<?php

namespace App\Http\Controllers;

use App\Models\OT;
use App\Models\Cliente;
use App\Models\User;
use App\Models\EstadoOt;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\HistorialOT;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class OTController extends Controller
{
    public function index(Request $request)
    {
        $query = OT::with(['cliente', 'responsable', 'estadoOT']);

        if ($request->filled('cliente')) {
            $query->where('id_cliente', $request->cliente);
        }
        if ($request->filled('responsable')) {
            $query->where('id_responsable', $request->responsable);
        }
        if ($request->filled('estado')) {
            $query->where('id_estado', $request->estado);
        }
        if ($request->filled('fecha_creacion')) {
            $query->whereDate('fecha_creacion', $request->fecha_creacion);
        }

        $ordenes = $query->orderBy('fecha_creacion', 'desc')->paginate(10);

        $clientes     = Cliente::pluck('nombre_cliente', 'id_cliente');
        $responsables = User::whereIn('rol', ['Supervisor', 'Técnico'])->pluck('nombre', 'id');
        $estados      = EstadoOt::pluck('nombre_estado', 'id_estado');

        return view('ot.index', compact('ordenes', 'clientes', 'responsables', 'estados'));
    }

    public function create()
    {
        $clientes     = Cliente::pluck('nombre_cliente', 'id_cliente');
        $responsables = User::whereIn('rol', ['Supervisor', 'Técnico'])->pluck('nombre', 'id');
        $estados      = EstadoOt::pluck('nombre_estado', 'id_estado');
        $servicios    = Servicio::pluck('nombre_servicio', 'id_servicio');
        $productos    = Producto::with('inventario')->where('estado', true)->get();

        return view('ot.create', compact('clientes', 'responsables', 'estados', 'servicios', 'productos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_cliente'           => 'required|exists:clientes,id_cliente',
            'id_responsable'       => 'required|exists:users,id',
            'id_estado'            => 'required|exists:estado_ot,id_estado',
            'fecha_entrega'        => 'nullable|date',
            'descripcion'          => 'nullable|string',
            'servicios'            => 'array',
            'servicios.*'          => 'exists:servicios,id_servicio',
            'productos'            => 'array',
            'productos.*.id'       => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'archivos.*'           => 'file',
        ]);

        DB::transaction(function () use ($data, $request) {
            // 1) Creo la OT
            $ot = OT::create([
                'id_cliente'     => $data['id_cliente'],
                'id_responsable' => $data['id_responsable'],
                'id_estado'      => $data['id_estado'],
                'fecha_entrega'  => $data['fecha_entrega'] ?? null,
            ]);

            // 2) Registro **solo** historial de creación
            HistorialOT::create([
                'id_ot'              => $ot->id_ot,
                'id_responsable'     => Auth::id(),
                'campo_modificado'   => 'Creación',
                'valor_anterior'     => null,
                'valor_nuevo'        => 'Orden creada',
                'fecha_modificacion' => Carbon::now()->timezone('America/Santiago'),
            ]);

            // 3) Ahora guardo el resto de datos sin más auditoría
            if (!empty($data['descripcion'])) {
                $ot->detalleOT()->create([
                    'descripcion_actividad' => $data['descripcion'],
                ]);
            }

            $ot->servicios()->sync($data['servicios'] ?? []);

            foreach ($data['productos'] as $prod) {
                $ot->detalleProductos()->create([
                    'id_producto' => $prod['id'],
                    'cantidad'    => $prod['cantidad'],
                ]);
            }

            if ($request->hasFile('archivos')) {
                foreach ($request->file('archivos') as $file) {
                    $path = $file->store('archivos_ot', 'public');
                    $ot->archivosAdjuntos()->create([
                        'ruta_archivo'    => $path,
                        'tipo_archivo'    => $file->getMimeType(),
                        'nombre_original' => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        return redirect()->route('ot.index')
            ->with('success', 'Orden creada correctamente.');
    }


    public function show($id)
    {
        // Cargo la OT con sus relaciones
        $ot = OT::with([
            'cliente',
            'responsable',
            'estadoOT',
            'servicios',
            'detalleOT',
            'detalleProductos.producto',
            'archivosAdjuntos'
        ])->findOrFail($id);

        // --- Nuevo: cargo todo el historial de esta OT, ordenado de mayor a menor por ID ---
        $todos = HistorialOT::with('usuario')
            ->where('id_ot', $id)
            ->orderByDesc('id_historial_ot')
            ->get();

        // Agrupo cambios que ocurren en la misma fecha/hora y mismo responsable
        $agrupado = $todos->groupBy(function ($h) {
            return "{$h->fecha_modificacion->format('Y-m-d H:i:s')}|{$h->id_responsable}";
        });

        // Preparo la colección final de bloques de historial
        $historial = $agrupado->map(function ($group) {
            $first = $group->first();
            return [
                'id_historial'       => $group->min('id_historial_ot'),
                'usuario'            => $first->usuario->nombre ?? 'Sistema',
                'fecha_modificacion' => $first->fecha_modificacion->format('Y-m-d H:i:s'),
                'campos'             => $group->pluck('campo_modificado')->unique()->values()->all(),
                'descripciones'      => $group->map(fn($h) => $this->descripcion($h))->all(),
            ];
        })
            // Aseguro que quede ordenado de mayor a menor ID de historial
            ->sortByDesc('id_historial')
            ->values();

        // Paso todo a la vista
        return view('ot.show', compact('ot', 'historial'));
    }


    public function edit($id)
    {
        $ot = OT::with([
            'cliente',
            'responsable',
            'estadoOT',
            'servicios',
            'detalleOT',
            'detalleProductos.producto'
        ])->findOrFail($id);

        $clientes     = Cliente::pluck('nombre_cliente', 'id_cliente');
        $responsables = User::whereIn('rol', ['Supervisor', 'Técnico'])->pluck('nombre', 'id');
        $estados      = EstadoOt::pluck('nombre_estado', 'id_estado');
        $servicios    = Servicio::pluck('nombre_servicio', 'id_servicio');
        $productos    = Producto::with('inventario')->where('estado', true)->get();

        return view('ot.edit', compact(
            'ot',
            'clientes',
            'responsables',
            'estados',
            'servicios',
            'productos'
        ));
    }

    public function update(Request $request, $id)
    {
        $ot   = OT::findOrFail($id);
        $data = $request->validate([
            'id_cliente'     => 'required|exists:clientes,id_cliente',
            'id_responsable' => 'required|exists:users,id',
            'id_estado'      => 'required|exists:estado_ot,id_estado',
            'fecha_entrega'  => 'nullable|date',
            'descripcion'    => 'nullable|string',
            'servicios'      => 'array',
            'servicios.*'    => 'exists:servicios,id_servicio',
            'productos'      => 'array',
            'productos.*.id' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'archivos.*'     => 'file',
        ]);

        DB::transaction(function () use ($ot, $data) {
            $labels = [
                'id_cliente'     => 'Cliente',
                'id_responsable' => 'Responsable',
                'id_estado'      => 'Estado',
                'fecha_entrega'  => 'Fecha de Entrega',
                'descripcion'    => 'Descripción',
                'servicios'      => 'Tipo de Trabajo',
                'productos'      => 'Productos Asociados',
            ];

            $cambios = [];

            // 1) Campos simples
            foreach (['id_cliente', 'id_responsable', 'id_estado', 'fecha_entrega'] as $campo) {
                $antes   = $ot->$campo;
                $despues = $data[$campo] ?? null;
                if ($antes == $despues) continue;

                switch ($campo) {
                    case 'id_cliente':
                        $valorAnterior = optional(Cliente::find($antes))->nombre_cliente ?? 'Sin asignar';
                        $valorNuevo    = optional(Cliente::find($despues))->nombre_cliente ?? 'Sin asignar';
                        break;
                    case 'id_responsable':
                        $valorAnterior = optional(User::find($antes))->nombre ?? 'Sin asignar';
                        $valorNuevo    = optional(User::find($despues))->nombre ?? 'Sin asignar';
                        break;
                    case 'id_estado':
                        $valorAnterior = optional(EstadoOt::find($antes))->nombre_estado ?? 'Sin asignar';
                        $valorNuevo    = optional(EstadoOt::find($despues))->nombre_estado ?? 'Sin asignar';
                        break;
                    case 'fecha_entrega':
                        $valorAnterior = $antes ? Carbon::parse($antes)->format('d/m/Y') : '-';
                        $valorNuevo    = $despues ? Carbon::parse($despues)->format('d/m/Y') : '-';
                        break;
                }

                $cambios[] = [
                    'campo'           => $labels[$campo],
                    'valor_anterior'  => $valorAnterior,
                    'valor_nuevo'     => $valorNuevo,
                ];
            }

            // 2) Descripción
            $descAntes = optional($ot->detalleOT->first())->descripcion_actividad ?? '';
            $descDesp  = $data['descripcion'] ?? '';
            if ($descAntes !== $descDesp) {
                $ot->detalleOT()->delete();
                if ($descDesp) {
                    $ot->detalleOT()->create(['descripcion_actividad' => $descDesp]);
                }
                $cambios[] = [
                    'campo'           => $labels['descripcion'],
                    'valor_anterior'  => $descAntes ?: 'Sin descripción',
                    'valor_nuevo'     => $descDesp  ?: 'Sin descripción',
                ];
            }

            // 3) Servicios
            $oldServ = $ot->servicios()->pluck('servicios.id_servicio')->toArray();
            $newServ = $data['servicios'] ?? [];
            if (array_diff($oldServ, $newServ) || array_diff($newServ, $oldServ)) {
                $antes   = Servicio::whereIn('id_servicio', $oldServ)->pluck('nombre_servicio')->implode(', ');
                $despues = Servicio::whereIn('id_servicio', $newServ)->pluck('nombre_servicio')->implode(', ');
                $cambios[] = [
                    'campo'           => $labels['servicios'],
                    'valor_anterior'  => $antes ?: '—',
                    'valor_nuevo'     => $despues ?: '—',
                ];
                $ot->servicios()->sync($newServ);
            }

            // 4) Productos
            // 4.1) Cambios de cantidad
            $oldProducts = $ot->detalleProductos()
                ->pluck('cantidad', 'id_producto')
                ->toArray();
            $newProducts = collect($data['productos'] ?? [])
                ->pluck('cantidad', 'id')
                ->toArray();

            foreach ($oldProducts as $prodId => $oldQty) {
                if (isset($newProducts[$prodId]) && $newProducts[$prodId] != $oldQty) {
                    $prod = Producto::find($prodId);
                    $label = "{$prod->marca} {$prod->modelo}";
                    $cambios[] = [
                        'campo'           => "Cantidad de {$label}",
                        'valor_anterior'  => $oldQty,
                        'valor_nuevo'     => $newProducts[$prodId],
                    ];
                }
            }

            // 4.2) Cambios de asociación
            $oldIds = array_keys($oldProducts);
            $newIds = array_keys($newProducts);
            if (array_diff($oldIds, $newIds) || array_diff($newIds, $oldIds)) {
                $cambios[] = [
                    'campo'           => $labels['productos'],
                    'valor_anterior'  => json_encode($oldIds),
                    'valor_nuevo'     => json_encode($newIds),
                ];
            }

            // 4.3) Reemplazo los detalles
            $ot->detalleProductos()->delete();
            foreach ($data['productos'] as $item) {
                $ot->detalleProductos()->create([
                    'id_producto' => $item['id'],
                    'cantidad'    => $item['cantidad'],
                ]);
            }

            // 5) Guardar historial
            foreach ($cambios as $chg) {
                HistorialOT::create([
                    'id_ot'              => $ot->id_ot,
                    'id_responsable'     => Auth::id(),
                    'campo_modificado'   => $chg['campo'],
                    'valor_anterior'     => $chg['valor_anterior'],
                    'valor_nuevo'        => $chg['valor_nuevo'],
                    'fecha_modificacion' => Carbon::now()->timezone('America/Santiago'),
                ]);
            }

            // 6) Actualizar OT
            $ot->update([
                'id_cliente'     => $data['id_cliente'],
                'id_responsable' => $data['id_responsable'],
                'id_estado'      => $data['id_estado'],
                'fecha_entrega'  => $data['fecha_entrega'] ?? null,
            ]);
        });

        return redirect()->route('ot.show', $ot->id_ot)
            ->with('success', 'Orden actualizada correctamente.');
    }

    public function desactivar($id_ot)
    {
        $ot = OT::findOrFail($id_ot);
        $ot->id_estado = 2;
        $ot->save();

        return back()->with('success', 'Orden de trabajo inhabilitada.');
    }

    public function reactivar($id_ot)
    {
        $ot = OT::findOrFail($id_ot);
        $ot->id_estado = 1;
        $ot->save();

        return back()->with('success', 'Orden de trabajo reactivada.');
    }

    public function historialGeneral(Request $request)
    {
        $query = HistorialOT::with(['usuario', 'ot'])
            ->orderByDesc('fecha_modificacion');

        if ($request->filled('ot')) {
            $query->where('id_ot', $request->ot);
        }
        if ($request->filled('campo')) {
            $query->where('campo_modificado', 'like', "%{$request->campo}%");
        }
        if ($request->filled('responsable')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->responsable}%");
            });
        }
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_modificacion', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $todos = $query->get();

        $agrupado = $todos->groupBy(function ($h) {
            return "{$h->id_ot}|{$h->fecha_modificacion->format('Y-m-d H:i:s')}|{$h->id_responsable}";
        });

        $historial = $agrupado->map(function ($group) {
            $minId = $group->min('id_historial_ot');
            $first = $group->first(fn($h) => $h->id_historial_ot === $minId);

            return [
                'id_historial'       => $first->id_historial_ot, // usa el ID real
                'id_ot'              => $first->id_ot,
                'usuario'            => $first->usuario->nombre ?? 'Sistema',
                'campos'             => $group->pluck('campo_modificado')->unique()->values()->all(),
                'fecha_modificacion' => $first->fecha_modificacion->format('Y-m-d H:i:s'),
                'descripciones'      => $group->map(fn($h) => $this->descripcion($h))->all(),
            ];
        })
            ->sortByDesc('id_historial')
            ->values();

        // --- aquí definimos página y paginamos ---
        $page    = $request->get('page', 1);
        $perPage = 10;
        $slice   = $historial->slice(($page - 1) * $perPage, $perPage)->all();

        $paginado = new LengthAwarePaginator(
            $slice,
            $historial->count(),
            $perPage,
            $page,
            [
                'path'  => route('ot.historial.global'),
                'query' => $request->query(),
            ]
        );

        return view('ot.historial', ['historial' => $paginado]);
    }


    protected function descripcion(HistorialOT $h): string
    {
        switch ($h->campo_modificado) {
            case 'Cliente':
                $ant = optional(Cliente::find($h->valor_anterior))->nombre_cliente ?? $h->valor_anterior;
                $nue = optional(Cliente::find($h->valor_nuevo))->nombre_cliente ?? $h->valor_nuevo;
                return "<strong>Cliente</strong> de <em>{$ant}</em> a <em>{$nue}</em>";
            case 'Responsable':
                $ant = optional(User::find($h->valor_anterior))->nombre ?? $h->valor_anterior;
                $nue = optional(User::find($h->valor_nuevo))->nombre ?? $h->valor_nuevo;
                return "<strong>Responsable</strong> de <em>{$ant}</em> a <em>{$nue}</em>";
            case 'Estado':
                $ant = optional(EstadoOt::find($h->valor_anterior))->nombre_estado ?? $h->valor_anterior;
                $nue = optional(EstadoOt::find($h->valor_nuevo))->nombre_estado ?? $h->valor_nuevo;
                return "<strong>Estado</strong> de <em>{$ant}</em> a <em>{$nue}</em>";
            case 'Tipo de Trabajo':
                $ant = Servicio::whereIn('id_servicio', json_decode($h->valor_anterior, true) ?? [])->pluck('nombre_servicio')->implode(', ');
                $nue = Servicio::whereIn('id_servicio', json_decode($h->valor_nuevo, true) ?? [])->pluck('nombre_servicio')->implode(', ');
                return "<strong>Tipo de Trabajo</strong> de <em>{$h->valor_anterior}</em> a <em>{$h->valor_nuevo}</em>";
            case 'Descripción':
                return "<strong>Descripción</strong> de <em>{$h->valor_anterior}</em> a <em>{$h->valor_nuevo}</em>";
            case 'Productos Asociados':
                // Decodifica los JSON guardados
                $idsAntes = json_decode($h->valor_anterior, true) ?: [];
                $idsNuevo = json_decode($h->valor_nuevo, true)   ?: [];

                // Construye "Marca Modelo" para cada ID
                $antesList = Producto::whereIn('id_producto', $idsAntes)
                    ->get()
                    ->map(fn($p) => "{$p->marca} {$p->modelo}")
                    ->implode(', ');

                $nuevoList = Producto::whereIn('id_producto', $idsNuevo)
                    ->get()
                    ->map(fn($p) => "{$p->marca} {$p->modelo}")
                    ->implode(', ');

                return "<strong>Productos Asociados</strong> de <em>{$antesList}</em> a <em>{$nuevoList}</em>";

            case 'Creación':
                return '<strong>Se ha creado la Orden de Trabajo.</strong>';
            default:
                return "<strong>{$h->campo_modificado}</strong> de <em>{$h->valor_anterior}</em> a <em>{$h->valor_nuevo}</em>";
        }
    }

    public function exportOrdenes($id)
    {
        // Cargar la OT con sus relaciones
        $ordenTrabajo = OT::with([
            'cliente',
            'responsable',
            'estadoOT',
            'detalleProductos.producto',
            'servicios',
            'archivosAdjuntos',
        ])->findOrFail($id);

        // Obtener y agrupar el historial como en el método 'show'
        $todos = HistorialOT::with('usuario')
            ->where('id_ot', $id)
            ->orderByDesc('id_historial_ot')
            ->get();

        $agrupado = $todos->groupBy(function ($h) {
            return "{$h->fecha_modificacion->format('Y-m-d H:i:s')}|{$h->id_responsable}";
        });

        $historialTransformado = $agrupado->map(function ($group) {
            $first = $group->first();
            return [
                'id_historial'       => $group->min('id_historial_ot'),
                'usuario'            => $first->usuario->nombre ?? 'Sistema',
                'fecha_modificacion' => $first->fecha_modificacion->format('Y-m-d H:i:s'),
                'campos'             => $group->pluck('campo_modificado')->unique()->values()->all(),
                'descripciones'      => $group->map(fn($h) => app(OTController::class)->descripcion($h))->all(),
            ];
        })->sortByDesc('id_historial')->values();

        // Generar base64 del logo
        $logoPath = public_path('images/logo.png');
        $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoData = file_get_contents($logoPath);
        $base64Logo = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);

        // Fuente (aunque ahora usás DejaVu, dejo esto por si volvés a usar Cairo)
        $fontPath = resource_path('fonts/CairoPlay-Regular.ttf');
        $base64Font = file_exists($fontPath) ? base64_encode(file_get_contents($fontPath)) : '';

        // Renderizar la vista PDF
        $view = View::make('ot.pdf', [
            'ordenTrabajo' => $ordenTrabajo,
            'historial'    => $historialTransformado,
            'base64Logo'   => $base64Logo,
            'base64Font'   => $base64Font,
        ]);

        $html = $view->render();

        // Generar y retornar el PDF
        $pdf = Pdf::loadHTML($html)->setPaper('A4', 'portrait');
        $fechaDescarga = now()->format('Y-m-d_H-i-s');

        return $pdf->download("OrdenTrabajo_{$ordenTrabajo->id_ot}_{$fechaDescarga}.pdf");
    }

    public function exportarListadoOT(Request $request)
    {
        $query = OT::with(['cliente', 'responsable', 'estadoOT']);

        if ($request->filled('cliente')) {
            $query->where('id_cliente', $request->cliente);
        }
        if ($request->filled('responsable')) {
            $query->where('id_responsable', $request->responsable);
        }
        if ($request->filled('estado')) {
            $query->where('id_estado', $request->estado);
        }
        if ($request->filled('fecha_creacion')) {
            $query->whereDate('fecha_creacion', $request->fecha_creacion);
        }

        $ordenes = $query->orderBy('fecha_creacion', 'desc')->get();

        // Base64 logo si querés incluirlo
        $logoPath = public_path('images/logo.png');
        $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoData = file_get_contents($logoPath);
        $base64Logo = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);

        $view = View::make('ot.reporte', [
            'ordenes' => $ordenes,
            'base64Logo' => $base64Logo,
        ]);

        $pdf = Pdf::loadHTML($view->render())->setPaper('A4', 'landscape');

        $fecha = now()->format('Y-m-d_H-i-s');
        return $pdf->download("Listado_OTs_{$fecha}.pdf");
    }
}
