<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .logo {
            width: 150px;
        }

        .titulo {
            font-size: 16px;
            font-weight: bold;
            text-align: right;
        }

        footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <header>
        @isset($base64Logo)
            <img src="{{ $base64Logo }}" class="logo">
        @endisset
        <div class="titulo">@yield('title')</div>
    </header>

    <div>
        @yield('content')
    </div>

    <footer>
        Reporte generado el {{ now()->format('d/m/Y H:i') }}
    </footer>

    {{-- Script de numeración de página para dompdf --}}
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $font = $fontMetrics->getFont("Helvetica", "normal");
            $size = 10;
            $x = 500; // posición horizontal (ajustar si lo querés más a la derecha o izquierda)
            $y = 830; // posición vertical (ajustar según tamaño hoja, usualmente A4)
            $pdf->page_text($x, $y, $text, $font, $size, [0,0,0]);
        }
    </script>
</body>

</html>


<!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
