<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parte de Trabajo Diario</title>
    <style>
        body {
            font-size: 12px;
            padding: 24px;
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        th, td {
            border: 1px solid black;
            padding: 4px 8px;
            vertical-align: top;
        }
        th {
            background-color: #bfdbfe; /* azul claro */
            font-weight: bold;
            text-align: center;
        }
        .encabezado th {
            font-size: 12px;
        }
        .subencabezado th {
            background-color: #f3f4f6; /* gris claro */
            font-size: 12px;
        }
        .logo {
            text-align: center;
            vertical-align: middle;
        }
        .logo img {
            height: 80px;
            margin-bottom: 4px;
            object-fit: contain;
        }
        .logo div {
            font-size: 12px;
            font-weight: 600;
        }
        .pre-line {
            white-space: pre-line;
        }
    </style>
</head>
<body>
    <table class="encabezado">
        <thead>
            <tr>
                <th colspan="6">PARTE DE TRABAJO DIARIO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold;">EMPRESA</td>
                <td colspan="2">{{$workOrder->empresa}}</td>
                <td colspan="3" rowspan="5" class="logo">
                    <img src="{{ public_path('storage/' . $workOrder->image_path) }}" alt="Logo">
                    <div>{{$workOrder->descripcion}}</div>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">ORDEN DE TRABAJO</td>
                <td colspan="2">{{$workOrder->order_work}}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">RESPONSABLE</td>
                @foreach($workOrderSupervisor as $supervisor)
                    <td>{{ $supervisor->supervisor->name }} {{ $supervisor->supervisor->paternal_surname }} {{ $supervisor->supervisor->maternal_surname }}</td>
                @endforeach
                @for($i = count($workOrderSupervisor); $i < 2; $i++)
                    <td></td>
                @endfor
            </tr>
            <tr>
                <td style="font-weight: bold;">JEFE DE MANTENIMIENTO</td>
                @foreach($workOrderMaintenanceManager as $manager)
                    <td>{{ $manager->maintenanceManager->name }} {{ $manager->maintenanceManager->paternal_surname }} {{ $manager->maintenanceManager->maternal_surname }}</td>
                @endforeach
                @for($i = count($workOrderMaintenanceManager); $i < 2; $i++)
                    <td></td>
                @endfor
            </tr>
            <tr>
                <td style="font-weight: bold;">OPERARIOS</td>
                @foreach($workOrderWorker as $worker)
                    <td>{{ $worker->worker->name }} {{ $worker->worker->paternal_surname }} {{ $worker->worker->maternal_surname }}</td>
                @endforeach
                @for($i = count($workOrderWorker); $i < 2; $i++)
                    <td></td>

                @endfor
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            @if (isset($workOrderDetail) && is_iterable($workOrderDetail) && $workOrderDetail->isNotEmpty())
                <tr>
                    <th colspan="5">DESCRIPCIÓN DE LOS TRABAJOS</th>
                </tr>
                <tr class="subencabezado">
                    <th>N.º TRABAJO</th>
                    <th>DESCRIPCIÓN</th>
                    <th>MATERIAL EMPLEADO</th>
                    <th>HERRAMIENTAS</th>
                    <th>OBSERVACIONES</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @if (isset($workOrderDetail) && is_iterable($workOrderDetail) && $workOrderDetail->isNotEmpty())
                @php
                    $rowCount = 0;
                @endphp

                @foreach ($workOrderDetail as $index => $work)
                    <tr>
                        <td>{{ $work->nro_trabajo ?? 'Sin nombre' }}</td>
                        <td>{{ $work->descripcion ?? 'Sin ubicación' }}</td>
                        <td class="pre-line">{{$work->materiales ?? 'Sin nombre' }}</td>
                        <td class="pre-line">{{$work->herramientas ?? 'Sin nombre' }}</td>
                        <td>FECHA: {{ $work->observaciones ?? 'Sin nombre' }} </td>
                    </tr>
                    @php $rowCount++; @endphp
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No se registró ningún trabajo realizado.</td>
                </tr>
            @endif
        </tbody>
    </table>


    @php
        $trabajosPorSemana = [];
    @endphp

    @if (isset($workOrderDetail) && is_iterable($workOrderDetail))
        @foreach ($workOrderDetail as $work)
            @php
                $fechasTexto = $work->observaciones ?? '';
                // Convertimos saltos de línea a espacio y luego explotamos
                $fechas = preg_split('/\s+/', trim(str_replace(["\r\n", "\n", "\r"], ' ', $fechasTexto)));
            @endphp

            @foreach ($fechas as $fecha)
                @if (!empty($fecha))
                    @php
                        // Usamos Carbon para analizar la fecha
                        $carbonFecha = \Carbon\Carbon::createFromFormat('d/m/Y', $fecha, 'America/Lima');
                        $semanaDelAno = $carbonFecha->weekOfYear;
                    @endphp
                    @php
                        // Agrupar los trabajos por semana
                        $trabajosPorSemana[$semanaDelAno][] = [
                            'nro_trabajo' => $work->nro_trabajo ?? 'Sin número',
                            'fecha' => $fecha,
                            'descripcion' => $work->descripcion ?? 'Sin descripción',
                        ];
                    @endphp
                @endif
            @endforeach
        @endforeach
    @endif

    @foreach ($trabajosPorSemana as $semana => $trabajos)
    <table>
        <thead>
            <tr>
                <th colspan="3">Semana {{ $semana }}</th>
            </tr>
            <tr class="subencabezado">
                <th>DÍA</th> <!-- Cambiado de "N.º TRABAJO" a "DÍA" -->
                <th>FECHA</th>
                <th>OFICIAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trabajos as $trabajo)
                @php
                    // Establecemos el idioma a español
                    $diaDeLaSemana = \Carbon\Carbon::createFromFormat('d/m/Y', $trabajo['fecha'])->locale('es')->isoFormat('dddd');
                @endphp
                <tr>
                    <td>{{ $diaDeLaSemana }}</td> <!-- Mostramos el día en español -->
                    <td>{{ $trabajo['fecha'] }}</td>
                    <td>OFICIAL </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
{{-- Tabla de Imágenes (separada) --}}
@if (isset($workOrderDetail) && is_iterable($workOrderDetail))
@php
// Agrupamos las imágenes por nro_trabajo
$imagenesAgrupadas = [];

foreach ($workOrderDetail as $work) {
    if ($work->images && $work->images->isNotEmpty()) {
        foreach ($work->images as $image) {
            $imagenesAgrupadas[$work->nro_trabajo ?? 'Sin nombre'][] = [
                'descripcion' => $image->descripcion ?? 'Sin descripción',
                'ruta' => public_path('storage/' . $image->image_path),
            ];
        }
    }
}
@endphp

@foreach ($imagenesAgrupadas as $nro_trabajo => $imagenes)
<table>
    <thead>
        <tr>
            <th colspan="2">IMÁGENES DEL TRABAJO N.º {{ $nro_trabajo }}</th>
        </tr>
        <tr class="subencabezado">
            <th>Imagen</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($imagenes as $image)
            <tr>
                <td>
                    @if (file_exists($image['ruta']))
                        <img src="{{ $image['ruta'] }}" alt="Imagen de trabajo" width="200">
                    @else
                        No hay imagen subida.
                    @endif
                </td>
                <td>{{ $image['descripcion'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
@endforeach

@endif





</body>
</html>
