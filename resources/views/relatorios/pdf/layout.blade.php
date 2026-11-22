<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Relatório')</title>
    <style>
        @page {
            margin: 26px 28px 34px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #111;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
        }

        .report-header {
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #111;
        }

        .report-header table,
        .footer table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-header td {
            vertical-align: bottom;
        }

        .brand {
            color: #000;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .brand .m-blue-light {
            color: #0066b1;
        }

        .brand .m-blue-dark {
            color: #1c69d4;
        }

        .brand .m-red {
            color: #e22718;
        }

        .meta {
            color: #666;
            font-size: 9px;
            line-height: 1.5;
            text-align: right;
        }

        h1 {
            margin: 0 0 4px;
            color: #000;
            font-size: 16px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .subtitle {
            margin-bottom: 14px;
            color: #555;
            font-size: 10px;
        }

        h2 {
            margin: 16px 0 6px;
            color: #000;
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .summary {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }

        .summary td {
            padding: 6px 8px;
            background: #f7f7f7;
            border: 1px solid #ccc;
        }

        .summary .label {
            color: #555;
            font-size: 8px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .summary .value {
            color: #000;
            font-size: 14px;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            margin-top: 8px;
            border-collapse: collapse;
        }

        table.data th {
            padding: 6px 7px;
            background: #111;
            color: #fff;
            font-size: 8px;
            letter-spacing: 1px;
            text-align: left;
            text-transform: uppercase;
        }

        table.data td {
            padding: 5px 7px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        table.data tr:nth-child(even) td {
            background: #f7f7f7;
        }

        table.data tfoot td {
            background: #eee;
            border-top: 2px solid #111;
            font-weight: bold;
        }

        table.fields {
            width: 100%;
            border-collapse: collapse;
        }

        table.fields th {
            width: 18%;
            padding: 5px 7px;
            border-bottom: 1px solid #ddd;
            color: #555;
            font-size: 8px;
            letter-spacing: 1px;
            text-align: left;
            text-transform: uppercase;
        }

        table.fields td {
            padding: 5px 7px;
            border-bottom: 1px solid #ddd;
        }

        .empty {
            padding: 16px;
            border: 1px solid #ccc;
            color: #666;
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            color: #fff;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-disponivel,
        .badge-baixa {
            background: #0fa336;
        }

        .badge-em_revisao,
        .badge-media {
            background: #b88600;
        }

        .badge-danificado,
        .badge-critica,
        .badge-alta {
            background: #c02015;
        }

        .badge-em_manutencao {
            background: #1c69d4;
        }

        .badge-indisponivel {
            background: #555;
        }

        .muted {
            color: #666;
        }

        .footer {
            margin-top: 18px;
            padding-top: 5px;
            border-top: 1px solid #ccc;
            color: #777;
            font-size: 8px;
        }

        .footer .right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <table>
            <tr>
                <td>
                    <div class="brand">
                        <span class="m-blue-light">C</span><span class="m-blue-dark">A</span><span class="m-red">R</span>TRIX
                    </div>
                </td>
                <td class="meta">
                    Gerado em {{ $generatedAt->format('d/m/Y H:i') }}<br>
                    Pi Cartrix · Gestão de Frota
                </td>
            </tr>
        </table>
    </div>

    <h1>@yield('heading')</h1>
    <div class="subtitle">@yield('subtitle')</div>

    @yield('content')

    <div class="footer">
        <table>
            <tr>
                <td>Pi Cartrix · Relatório gerado pelo sistema</td>
                <td class="right">{{ $generatedAt->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
