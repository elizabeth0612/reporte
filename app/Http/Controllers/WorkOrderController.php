<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceManager;
use App\Models\Supervisor;
use App\Models\Worker;
use App\Models\WorkOrder;
use App\Models\WorkOrderSupervisor;
use App\Models\WorkOrderWorker;
use Illuminate\Support\Facades\DB;
use App\Models\WorkOrderDetailImage;
use App\Models\WorkOrderDetail;
use App\Models\WorkOrderMaintenanceManager;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenanceManagers = MaintenanceManager::all();
        $supervisors = Supervisor::all();
        $workers = Worker::all();
        return view('workorder.index',compact('maintenanceManagers','supervisors','workers'));
    }
    public function getWorkOrder($id)
    {
        $workOrder = WorkOrder::find($id);
        $workOrderWorker = WorkOrderWorker::where('work_order_id', $id)->get();
        $workOrderSupervisor = WorkOrderSupervisor::where('work_order_id', $id)->get();
        $workOrderMaintenanceManager = WorkOrderMaintenanceManager::where('work_order_id', $id)->get();

        return response()->json([
            'workOrder' => $workOrder,
            'workers' => $workOrderWorker,
            'workOrderSupervisors'=> $workOrderSupervisor,
            'workOrderMaintenanceManagers'=> $workOrderMaintenanceManager,
        ]);
    }

    public function updateImage(Request $request){
        try {
            // 1. Actualizar descripciones de imágenes ya guardadas
            if ($request->has('imagenes_existentes')) {
                foreach ($request->input('imagenes_existentes') as $id => $nuevaDescripcion) {
                    \App\Models\WorkOrderDetailImage::where('id', $id)->update([
                        'descripcion' => $nuevaDescripcion,
                    ]);
                }
            }

            // 2. Subir y guardar nuevas imágenes
            if ($request->hasFile('imagenes')) {
                $imagenes = $request->file('imagenes');
                $descripciones = $request->input('descripciones');

                foreach ($imagenes as $index => $imagen) {
                    if ($imagen) {
                        $imageName = time() . '_' . $imagen->getClientOriginalName();
                        $imagen->move(storage_path('app/public/images/work_order_detail'), $imageName);

                        \App\Models\WorkOrderDetailImage::create([
                            'work_order_detail_id' => $request->input('workOrderId'),
                            'image_path' => 'images/work_order_detail/' . $imageName,
                            'descripcion' => $descripciones[$index] ?? null,
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => '¡Ha sido actualizado con éxito!',
            ]);
        } catch (\Exception $e) {
            // Capturar cualquier excepción lanzada durante el proceso
            return response()->json([
                'success' => false,
                'message' => 'Ha ocurrido un error al actualizar las imágenes: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $maintenanceManagers = MaintenanceManager::all();
        $supervisors = Supervisor::all();
        $workers = Worker::all();


        return view('workorder.create',compact('maintenanceManagers','supervisors','workers'));
    }
    public function search(){
        $orders = WorkOrder::all();

        return response()->json($orders);

    }
    public function details($id){
        $workOrderDetail= WorkOrderDetail::where('work_order_id',$id)->get();

        return response()->json($workOrderDetail);

    }
    public function pdf($id){
        $workOrder = WorkOrder::find($id);
        if (!$workOrder) {
            return abort(404, 'order no encontrada');
        }
        $workOrderMaintenanceManager = WorkOrderMaintenanceManager::with('maintenanceManager')
        ->where('work_order_id', $id)
        ->get();

        $workOrderSupervisor = WorkOrderSupervisor::with('supervisor')
            ->where('work_order_id', $id)
            ->get();

        $workOrderWorker = WorkOrderWorker::with('worker')
            ->where('work_order_id', $id)
            ->get();
        $workOrderDetail = WorkOrderDetail::with('images')->where('work_order_id', $id)->get();
        $pdf = Pdf::loadView('workorder.pdf', compact('workOrder','workOrderMaintenanceManager','workOrderSupervisor','workOrderWorker','workOrderDetail'));
        return $pdf->stream('workorder.pdf');
    }
    public function add( Request $request){
        //dd($request);
         // Validación de los datos (si es necesario)
         try{
            $request->validate([
                'work_order_id' => 'nullable|exists:work_orders,id',
                'nro_trabajo' => 'nullable|string',
                'descripcion' => 'nullable|string',
                'material' => 'nullable|string',
                'herramientas' => 'nullable|string',
                'observaciones' => 'nullable|string',
            ]);
         }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
       try{

            // Crear el registro en la tabla `work_order_details`
            $WorkOrderDetail= WorkOrderDetail::create([
                'work_order_id' => $request->input('work_order_id'),
                'nro_trabajo' => $request->input('nro_trabajo'),
                'descripcion' => $request->input('descripcion'),
                'materiales' => $request->input('material'),
                'herramientas' => $request->input('herramientas'),
                'observaciones' => $request->input('observaciones'),
            ]);

        // Si se suben imágenes, guardarlas
        if ($request->hasFile('imagenes')) {
            $imagenes = $request->file('imagenes');
            $descripciones = $request->input('descripciones');

            foreach ($imagenes as $index => $imagen) {
                if ($imagen) {
                    $imageName = time() . '_' . $imagen->getClientOriginalName();
                    $imagen->move(storage_path('app/public/images/work_order_detail'), $imageName);

                    WorkOrderDetailImage::create([
                        'work_order_detail_id' => $WorkOrderDetail->id,
                        'image_path' => 'images/work_order_detail/' . $imageName,
                        'descripcion' => $descripciones[$index] ?? null,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => '¡Ha sido registrado con éxito!',
        ]);

       } catch (\Exception $e) {
        //DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error al registrar el producto',
            'error' => $e->getMessage()
        ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        try{
            $request->validate([
                'order_work' => 'required|string',
                'empresa' => 'nullable|string',
                'descripcion' => 'nullable|string',


                'supervisor_id' => 'nullable|array',
                'supervisor_id.*' => 'exists:supervisors,id',

                'maintenance_manager_id' => 'nullable|array',
                'maintenance_manager_id.*' => 'exists:maintenance_managers,id',

                'workers' => 'nullable|array',
                'workers.*' => 'exists:workers,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
       try{
            //DB::beginTransaction();

            $workOrder = WorkOrder::create([
                'order_work' => $request->order_work,
                'empresa' => $request->empresa,
                'descripcion'=> $request->descripcion,

            ]);
            // Si se sube una imagen, guardarla
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $imageName = time() . '_' . $imagen->getClientOriginalName();

                // Mover la imagen a la carpeta correspondiente
                $imagen->move(storage_path('app/public/images/logo'), $imageName);

                // Actualizar el registro con la imagen
                $workOrder->update([
                    'image_path' => 'images/logo/' . $imageName,
                ]);
            }
            // Jefes de mantenimiento
            if ($request->filled('maintenance_manager_id')) {
                foreach ($request->maintenance_manager_id as $managerId) {
                    $maintenanceManagers = WorkOrderMaintenanceManager::create([
                        'work_order_id' => $workOrder->id,
                        'maintenance_manager_id' => $managerId,
                    ]);
                }
            }
            // Supervisores
            if ($request->filled('supervisor_id')) {
                foreach ($request->supervisor_id as $supervisorId) {
                    $supervisors = WorkOrderSupervisor::create([
                        'work_order_id' => $workOrder->id,
                        'supervisor_id' => $supervisorId,
                    ]);
                }
            }
            // Trabajadores
            if ($request->filled('workers')) {
                foreach ($request->workers as $workerId) {
                    $workers = WorkOrderWorker::create([
                        'work_order_id' => $workOrder->id,
                        'worker_id' => $workerId,
                    ]);
                }
            }


            //DB::commit();
            return response()->json([
                'success' => true,
                'message' => '¡Ha sido registrado con éxito!',
            ]);
       } catch (\Exception $e) {
            //DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el producto',
                'error' => $e->getMessage()
            ], 500);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request,$id);
    $workOrderDetail = WorkOrderDetail::findOrFail($id);

    // Actualiza los campos con los nuevos valores proporcionados en la solicitud
        $workOrderDetail->update([
            'work_order_id' =>  $id,
            'nro_trabajo' => $request->input('nro_trabajo'),
            'descripcion' => $request->input('descripcion'),
            'materiales' => $request->input('materiales'),  // Cambié material a materiales
            'herramientas' => $request->input('herramientas'),
            'observaciones' => $request->input('observaciones'),
        ]);

            // Opción para devolver una respuesta, si lo necesitas
            return response()->json([
                'success' => true,
                'message' => '¡Ha sido actualizado con éxito!',
            ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $workOrderDetail = WorkOrder::findOrFail($id);
        $workOrderDetail->delete();

        return response()->json([
            'message' => 'Orden de trabajo eliminado con éxito.',
        ], 200);
    }
    public function destroyWork(string $id)
    {
        $workOrderDetail = WorkOrderDetail::findOrFail($id);
        $workOrderDetail->delete();

        return response()->json([
            'message' => 'Detalle de orden de trabajo eliminado con éxito.',
        ], 200);
    }
    public function destroyImage($id){
        $workOrderDetailImage = WorkOrderDetailImage::findOrFail($id);
        $workOrderDetailImage->delete();

        return response()->json([
            'message' => 'Se elimino la imagen.',
        ], 200);
    }
    public function images($id){
            // Obtén las imágenes asociadas con el id (aquí asumo que tienes una relación con 'images')
            $workOrderDetail = WorkOrderDetailImage::where('work_order_detail_id',$id)->get();
            $images = $workOrderDetail;  // Asegúrate de tener la relación configurada en el modelo

            return response()->json($images);  // Devuelves las imágenes como JSON
    }
}
