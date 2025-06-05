<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parte de Trabajo Diario</title>
    <style>
        /* ... (Tu CSS actual, sin cambios necesarios aquí) ... */
        body {
            font-size: 12px;
            padding: 24px;
            font-family: sans-serif;
            font-size: 10px;
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
            font-size: 9px;
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

    {{-- Encabezado principal --}}
    <table class="encabezado">
        <thead>
            <tr>
                <th colspan="6">PARTE DE TRABAJO DIARIO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold;width:25%;">EMPRESA</td>
                <td style="width:55%;">{{ $workOrder->empresa }}</td>
                <td rowspan="5" colspan="4" class="logo" style="width:25%;">
                    {{-- Esta imagen del logo normalmente es estática y puede ir directa --}}
                    <img src="{{ public_path('storage/' . $workOrder->image_path) }}" alt="Logo">
                    <div>{{ $workOrder->descripcion }}</div>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">MES</td>
                <td >{{ $workOrder->mes_work }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">RESPONSABLE</td>
                <td >
                    @foreach($workOrderSupervisor as $index => $supervisor)
                        - {{ $supervisor->supervisor->name }} {{ $supervisor->supervisor->paternal_surname }} {{ $supervisor->supervisor->maternal_surname }}<br>
                    @endforeach
                </td>
            </tr>

            <tr>
                <td style="font-weight: bold;">JEFE DE MANTENIMIENTO</td>
                <td >
                @foreach($workOrderMaintenanceManager as $manager)
                    - {{ $manager->maintenanceManager->name }} {{ $manager->maintenanceManager->paternal_surname }} {{ $manager->maintenanceManager->maternal_surname }}<br>
                @endforeach
                </td>

            </tr>
            <tr>
                <td>OPERARIOS</td>
                <td >
                    @foreach($workOrderWorker as $worker)
                        - {{ $worker->worker->name }} {{ $worker->worker->paternal_surname }} {{ $worker->worker->maternal_surname }}<br>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Tabla de trabajos --}}
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
                    {{--  <th>HORAS</th>  --}}
                    <th>FECHAS</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @if (isset($workOrderDetail) && is_iterable($workOrderDetail) && $workOrderDetail->isNotEmpty())
                @foreach ($workOrderDetail as $work)
                    <tr>
                        <td>{{ $work->nro_trabajo ?? 'Sin número' }}</td>
                        <td>{{ $work->descripcion ?? 'Sin descripción' }}</td>
                        <td class="pre-line">{{ $work->materiales ?? 'Sin materiales' }}</td>
                        <td class="pre-line">{{ $work->herramientas ?? 'Sin herramientas' }}</td>
                        {{--  <td class="pre-line"> {{ count($workOrderWorker) * 8 }}</td>  --}}
                        <td>{{ $work->fechas ?? 'Sin fecha' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No se registró ningún trabajo realizado.</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{-- Tabla de trabajos QUIEN REGISTRO --}}
    <table>
        <thead>
            @if (isset($workOrderDetail) && is_iterable($workOrderDetail) && $workOrderDetail->isNotEmpty())
                <tr>
                    <th colspan="2">DESCRIPCIÓN DE LOS TRABAJOS</th>
                </tr>
                <tr class="subencabezado">
                    <th>N.º TRABAJO</th>
                    <th>OFICIAL</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @if (isset($workOrderDetail) && is_iterable($workOrderDetail) && $workOrderDetail->isNotEmpty())
                @foreach ($workOrderDetail as $work)
                    <tr>
                        <td>{{ $work->nro_trabajo ?? 'Sin número' }}</td>
                        {{--  <td>{{ $work->user->name ?? 'Sin usuario registrado'}}</td>  --}}
                        <td>OFICIAL</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No se registró ningún trabajo realizado.</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Agrupación por semana --}}
    @php $trabajosPorSemana = []; @endphp
    @if (isset($workOrderDetail) && is_iterable($workOrderDetail))
        @foreach ($workOrderDetail as $work)
            @php
                $fechasTexto = $work->fechas ?? '';
                $fechas = preg_split('/\s+/', trim(str_replace(["\r\n", "\n", "\r"], ' ', $fechasTexto)));
            @endphp
            @foreach ($fechas as $fecha)
                @if (!empty($fecha))
                    @php
                        try {
                            $carbonFecha = \Carbon\Carbon::createFromFormat('Y-m-d', $fecha, 'America/Lima');
                            $semanaDelAno = $carbonFecha->weekOfYear;
                            $trabajosPorSemana[$semanaDelAno][] = [
                                'nro_trabajo' => $work->nro_trabajo ?? 'Sin número',
                                'fecha' => $fecha,
                                'descripcion' => $work->descripcion ?? 'Sin descripción',
                            ];
                        } catch (Exception $e) {}
                    @endphp
                @endif
            @endforeach
        @endforeach
    @endif

    @foreach ($trabajosPorSemana as $semana => $trabajos)
    <table>
        <thead>
            <tr>
                <th colspan="2">Semana {{ $semana }}</th>
            </tr>
            <tr class="subencabezado">
                <th>DÍA</th>
                <th>FECHA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trabajos as $trabajo)
                @php
                    $diaDeLaSemana = \Carbon\Carbon::createFromFormat('Y-m-d', $trabajo['fecha'])->locale('es')->isoFormat('dddd');
                @endphp
                <tr>
                    <td>{{ ucfirst($diaDeLaSemana) }}</td>
                    <td>{{ $trabajo['fecha'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach

    {{-- Tabla de Imágenes --}}
   @if (isset($workOrderDetail) && is_iterable($workOrderDetail))
    @php
        $imagenesAgrupadas = [];
        foreach ($workOrderDetail as $detail) {
            if ($detail->images && $detail->images->isNotEmpty()) {
                $imagenesAgrupadas[$detail->id] = $detail->images->map(function ($image) {
                    // ¡IMPORTANTE! Ahora usamos la propiedad original `image_path`
                    return [
                        'descripcion' => $image->descripcion ?? 'Sin descripción',
                        'path_original_imagen' => $image->image_path ?? null, // <--- CAMBIO AQUÍ
                    ];
                })->toArray();
            }
        }
    @endphp

        {{-- Tabla de Imágenes Paginada y con Estilo --}}
        @if (isset($imagenesAgrupadas) && is_iterable($imagenesAgrupadas))
            @foreach ($imagenesAgrupadas as $detailId => $imagenes)
                @php
                    $workDetail = \App\Models\WorkOrderDetail::find($detailId);
                    $nroTrabajo = $workDetail->nro_trabajo ?? 'Sin número';
                @endphp
                <div style="margin-top: 50px; border: 1px solid #000000;">
                    <h3 style="font-size: 14px; background-color: #bfdbfe; margin-top: 0;border-bottom: 1px solid #000000;text-align: center">Imágenes del Trabajo N.º {{ $nroTrabajo }}</h3>
                    @foreach (array_chunk($imagenes, 6) as $paginaImagenes)
                        <table width="100%" cellspacing="10" cellpadding="0" style="margin-bottom: 15px;">
                            <tbody>
                                @foreach (array_chunk($paginaImagenes, 3) as $fila)
                                <tr style="width: 100%;">
                                    @foreach ($fila as $image)
                                        <td align="center" style="text-align: center; border: none; width: 33%;">
                                            @if($image['path_original_imagen'])
                                                {{-- Descripción centrada arriba de la imagen --}}
                                                <div style="text-align: center; margin-bottom: 5px;">
                                                    <small style="color: #777; display: block;">{{ $image['descripcion'] }}</small>
                                                </div>
                                                {{-- La imagen --}}
                                                <img src="{{ str_replace('\\', '/', public_path('storage/' . $image['path_original_imagen'])) }}" style="max-width: 100%; height: auto; border: 1px solid #eee; padding: 5px; border-radius: 4px;">
                                            @else
                                                {{-- Si no hay imagen, la descripción (o un mensaje) sigue centrada --}}
                                                <div style="text-align: center; margin-bottom: 5px;">
                                                    <small style="color: #777; display: block;">{{ $image['descripcion'] ?? 'Sin descripción' }}</small>
                                                </div>
                                                <span>No hay imagen</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    @if(count($fila) < 3)
                                        @for ($i = count($fila); $i < 3; $i++)
                                            <td style="border: none; width: 33%;"></td> {{-- Rellenar celdas vacías --}}
                                        @endfor
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
                <div style="page-break-after: always;"></div> {{-- Salto de página después de cada grupo de imágenes de un trabajo --}}
            @endforeach
        @endif
    @endif

</body>
</html>
