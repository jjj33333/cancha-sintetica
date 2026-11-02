<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Partidos - {{ $torneoSeleccionado->nombre }}</title>
    <style>
        @page {
            margin: 30px 34px 52px 34px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 10px;
            color: #1F2937;
            line-height: 1.35;
        }

        /* ---------- Encabezado ---------- */
        .header {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
        }

        .header td {
            vertical-align: top;
            padding: 0;
        }

        .brand {
            font-size: 8px;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            color: #047857;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .title {
            font-size: 21px;
            font-weight: bold;
            color: #065F46;
            margin: 0 0 3px 0;
        }

        .tournament {
            font-size: 11px;
            color: #4B5563;
            font-weight: bold;
        }

        .filter-box {
            text-align: right;
            padding-top: 4px;
        }

        .filter-label {
            font-size: 7.5px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #9CA3AF;
            font-weight: bold;
        }

        .filter-value {
            font-size: 14px;
            font-weight: bold;
            color: #B45309;
            margin-top: 1px;
        }

        .filter-count {
            font-size: 8.5px;
            color: #6B7280;
            margin-top: 2px;
        }

        .rule {
            height: 3px;
            background-color: #F59E0B;
            margin-bottom: 12px;
        }

        /* ---------- Ficha de datos ---------- */
        .meta {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 14px;
        }

        .meta td {
            width: 33.33%;
            padding: 7px 10px;
            background-color: #F3F4F6;
            border-right: 3px solid #FFFFFF;
            vertical-align: top;
        }

        .meta td:last-child {
            border-right: 0;
        }

        .meta-label {
            display: block;
            font-size: 7px;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            color: #9CA3AF;
            font-weight: bold;
            margin-bottom: 1px;
        }

        .meta-value {
            font-size: 9.5px;
            color: #374151;
            font-weight: bold;
        }

        /* ---------- Tabla de partidos ---------- */
        .list {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .list thead th {
            background-color: #065F46;
            color: #FFFFFF;
            font-size: 8px;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            text-align: left;
            padding: 9px 10px;
            border-bottom: 2px solid #047857;
        }

        .list thead th.center {
            text-align: center;
        }

        .list tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #E5E7EB;
            vertical-align: middle;
        }

        .list tbody tr {
            page-break-inside: avoid;
        }

        .list tbody tr.alt td {
            background-color: #F9FAFB;
        }

        .col-num {
            width: 8%;
            text-align: center;
        }

        .col-fecha {
            width: 30%;
        }

        .col-equipo {
            width: 31%;
        }

        .num {
            display: inline-block;
            font-size: 9px;
            font-weight: bold;
            color: #6B7280;
        }

        .fecha {
            font-size: 10px;
            font-weight: bold;
            color: #111827;
        }

        .hora {
            font-size: 9px;
            color: #6B7280;
            margin-top: 1px;
        }

        .equipo {
            font-size: 10px;
            font-weight: bold;
            color: #1F2937;
            word-wrap: break-word;
        }

        .equipo-rol {
            display: block;
            font-size: 7px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #9CA3AF;
            font-weight: normal;
            margin-top: 1px;
        }

        .empty {
            padding: 26px 10px;
            text-align: center;
            color: #6B7280;
            font-size: 10px;
            border: 1px solid #E5E7EB;
        }

        /* ---------- Pie de página ---------- */
        .footer {
            position: fixed;
            bottom: -34px;
            left: 0;
            right: 0;
            border-top: 1px solid #E5E7EB;
            padding-top: 7px;
            font-size: 8px;
            color: #9CA3AF;
        }

        .footer td {
            padding: 0;
        }

        .footer .right {
            text-align: right;
        }

        .footer strong {
            color: #6B7280;
        }
    </style>
</head>
<body>

    {{-- Encabezado principal --}}
    <table class="header">
        <tr>
            <td style="width: 68%;">
                <div class="brand">{{ $contacto?->nombre_negocio ?? 'Cancha Sintética' }}</div>
                <div class="title">Partidos y Resultados</div>
                <div class="tournament">{{ $torneoSeleccionado->nombre }}</div>
            </td>
            <td style="width: 32%;">
                <div class="filter-box">
                    <div class="filter-label">Filtro aplicado</div>
                    <div class="filter-value">{{ $jornadaTexto }}</div>
                    <div class="filter-count">
                        {{ $partidos->count() }} partido{{ $partidos->count() !== 1 ? 's' : '' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="rule"></div>

    {{-- Datos del reporte --}}
    <table class="meta">
        <tr>
            <td>
                <span class="meta-label">Generado el</span>
                <span class="meta-value">{{ now()->format('d/m/Y') }} · {{ now()->format('g:i A') }}</span>
            </td>
            <td>
                <span class="meta-label">Torneo</span>
                <span class="meta-value">{{ $torneoSeleccionado->nombre }}</span>
            </td>
            <td>
                <span class="meta-label">Contacto</span>
                <span class="meta-value">
                    {{ $contacto?->telefono_principal ?? '—' }}
                    @if($contacto?->ciudad)
                        · {{ $contacto->ciudad }}
                    @endif
                </span>
            </td>
        </tr>
    </table>

    {{-- Listado de partidos --}}
    @if($partidos->count() > 0)
        <table class="list">
            <thead>
                <tr>
                    <th class="center col-num">No.</th>
                    <th class="col-fecha">Fecha y Hora</th>
                    <th class="col-equipo">Equipo Local</th>
                    <th class="col-equipo">Equipo Visitante</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partidos as $partido)
                    <tr class="{{ $loop->even ? 'alt' : '' }}">
                        <td class="col-num">
                            <span class="num">{{ $loop->iteration }}</span>
                        </td>
                        <td class="col-fecha">
                            <div class="fecha">{{ $partido->fecha_partido->format('d/m/Y') }}</div>
                            <div class="hora">
                                {{ \Carbon\Carbon::parse($partido->hora_inicio)->format('g:i A') }}
                                @if($partido->hora_fin)
                                    - {{ \Carbon\Carbon::parse($partido->hora_fin)->format('g:i A') }}
                                @else
                                    - {{ \Carbon\Carbon::parse($partido->hora_inicio)->addHours(2)->format('g:i A') }}
                                @endif
                            </div>
                        </td>
                        <td class="col-equipo">
                            <span class="equipo">{{ $partido->equipoLocal->nombre }}</span>
                            <span class="equipo-rol">Local</span>
                        </td>
                        <td class="col-equipo">
                            <span class="equipo">{{ $partido->equipoVisitante->nombre }}</span>
                            <span class="equipo-rol">Visitante</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty">
            No hay partidos programados para el filtro seleccionado.
        </div>
    @endif

    {{-- Pie de página --}}
    <table class="footer">
        <tr>
            <td>
                <strong>{{ $contacto?->nombre_negocio ?? 'Cancha Sintética' }}</strong>
                @if($contacto?->direccion)
                    · {{ $contacto->direccion }}
                @endif
            </td>
            <td class="right">
                Documento generado automáticamente
                @if($contacto?->sitio_web)
                    · {{ $contacto->sitio_web }}
                @endif
            </td>
        </tr>
    </table>

</body>
</html>
