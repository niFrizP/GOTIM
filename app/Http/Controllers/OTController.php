<?php

namespace App\Http\Controllers;

use App\Models\OT;
use App\Models\Cliente;
use App\Models\User;
use App\Models\EstadoOt;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\HistorialOT;
use Illuminate\Support\Facades\Storage;
use App\Models\ArchivoAdjuntoOT;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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

        $clientes = Cliente::pluck('nombre_cliente', 'apellido_cliente', 'id_cliente', 'rut');
        $responsables = User::whereIn('rol', ['Supervisor', 'Técnico'])->pluck('nombre', 'id');
        $estados = EstadoOt::pluck('nombre_estado', 'id_estado');

        return view('ot.index', compact('ordenes', 'clientes', 'responsables', 'estados'));
    }

    public function create()
    {
        $clientes = Cliente::select('id_cliente', 'nombre_cliente', 'apellido_cliente', 'rut')
            ->get()
            ->mapWithKeys(function ($cliente) {
                return [$cliente->id_cliente => $cliente->nombre_cliente . ' ' . $cliente->apellido_cliente . ' (' . $cliente->rut . ')'];
            });
        $responsables = User::whereIn('rol', ['Supervisor', 'Técnico'])
            ->get()
            ->mapWithKeys(function ($user) {
                return [$user->id => $user->nombre . ' ' . $user->apellido . ' (' . $user->rol . ')'];
            });
        $servicios = Servicio::pluck('nombre_servicio', 'id_servicio');

        return view('ot.create', compact('clientes', 'responsables', 'servicios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_responsable' => 'required|exists:users,id',
            'fecha_entrega' => ['required', 'date', 'after_or_equal:today'],
            'descripcion' => 'nullable|string',
            'servicios' => 'array',
            'servicios.*' => 'exists:servicios,id_servicio',
            'archivos.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx|max:10240',
        ], $messages = [
                'archivos.*.mimes' => 'Solo se permiten archivos JPG, PNG, PDF, Word o Excel.',
                'archivos.*.max' => 'El archivo no debe superar los 10MB.',
            ]);
        // Asignar el estado inicial de la OT
        $data['id_estado'] = 1; // Estado "Recepcionada"
        // Validación adicional: la fecha_entrega no puede ser mayor a 5 años desde hoy
        if (!empty($data['fecha_entrega'])) {
            $fechaEntrega = Carbon::parse($data['fecha_entrega']);
            $limiteSuperior = now()->addYears(5);

            if ($fechaEntrega->gt($limiteSuperior)) {
                return redirect()->back()
                    ->withErrors([
                        'fecha_entrega' => 'La fecha de entrega no puede superar los 5 años desde hoy (' . $limiteSuperior->format('d/m/Y') . ').'
                    ])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($data, $request) {
            // 1) Creo la OT
            $ot = OT::create([
                'id_cliente' => $data['id_cliente'],
                'id_responsable' => $data['id_responsable'],
                'id_estado' => $data['id_estado'],
                'fecha_entrega' => $data['fecha_entrega'] ?? null,
            ]);

            // 2) Registrar historial de creación y campos
            $camposHistorial = [];

            $cliente = Cliente::find($data['id_cliente']);
            $responsable = User::find($data['id_responsable']);
            $fechaEntrega = Carbon::parse($data['fecha_entrega'])->format('d/m/Y');

            $camposHistorial[] = "<strong>Cliente</strong>: <em>{$cliente->nombre_cliente} {$cliente->apellido_cliente}</em>";
            $camposHistorial[] = "<strong>Responsable</strong>: <em>{$responsable->nombre} {$responsable->apellido}</em>";
            $camposHistorial[] = "<strong>Fecha de entrega</strong>: <em>{$fechaEntrega}</em>";

            if (!empty($data['descripcion'])) {
                $camposHistorial[] = "<strong>Descripción</strong>: <em>{$data['descripcion']}</em>";
            }

            if (!empty($data['servicios'])) {
                $nombresServicios = Servicio::whereIn('id_servicio', $data['servicios'])->pluck('nombre_servicio')->implode(', ');
                $camposHistorial[] = "<strong>Servicios</strong>: <em>{$nombresServicios}</em>";
            }

            if ($request->hasFile('archivos')) {
                $nombresArchivos = collect($request->file('archivos'))->map(fn($f) => $f->getClientOriginalName())->implode(', ');
                $camposHistorial[] = "<strong>Archivo(s) adjunto(s)</strong>: <em>{$nombresArchivos}</em>";
            }

            HistorialOT::create([
                'id_ot' => $ot->id_ot,
                'id_responsable' => Auth::id(),
                'campo_modificado' => 'Creación',
                'valor_anterior' => null,
                'valor_nuevo' => implode('<br>', $camposHistorial),
                'fecha_modificacion' => Carbon::now()->timezone('America/Santiago'),
            ]);


            // 3) Ahora guardo el resto de datos sin más auditoría
            if (!empty($data['descripcion'])) {
                $ot->detalleOT()->create([
                    'descripcion_actividad' => $data['descripcion'],
                ]);
            }

            $ot->servicios()->sync($data['servicios'] ?? []);


            if ($request->hasFile('archivos')) {
                foreach ($request->file('archivos') as $file) {
                    $path = $file->store('archivos_ot', 'public');
                    $ot->archivosAdjuntos()->create([
                        'ruta_archivo' => $path,
                        'tipo_archivo' => $file->getMimeType(),
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

        // --- Nuevo: cargo en su totalidad el historial de esta OT, ordenado de mayor a menor por ID ---
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
            $campos = collect($group)->pluck('campo_modificado')->implode(',');
            $listaCampos = collect(explode(',', $campos))->map(fn($c) => trim($c))->unique()->values()->all();

            return [
                'id_historial' => $group->min('id_historial_ot'),
                'usuario' => $first->usuario->nombre ?? 'Sistema',
                'fecha_modificacion' => $first->fecha_modificacion->format('Y-m-d H:i:s'),
                'campos' => collect($listaCampos)->map(function ($campo) {
                    return $campo === 'nueva_descripcion' ? 'Descripción' : $campo;
                })->all(),

                'descripciones' => $group->map(fn($h) => $this->descripcion($h))->all(),
            ];
        })->sortByDesc('id_historial')->values();

        // Paso en su totalidad a la vista
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
            'detalleProductos.producto',
            'archivosAdjuntos'
        ])->findOrFail($id);

        $clientes = Cliente::select('id_cliente', 'nombre_cliente', 'apellido_cliente', 'rut')
            ->get()
            ->mapWithKeys(function ($cliente) {
                return [$cliente->id_cliente => $cliente->nombre_cliente . ' ' . $cliente->apellido_cliente . ' (' . $cliente->rut . ')'];
            });
        $responsables = User::whereIn('rol', ['Supervisor', 'Técnico'])
            ->get()
            ->mapWithKeys(function ($user) {
                return [$user->id => $user->nombre . ' ' . $user->apellido . ' (' . $user->rol . ')'];
            });
        $estados = EstadoOt::pluck('nombre_estado', 'id_estado');
        $servicios = Servicio::pluck('nombre_servicio', 'id_servicio');

        // 🔴 Solo productos con inventario > 0
        $productos = Producto::where('estado', true)
            ->whereHas('inventario', function ($q) {
                $q->where('cantidad', '>', 0);
            })
            ->with('inventario')
            ->get();

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
        $ot = OT::findOrFail($id);

        // ✅ Paso 1: Validación con Laravel
        $data = $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_responsable' => 'required|exists:users,id',
            'id_estado' => 'required|exists:estado_ot,id_estado',
            'fecha_entrega' => 'nullable|date',
            'descripcion' => 'nullable|string',
            'servicios' => 'array',
            'servicios.*' => 'exists:servicios,id_servicio',
            'productos' => 'array',
            'productos.*.id' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'archivos.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx|max:10240',
        ], [
            'archivos.*.mimes' => 'Solo se permiten archivos JPG, PNG, PDF, Word o Excel.',
            'archivos.*.max' => 'El archivo no debe superar los 10MB.',
        ]);

        // ✅ Paso 2: Validación personalizada de fecha_entrega
        if (!empty($data['fecha_entrega'])) {
            $fechaEntrega = Carbon::parse($data['fecha_entrega']);
            $fechaCreacion = Carbon::parse($ot->fecha_creacion);
            $limiteSuperior = now()->addYears(5);

            if ($fechaEntrega->lt($fechaCreacion)) {
                return redirect()->back()
                    ->withErrors([
                        'fecha_entrega' => 'La fecha de entrega no puede ser anterior a la fecha de creación de la orden (' . $fechaCreacion->format('d/m/Y') . ').'
                    ])
                    ->withInput();
            }

            if ($fechaEntrega->gt($limiteSuperior)) {
                return redirect()->back()
                    ->withErrors([
                        'fecha_entrega' => 'La fecha de entrega no puede superar los 5 años desde hoy (' . $limiteSuperior->format('d/m/Y') . ').'
                    ])
                    ->withInput();
            }
        }
        DB::transaction(function () use ($ot, $data) {
            $labels = [
                'id_cliente' => 'Cliente',
                'id_responsable' => 'Responsable',
                'id_estado' => 'Estado',
                'fecha_entrega' => 'Fecha de Entrega',
                'descripcion' => 'Descripción',
                'servicios' => 'Tipo de Trabajo',
                'productos' => 'Productos Asociados',
            ];

            $cambios = [];

            // 1) Campos simples
            foreach (['id_cliente', 'id_responsable', 'id_estado', 'fecha_entrega'] as $campo) {
                $antes = $ot->$campo;
                $despues = $data[$campo] ?? null;
                if ($antes == $despues)
                    continue;

                switch ($campo) {
                    case 'id_cliente':
                        $valorAnterior = optional(Cliente::find($antes))->nombre_cliente ?? 'Sin asignar';
                        $valorNuevo = optional(Cliente::find($despues))->nombre_cliente ?? 'Sin asignar';
                        break;
                    case 'id_responsable':
                        $valorAnterior = optional(User::find($antes))->nombre ?? 'Sin asignar';
                        $valorNuevo = optional(User::find($despues))->nombre ?? 'Sin asignar';
                        break;
                    case 'id_estado':
                        $valorAnterior = optional(EstadoOt::find($antes))->nombre_estado ?? 'Sin asignar';
                        $valorNuevo = optional(EstadoOt::find($despues))->nombre_estado ?? 'Sin asignar';
                        break;
                    case 'fecha_entrega':
                        $valorAnterior = $antes ? Carbon::parse($antes)->format('d/m/Y') : '-';
                        $valorNuevo = $despues ? Carbon::parse($despues)->format('d/m/Y') : '-';
                        break;
                }

                $cambios[] = [
                    'campo' => $labels[$campo],
                    'valor_anterior' => $valorAnterior,
                    'valor_nuevo' => $valorNuevo,
                ];
            }

            // 2) Descripción
            $descAntes = optional($ot->detalleOT->first())->descripcion_actividad ?? '';
            $descDesp = $data['descripcion'] ?? '';
            if ($descAntes !== $descDesp) {
                $ot->detalleOT()->delete();
                if ($descDesp) {
                    $ot->detalleOT()->create(['descripcion_actividad' => $descDesp]);
                }

                // Campo personalizado para mostrar "Nueva descripción" en historial
                $cambios[] = [
                    'campo' => 'nueva_descripcion',
                    'valor_anterior' => null,
                    'valor_nuevo' => $descDesp ?: 'Sin descripción',
                ];
            }


            // 3) Servicios
            $oldServ = $ot->servicios()->pluck('servicios.id_servicio')->toArray();
            $newServ = $data['servicios'] ?? [];
            if (array_diff($oldServ, $newServ) || array_diff($newServ, $oldServ)) {
                $antes = Servicio::whereIn('id_servicio', $oldServ)->pluck('nombre_servicio')->implode(', ');
                $despues = Servicio::whereIn('id_servicio', $newServ)->pluck('nombre_servicio')->implode(', ');
                $cambios[] = [
                    'campo' => $labels['servicios'],
                    'valor_anterior' => $antes ?: '—',
                    'valor_nuevo' => $despues ?: '—',
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
                    $label = "{$prod->nombre_producto} {$prod->marca} {$prod->modelo}";
                    $cambios[] = [
                        'campo' => "Cantidad de {$label}",
                        'valor_anterior' => $oldQty,
                        'valor_nuevo' => $newProducts[$prodId],
                    ];
                }
            }

            // 4.2) Cambios de asociación
            $oldIds = array_keys($oldProducts);
            $newIds = array_keys($newProducts);
            if (array_diff($oldIds, $newIds) || array_diff($newIds, $oldIds)) {
                $cambios[] = [
                    'campo' => $labels['productos'],
                    'valor_anterior' => json_encode($oldIds),
                    'valor_nuevo' => json_encode($newIds),
                ];
            }

            // 4.3) Reemplazo los detalles con control de inventario y registro en historial
            $oldDetalle = $ot->detalleProductos()->get();

            // 4.3.1. Revertir stock de productos eliminados o modificados
            foreach ($oldDetalle as $detalle) {
                $inventario = $detalle->producto->inventario->first();

                if ($inventario) {
                    $stockAnterior = $inventario->cantidad;
                    $inventario->cantidad += $detalle->cantidad;
                    $inventario->save();

                    // Actualizar estado del producto
                    $producto = $detalle->producto;
                    $inventario->estado = $inventario->cantidad > 0 ? 'activo' : 'agotado';
                    $inventario->save();


                    // Registrar devolución en historial
                    \App\Models\HistorialInventario::create([
                        'id_inventario' => $inventario->id_inventario,
                        'id_producto' => $detalle->id_producto,
                        'id_responsable' => Auth::id(),
                        'campo_modificado' => 'cantidad',
                        'valor_anterior' => $stockAnterior,
                        'valor_nuevo' => $inventario->cantidad,
                        'fecha_modificacion' => now(),
                    ]);
                }
            }
            // 4.3.2. Eliminar detalles anteriores
            $ot->detalleProductos()->delete();

            // 4.3.3. Registrar nuevos productos y descontar del inventario
            foreach ($data['productos'] ?? [] as $item) {
                $producto = \App\Models\Producto::with('inventario')->find($item['id']);
                $inventario = $producto->inventario->first();
                $stockAnterior = $inventario->cantidad ?? 0;

                if ($stockAnterior < $item['cantidad']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "productos.{$item['id']}" => "Stock insuficiente para el producto {$producto->marca} {$producto->modelo}. Disponible: {$stockAnterior}, requerido: {$item['cantidad']}",
                    ]);
                }

                // Descontar del stock
                $inventario->cantidad -= $item['cantidad'];
                $inventario->save();

                // Actualizar estado del producto
                $inventario->estado = $inventario->cantidad > 0 ? 'activo' : 'agotado';
                $inventario->save();


                // Registrar descuento en historial
                \App\Models\HistorialInventario::create([
                    'id_inventario' => $inventario->id_inventario,
                    'id_producto' => $item['id'],
                    'id_responsable' => Auth::id(),
                    'campo_modificado' => 'cantidad',
                    'valor_anterior' => $stockAnterior,
                    'valor_nuevo' => $inventario->cantidad,
                    'fecha_modificacion' => now(),
                ]);

                // Asociar a la OT
                $ot->detalleProductos()->create([
                    'id_producto' => $item['id'],
                    'cantidad' => $item['cantidad'],
                ]);
            }


            // 5) Guardar historial
            $cambiosReales = collect($cambios)->reject(fn($c) => $c['valor_anterior'] === $c['valor_nuevo']);

            if ($cambiosReales->isNotEmpty()) {
                $descripcion = $cambiosReales->map(function ($chg) {
                    return match ($chg['campo']) {
                        'Productos Asociados' => (function () use ($chg) {
                                $idsAntes = json_decode($chg['valor_anterior'], true) ?? [];
                                $idsNuevo = json_decode($chg['valor_nuevo'], true) ?? [];

                                $agregados = array_diff($idsNuevo, $idsAntes);
                                $eliminados = array_diff($idsAntes, $idsNuevo);

                                if (!empty($agregados) && empty($eliminados)) {
                                    $nombres = \App\Models\Producto::whereIn('id_producto', $agregados)
                                    ->get()->map(fn($p) => "{$p->nombre_producto} {$p->modelo} {$p->marca}")->implode(', ');
                                    return "<strong>Producto(s) agregado(s)</strong>: <em>{$nombres}</em>";
                                }

                                if (!empty($eliminados) && empty($agregados)) {
                                    $nombres = \App\Models\Producto::whereIn('id_producto', $eliminados)
                                    ->get()->map(fn($p) => "{$p->nombre_producto} {$p->modelo} {$p->marca}")->implode(', ');
                                    return "<strong>Producto(s) eliminado(s)</strong>: <em>{$nombres}</em>";
                                }

                                if (!empty($agregados) && !empty($eliminados)) {
                                    $nombresAntes = \App\Models\Producto::whereIn('id_producto', $idsAntes)
                                    ->get()->map(fn($p) => "{$p->nombre_producto} {$p->modelo} {$p->marca}")->implode(', ');
                                    $nombresNuevo = \App\Models\Producto::whereIn('id_producto', $idsNuevo)
                                    ->get()->map(fn($p) => "{$p->nombre_producto} {$p->modelo} {$p->marca}")->implode(', ');

                                    return "<strong>Productos Asociados modificados</strong>: de <em>{$nombresAntes}</em> a <em>{$nombresNuevo}</em>";
                                }

                                return null;
                            })(),

                        // 🔧 Aquí está el cambio que tú pediste
                        'nueva_descripcion' => "<strong>Nueva descripción</strong>: <em>{$chg['valor_nuevo']}</em>",

                        // 🔁 Resto de campos (cliente, responsable, estado, etc.)
                        default => "<strong>{$chg['campo']}</strong> de <em>{$chg['valor_anterior']}</em> a <em>{$chg['valor_nuevo']}</em>",
                    };
                })->implode("\n");

                HistorialOT::create([
                    'id_ot' => $ot->id_ot,
                    'id_responsable' => Auth::id(),
                    'campo_modificado' => implode(',', $cambiosReales->pluck('campo')->toArray()),
                    'valor_anterior' => null,
                    'valor_nuevo' => $descripcion,
                    'fecha_modificacion' => Carbon::now()->timezone('America/Santiago'),
                ]);
            }
            // 6) Actualizar OT
            $ot->update([
                'id_cliente' => $data['id_cliente'],
                'id_responsable' => $data['id_responsable'],
                'id_estado' => $data['id_estado'],
                'fecha_entrega' => $data['fecha_entrega'] ?? null,
            ]);
            // 7) Archivos adjuntos nuevos
            if (request()->hasFile('archivos')) {
                $nombresNuevos = [];

                foreach (request()->file('archivos') as $archivo) {
                    $path = $archivo->store('archivos_ot', 'public');

                    $ot->archivosAdjuntos()->create([
                        'ruta_archivo' => $path,
                        'tipo_archivo' => $archivo->getMimeType(),
                        'nombre_original' => $archivo->getClientOriginalName(),
                    ]);

                    $nombresNuevos[] = $archivo->getClientOriginalName();
                }

                if (!empty($nombresNuevos)) {
                    HistorialOT::create([
                        'id_ot' => $ot->id_ot,
                        'id_responsable' => Auth::id(),
                        'campo_modificado' => 'Archivos Adjuntos',
                        'valor_anterior' => null,
                        'valor_nuevo' => '<span class="text-green-600 dark:text-green-400"><i class="fa-solid fa-circle-plus"></i> Archivo(s) agregado(s)</span>: <em>' . implode(', ', $nombresNuevos) . '</em>',
                        'fecha_modificacion' => Carbon::now()->timezone('America/Santiago'),
                    ]);
                }
            }
        });

        return redirect()->route('ot.show', $ot->id_ot)
            ->with('success', 'Orden actualizada correctamente.');
    }

    public function desactivar($id_ot)
    {
        $ot = OT::findOrFail($id_ot);
        $ot->fase = 'Inhabiltiado';
        $ot->save();

        return back()->with('success', 'Orden de trabajo inhabilitada.');
    }

    public function reactivar($id_ot)
    {
        $ot = OT::findOrFail($id_ot);
        $ot->fase = 'Habilitado';
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
        if ($request->filled('cliente')) {
            $query->whereHas('ot.cliente', function ($q) use ($request) {
                $q->where('nombre_cliente', 'like', '%' . $request->cliente . '%')
                    ->orWhere('apellido_cliente', 'like', '%' . $request->cliente . '%');
            });
        }

        if ($request->filled('rut')) {
            $query->whereHas('ot.cliente', function ($q) use ($request) {
                $rut = preg_replace('/[^0-9kK]/', '', $request->rut);
                $q->whereRaw("REPLACE(REPLACE(REPLACE(rut, '.', ''), '-', ''), ' ', '') LIKE ?", ["%{$rut}%"]);
            });
        }
        if ($request->filled('responsable')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->responsable}%");
            });
        }
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();

            $query->whereBetween('fecha_modificacion', [$fechaInicio, $fechaFin]);
        }


        $todos = $query->get();

        $agrupado = $todos->groupBy(function ($h) {
            return "{$h->id_ot}|{$h->fecha_modificacion->format('Y-m-d H:i:s')}|{$h->id_responsable}";
        });

        $historial = $agrupado->map(function ($group) {
            $minId = $group->min('id_historial_ot');
            $first = $group->first(fn($h) => $h->id_historial_ot === $minId);

            $campos = collect($group)->pluck('campo_modificado')->implode(',');
            $listaCampos = collect(explode(',', $campos))->map(fn($c) => trim($c))->unique()->values()->all();

            return [
                'id_historial' => $first->id_historial_ot,
                'id_ot' => $first->id_ot,
                'cliente' => optional($first->ot->cliente)->nombre_cliente . ' ' . optional($first->ot->cliente)->apellido_cliente,
                'rut' => optional($first->ot->cliente)->rut,
                'usuario' => $first->usuario->nombre ?? 'Sistema',
                'campos' => collect($listaCampos)->map(function ($campo) {
                    return $campo === 'nueva_descripcion' ? 'Descripción' : $campo;
                })->all(),

                'fecha_modificacion' => $first->fecha_modificacion->format('Y-m-d H:i:s'),
                'descripciones' => $group->map(fn($h) => $this->descripcion($h))->all(),
            ];
        })->sortByDesc('id_historial')->values();


        // --- aquí definimos página y paginamos ---
        $page = $request->get('page', 1);
        $perPage = 10;
        $slice = $historial->slice(($page - 1) * $perPage, $perPage)->all();

        $paginado = new LengthAwarePaginator(
            $slice,
            $historial->count(),
            $perPage,
            $page,
            [
                'path' => route('ot.historial.global'),
                'query' => $request->query(),
            ]
        );

        return view('ot.historial', ['historial' => $paginado]);
    }
    protected function descripcion(HistorialOT $h): string
    {
        if ($h->campo_modificado === 'Creación') {
            $lineas = preg_split('/\r\n|\r|\n|<br\s*\/?>/i', $h->valor_nuevo);
            $lineas = array_filter(array_map('trim', $lineas));

            $html = '<li><strong>Se ha creado la Orden de Trabajo con los siguientes datos:</strong></li>';
            foreach ($lineas as $linea) {
                if (!empty($linea)) {
                    $html .= "<li>{$linea}</li>";
                }
            }

            return $html;
        }


        if (is_string($h->valor_nuevo) && str_contains($h->valor_nuevo, '<strong>')) {
            // Si es un bloque consolidado con HTML, divídelo en líneas
            $lineas = preg_split('/\r\n|\r|\n/', $h->valor_nuevo);
            return collect($lineas)
                ->filter()
                ->map(fn($linea) => trim($linea))
                ->map(fn($linea) => "<li>{$linea}</li>")
                ->implode('');
        }

        return "<li><strong>{$h->campo_modificado}</strong> de <em>{$h->valor_anterior}</em> a <em>{$h->valor_nuevo}</em></li>";
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

        // Obtener y agrupar el historial como en la función 'show'
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
                'id_historial' => $group->min('id_historial_ot'),
                'usuario' => $first->usuario->nombre ?? 'Sistema',
                'fecha_modificacion' => $first->fecha_modificacion->format('Y-m-d H:i:s'),
                'campos' => $group->pluck('campo_modificado')->unique()->values()->all(),
                'descripciones' => $group->map(fn($h) => app(OTController::class)->descripcion($h))->all(),
            ];
        })->sortByDesc('id_historial')->values();

        // Generar base64 del logo
        $logoPath = public_path('images/logo.png');
        $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoData = file_get_contents($logoPath);
        $base64Logo = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);

        // Fuente opcional
        $fontPath = resource_path('fonts/CairoPlay-Regular.ttf');
        $base64Font = file_exists($fontPath) ? base64_encode(file_get_contents($fontPath)) : '';

        // Convertir archivos adjuntos (solo imágenes)
        $archivosConvertidos = collect();
        foreach ($ordenTrabajo->archivosAdjuntos as $archivo) {
            if (!$archivo->ruta || !Str::startsWith($archivo->ruta, 'storage/')) {
                continue;
            }

            $rutaCompleta = public_path($archivo->ruta);

            if (is_file($rutaCompleta)) {
                $tipo = @getimagesize($rutaCompleta)['mime'] ?? null;

                if ($tipo) {
                    $datos = file_get_contents($rutaCompleta);
                    $base64 = 'data:' . $tipo . ';base64,' . base64_encode($datos);

                    $archivosConvertidos->push([
                        'nombre' => $archivo->nombre_archivo,
                        'base64' => $base64
                    ]);
                }
            }

            // Si no es imagen o ruta inválida
            $archivosConvertidos->push([
                'nombre' => $archivo->nombre_archivo,
                'base64' => null
            ]);
        }

        // Renderizar vista
        $view = View::make('ot.pdf', [
            'ordenTrabajo' => $ordenTrabajo,
            'historial' => $historialTransformado,
            'base64Logo' => $base64Logo,
            'base64Font' => $base64Font,
            'archivosAdjuntos' => $archivosConvertidos,
        ]);

        $html = $view->render();

        // Generar PDF
        $pdf = Pdf::loadHTML($html)->setPaper('A4', 'portrait');

        // Agregar paginación personalizada
        $pdf->render(function ($dompdf) {
            $canvas = $dompdf->getCanvas();
            $fontMetrics = new \Dompdf\FontMetrics($canvas, $dompdf->getOptions());
            $font = $fontMetrics->getFont('helvetica', 'normal');
            $pageCount = $canvas->get_page_count();

            for ($i = 1; $i <= $pageCount; $i++) {
                $text = "Página $i de $pageCount";
                $textWidth = $fontMetrics->getTextWidth($text, $font, 9);
                $x = ($canvas->get_width() - $textWidth) / 2;
                $y = $canvas->get_height() - 30;

                $canvas->page_text(50, 50, "¡Hola página $i de $pageCount!", $font, 12, [255, 0, 0], $i);
            }
        });

        // Descargar el PDF
        $fechaDescarga = now()->format('Y-m-d_H-i-s');
        return $pdf->download("OT_{$ordenTrabajo->id_ot}_{$fechaDescarga}.pdf");
    }
    public function eliminarArchivo($id)
    {
        $archivo = ArchivoAdjuntoOT::findOrFail($id);
        $nombre = $archivo->nombre_original;
        $id_ot = $archivo->id_ot;

        // Elimina el archivo físico si existe
        if (Storage::exists($archivo->ruta_archivo)) {
            Storage::delete($archivo->ruta_archivo);
        }

        // Elimina el registro de la base de datos
        $archivo->delete();

        // Registrar en historial
        HistorialOT::create([
            'id_ot' => $id_ot,
            'id_responsable' => Auth::id(),
            'campo_modificado' => 'Archivos Adjuntos',
            'valor_anterior' => $nombre,
            'valor_nuevo' => '<span class="text-red-600 dark:text-red-400"><i class="fa-solid fa-trash"></i> Archivo eliminado</span>: <em>' . $nombre . '</em>',
            'fecha_modificacion' => Carbon::now()->timezone('America/Santiago'),
        ]);

        return redirect()->back()->with('success', 'Archivo eliminado correctamente.');
    }


    // Exporta el listado de OTs a PDF
    public function exportarListadoOT(Request $request)
    {
        // Validar los parámetros de búsqueda
        $validator = Validator::make($request->all(), [
            'buscar' => 'nullable|string|max:255',
            'estado' => 'nullable|integer|exists:estado_ot,id_estado',
            'responsable' => 'nullable|integer|exists:users,id',
            'fechaInicio' => 'nullable|date_format:Y-m-d',
        ]);

        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            // Podés redirigir, lanzar error o devolver algo para que no pete
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // Si la validación es exitosa, proceder a buscar las órdenes de trabajo
        $ordenes = OT::with(['cliente', 'responsable', 'estadoOT'])
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $search = $request->input('buscar');
                $query->whereHas('cliente', fn($q) => $q->where('nombre_cliente', 'like', "%$search%"))
                    ->orWhereHas('responsable', fn($q) => $q->where('nombre', 'like', "%$search%"))
                    ->orWhereHas('estadoOT', fn($q) => $q->where('nombre_estado', 'like', "%$search%"))
                    ->orWhere('id_ot', 'like', "%$search%");
            })
            ->when($request->filled('estado'), fn($q) => $q->where('id_estado', $request->estado))
            ->when($request->filled('responsable'), fn($q) => $q->where('id_responsable', $request->responsable))
            ->when($request->filled('fecha_creacion'), function ($q) use ($request) {
                $inicio = Carbon::parse($request->fecha_creacion)->startOfDay();
                $q->where('fecha_creacion', '>=', $inicio);
            })
            ->orderByDesc('fecha_creacion')
            ->get();

        // Generar base64 del logo
        $logoPath = public_path('images/logo.png');
        $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoData = file_get_contents($logoPath);
        $base64Logo = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);

        if ($ordenes->isEmpty()) {
            $pdf = Pdf::loadView('ot.ot_listado', [
                'base64Logo' => $base64Logo,
                'ordenes' => collect(),
                'mensaje' => 'No se encontraron órdenes de trabajo con los filtros seleccionados.'
            ]);
        } else {
            $pdf = Pdf::loadView('ot.ot_listado', [
                'base64Logo' => $base64Logo,
                'ordenes' => $ordenes
            ]);
        }

        $fechaDescarga = now()->format('Y-m-d_H-i-s');
        return $pdf->download("OrdenesTrabajo_{$fechaDescarga}.pdf");
    }
}
