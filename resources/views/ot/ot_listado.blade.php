@extends('pdf.listado_base')

@section('title', 'Listado de Órdenes de Trabajo')

@section('content')
    @if ($ordenes->isEmpty())
        <p>No hay órdenes de trabajo que coincidan con los filtros aplicados.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Responsable</th>
                    <th>Estado</th>
                    <th>Creación</th>
                    <th>Entrega Estimada</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ordenes as $ot)
                    <tr>
                        <td>{{ $ot->id_ot }}</td>
                        <td>{{ $ot->cliente?->nombre_cliente ?? 'Sin cliente' }}</td>
                        <td>{{ $ot->responsable?->nombre ?? 'Sin responsable' }}</td>
                        <td>{{ $ot->estadoOT?->nombre_estado ?? 'Sin estado' }}</td>
                        <td>{{ \Carbon\Carbon::parse($ot->fecha_creacion)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($ot->fecha_entrega)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection


<!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
