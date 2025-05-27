@extends('pdf.base')

@section('title', "Orden de Trabajo #{$ordenTrabajo->id_ot}")

@section('ot_id', $ordenTrabajo->id_ot)
@section('cliente', $ordenTrabajo->cliente->nombre_cliente)
@section('responsable', $ordenTrabajo->responsable->nombre)
@section('estado', $ordenTrabajo->estadoOT->nombre_estado)
@section('fecha_creacion', \Carbon\Carbon::parse($ordenTrabajo->fecha_creacion)->format('d/m/Y'))
@section('fecha_entrega', $ordenTrabajo->fecha_entrega ? $ordenTrabajo->fecha_entrega->format('d/m/Y') : '—')

@section('content')
    <h2>Descripción</h2>
    @foreach ($ordenTrabajo->detalleOT as $detalle)
        <p>{{ $detalle->descripcion_actividad }}</p>
    @endforeach

    <h2>Tipos de Trabajo</h2>
    <ul>
        @forelse($ordenTrabajo->servicios as $s)
            <li>{{ $s->nombre_servicio }}</li>
        @empty
            <li>No hay tipos de trabajo asignados.</li>
        @endforelse
    </ul>

    <h2>Productos Asociados</h2>
    @if ($ordenTrabajo->detalleProductos->isEmpty())
        <p>No hay productos asociados.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ordenTrabajo->detalleProductos as $d)
                    <tr>
                        <td>{{ $d->producto->marca }} {{ $d->producto->modelo }}</td>
                        <td>{{ $d->cantidad }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Archivos Adjuntos</h2>
    @if ($archivosAdjuntos->isNotEmpty())
        @foreach ($archivosAdjuntos as $archivo)
            <div style="margin-bottom: 10px;">
                <p><strong>{{ $archivo['nombre'] }}</strong></p>

                @if ($archivo['base64'])
                    <img src="{{ $archivo['base64'] }}" style="width: 200px; height: auto;" alt="Archivo Adjunto">
                @else
                    <p style="color: red;">Este archivo no se puede visualizar (no es una imagen válida)</p>
                @endif
            </div>
        @endforeach
    @else
        <p>No hay archivos adjuntos</p>
    @endif

    <h2>Historial de Cambios</h2>
    @if ($historial->isEmpty())
        <p>No hay historial de cambios.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID Hist.</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Campos</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($historial as $h)
                    <tr>
                        <td>{{ $h['id_historial'] }}</td>
                        <td>{{ $h['usuario'] }}</td>
                        <td>{{ $h['fecha_modificacion'] }}</td>
                        <td>
                            <ul>
                                @foreach ($h['campos'] as $campo)
                                    <li>{{ $campo }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <ul>
                                @foreach ($h['descripciones'] as $desc)
                                    <li>{!! $desc !!}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection


<!-- It is not the man who has too little, but the man who craves more, that is poor. - Seneca -->
