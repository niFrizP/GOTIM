<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Órdenes de Trabajo</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #2c3e50;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 120px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ $base64Logo }}" class="logo" alt="Logo">
        <h2>Listado de Órdenes de Trabajo</h2>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Responsable</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Entrega</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ordenes as $ot)
                <tr>
                    <td>{{ $ot->id_ot }}</td>
                    <td>{{ $ot->cliente->nombre_cliente }}</td>
                    <td>{{ $ot->responsable->nombre }}</td>
                    <td>{{ $ot->estadoOT->nombre_estado }}</td>
                    <td>{{ \Carbon\Carbon::parse($ot->fecha_creacion)->format('d/m/Y') }}</td>
                    <td>{{ $ot->fecha_entrega ? \Carbon\Carbon::parse($ot->fecha_entrega)->format('d/m/Y') : '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay órdenes de trabajo registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>

<!-- Very little is needed to make a happy life. - Marcus Aurelius -->
