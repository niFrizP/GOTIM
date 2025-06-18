<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Ciudad;
use App\Models\Region;
use App\Models\Empresa;
use App\Rules\RutValido;

/**
 * Controlador para manejar las operaciones relacionadas con los clientes.
 */
class ClienteController extends Controller
{
    // Muestra la lista de clientes
    public function index()
    {
        $clientes = Cliente::with(['empresa', 'region', 'ciudad'])
            ->orderBy('id_cliente', 'desc')
            ->paginate(10);

        return view('clientes.index', compact('clientes'));
    }

    // Muestra el formulario de creación de un nuevo cliente
    public function create(Request $request)
    {
        $regiones = Region::all();
        $ciudades = Ciudad::all();

        if ($request->query('popup') == 1) {
            return view('clientes._form', compact('regiones', 'ciudades'));
        }

        return view('clientes.create', compact('regiones', 'ciudades'));
    }


    // Almacena un nuevo cliente

    public function store(Request $request)
    {
        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'apellido_cliente' => 'nullable|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'nro_contacto' => 'required|string|max:20',
            'tipo_cliente' => 'required|in:natural,empresa',
            'id_region' => 'required|exists:regiones,id_region',
            'id_ciudad' => 'required|exists:ciudades,id_ciudad',
            'direccion' => 'required|string|max:255',
            'razon_social' => 'nullable|string|max:255',
            'giro' => 'nullable|string|max:255',
            'rut_natural' => [
                'nullable',
                'required_if:tipo_cliente,natural',
                'string',
                'max:20',
                new RutValido, // <--- AGREGADO
                // Tu closure para evitar duplicados
                function ($attribute, $value, $fail) {
                    if (request('tipo_cliente') === 'natural' && \App\Models\Cliente::where('rut', $value)->exists()) {
                        $fail('El RUT ya está registrado para otro cliente.');
                    }
                },
            ],
            'rut_empresa' => [
                'nullable',
                'required_if:tipo_cliente,empresa',
                'string',
                'max:20',
                new RutValido, // <--- AGREGADO
            ],
        ], [
            'rut_natural.required_if' => 'Debes ingresar el RUT si es persona natural.',
            'rut_empresa.required_if' => 'Debes ingresar el RUT si es empresa.',
        ]);

        $cliente = new Cliente();
        $cliente->nombre_cliente = $request->nombre_cliente;
        $cliente->apellido_cliente = $request->apellido_cliente;
        $cliente->email = $request->email;
        $cliente->nro_contacto = $request->nro_contacto;
        $cliente->tipo_cliente = $request->tipo_cliente;
        $cliente->id_region = $request->id_region;
        $cliente->id_ciudad = $request->id_ciudad;
        $cliente->direccion = $request->direccion;
        $cliente->estado = 'activo';

        if ($request->tipo_cliente === 'empresa') {
            $rutEmpresa = $request->rut_empresa;

            $empresa = Empresa::where('rut_empresa', $rutEmpresa)->first();
            if (!$empresa) {
                if ($request->ajax()) {
                    return response()->json([
                        'errors' => ['rut_empresa' => 'La empresa con ese RUT no está registrada.']
                    ], 422);
                }
                return back()->withErrors(['rut_empresa' => 'La empresa con ese RUT no está registrada.'])->withInput();
            }

            $cliente->id_empresa = $empresa->id_empresa;
            $cliente->razon_social = $empresa->razon_social;
            $cliente->giro = $request->giro;
            $cliente->rut = $empresa->rut_empresa;
        } else {
            $rutNatural = $request->rut_natural;

            if (Empresa::where('rut_empresa', $rutNatural)->exists()) {
                if ($request->ajax()) {
                    return response()->json([
                        'errors' => ['rut_natural' => 'Este RUT ya está registrado como empresa.']
                    ], 422);
                }
                return back()->withErrors(['rut_natural' => 'Este RUT ya está registrado como empresa.'])->withInput();
            }

            $cliente->rut = $rutNatural;
            $cliente->id_empresa = null;
            $cliente->razon_social = null;
            $cliente->giro = null;
        }

        $cliente->save();

        // ✅ Si es AJAX desde el modal, devolver JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cliente' => [
                    'id' => $cliente->id_cliente,
                    'nombre' => $cliente->nombre_cliente . ' ' . $cliente->apellido_cliente,
                    'rut' => $cliente->rut, // <-- Agrega el rut
                ]
            ]);
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado exitosamente.');
    }



    // Muestra los detalles de un cliente específico
    public function show(string $id)
    {
        $cliente = Cliente::with(['empresa', 'region', 'ciudad'])->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    // Muestra el formulario de edición para un cliente específico
    public function edit(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $regiones = Region::all();
        $ciudades = Ciudad::all();
        $empresas = Empresa::where('estado', 'activo')->get();
        return view('clientes.edit', compact('cliente', 'regiones', 'ciudades', 'empresas'));
    }

    // Actualiza un cliente existente
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'apellido_cliente' => 'nullable|string|max:255',
            'email' => 'required|email|unique:clientes,email,' . $cliente->id_cliente . ',id_cliente',
            'nro_contacto' => 'required|string|max:20',
            'tipo_cliente' => 'required|in:natural,empresa',
            'id_region' => 'required|exists:regiones,id_region',
            'id_ciudad' => 'required|exists:ciudades,id_ciudad',
            'direccion' => 'required|string|max:255',
            'razon_social' => 'nullable|string|max:255',
            'giro' => 'nullable|string|max:255',
            // Validación para tipo NATURAL
            'rut_natural' => [
                'nullable',
                'required_if:tipo_cliente,natural',
                'string',
                'max:20',
                new RutValido, // <--- AGREGADO
                function ($attribute, $value, $fail) use ($cliente) {
                    if (
                        request('tipo_cliente') === 'natural' &&
                        \App\Models\Cliente::where('rut', $value)
                            ->where('id_cliente', '!=', $cliente->id_cliente)
                            ->exists()
                    ) {
                        $fail('El RUT ya está registrado para otro cliente.');
                    }
                    // Valida que el rut_natural NO exista como rut_empresa en tabla empresas
                    if (
                        request('tipo_cliente') === 'natural' &&
                        \App\Models\Empresa::where('rut_empresa', $value)->exists()
                    ) {
                        $fail('Este RUT ya está registrado como empresa.');
                    }
                }
            ],
            // Validación para tipo EMPRESA
            'rut_empresa' => [
                'nullable',
                'required_if:tipo_cliente,empresa',
                'string',
                'max:20',
                new RutValido, // <--- AGREGADO
                // Opcional: Valida que la empresa exista en la tabla empresas
                function ($attribute, $value, $fail) {
                    if (
                        request('tipo_cliente') === 'empresa' &&
                        !\App\Models\Empresa::where('rut_empresa', $value)->exists()
                    ) {
                        $fail('La empresa con ese RUT no está registrada.');
                    }
                }
            ],
        ], [
            'rut_natural.required_if' => 'Debes ingresar el RUT si es persona natural.',
            'rut_empresa.required_if' => 'Debes ingresar el RUT si es empresa.',
        ]);

        // Campos comunes
        $cliente->nombre_cliente = $request->nombre_cliente;
        $cliente->apellido_cliente = $request->apellido_cliente;
        $cliente->email = $request->email;
        $cliente->nro_contacto = $request->nro_contacto;
        $cliente->tipo_cliente = $request->tipo_cliente;
        $cliente->id_region = $request->id_region;
        $cliente->id_ciudad = $request->id_ciudad;
        $cliente->direccion = $request->direccion;
        $cliente->giro = $request->giro;

        if ($request->tipo_cliente === 'empresa') {
            $rutEmpresa = $request->rut_empresa;
            $empresa = \App\Models\Empresa::where('rut_empresa', $rutEmpresa)->first();
            $cliente->id_empresa = $empresa->id_empresa;
            $cliente->razon_social = $empresa->razon_social;
            $cliente->rut = $empresa->rut_empresa;
        } else {
            $rutNatural = $request->rut_natural;
            $cliente->rut = $rutNatural;
            $cliente->id_empresa = null;
            $cliente->razon_social = null;
            $cliente->giro = null;
        }
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    // Inhabilita un cliente
    public function destroy($id_cliente)
    {
        $cliente = Cliente::findOrFail($id_cliente);
        $cliente->estado = 'inhabilitado';
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente inhabilitado correctamente.');
    }

    // Reactiva un cliente inhabilitado
    public function reactivar($id_cliente)
    {
        $cliente = Cliente::findOrFail($id_cliente);
        $cliente->estado = 'activo';
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente reactivado correctamente.');
    }

    // Busca clientes por RUT
    public function buscarPorRut(Request $request)
    {
        $request->validate(['rut' => 'required|string|max:20']);

        $cliente = Cliente::where('rut', $request->rut)->first();

        if ($cliente) {
            return response()->json($cliente);
        } else {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    }

    // Busca clientes por nombre
    public function buscarPorNombre(Request $request)
    {
        $request->validate(['nombre_cliente' => 'required|string|max:255']);

        $clientes = Cliente::where('nombre_cliente', 'like', '%' . $request->nombre_cliente . '%')->get();

        if ($clientes->isNotEmpty()) {
            return response()->json($clientes);
        } else {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    }

    // Obtiene las ciudades de una región específica
    public function obtenerCiudades($region_id)
    {
        $ciudades = Ciudad::where('id_region', $region_id)->get();
        return response()->json($ciudades);
    }

    // Obtiene todas las regiones
    public function obtenerRegiones()
    {
        $regiones = Region::all();
        return response()->json($regiones);
    }

    public function getCiudadesPorRegion($id_region)
    {
        $ciudades = Ciudad::where('id_region', $id_region)->get();

        return response()->json($ciudades);
    }
    // API para verificar si el rut_natural existe en clientes
    public function comprobarRut($rut)
    {
        $existe = Cliente::where('rut', $rut)->exists();
        return response()->json(['existe' => $existe]);
    }

}
