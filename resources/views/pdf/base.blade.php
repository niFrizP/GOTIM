<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Orden de Trabajo PDF')</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #2c3e50;
            margin: 0 30px;
        }

        h1,
        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f0f4f8;
            font-weight: 600;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 8px;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #999;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 6px;
        }

        ul {
            padding-left: 18px;
            margin: 0;
        }

        ul li {
            margin-bottom: 4px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ $base64Logo }}" alt="Logo Plataforma" style="width: 110px; margin-bottom: 5px;">
        <h1>Orden de Trabajo #@yield('ot_id')</h1>
        <p><strong>Cliente:</strong> @yield('cliente')</p>
        <p><strong>Responsable:</strong> @yield('responsable')</p>
        <p><strong>Estado:</strong> @yield('estado')</p>
        <p><strong>Creación:</strong> @yield('fecha_creacion')</p>
        <p><strong>Entrega Estimada:</strong> @yield('fecha_entrega')</p>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <div class="footer">
        Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>
