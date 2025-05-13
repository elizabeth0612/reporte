<style>
    .max-h-40 {
    max-height: 40rem !important;
}
</style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        </h2>
    </x-slot>
    <div class="max-w-6xl  mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
            <h2 class="text-2xl font-semibold text-gray-800">Registro de tareas</h2>

            <form class="flex flex-col sm:flex-row sm:items-center text-xs gap-2" id="formBuscarPorFecha">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <label for="fecha_desde">Desde:</label>
                    <input type="date" name="fecha_desde" id="fecha_desde"
                        class="border border-gray-300 rounded-lg py-2 px-4 w-full sm:w-auto">
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <label for="fecha_hasta">Hasta:</label>
                    <input type="date" name="fecha_hasta" id="fecha_hasta"
                        class="border border-gray-300 rounded-lg py-2 px-4 w-full sm:w-auto">
                </div>

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg w-full sm:w-auto">
                    Buscar
                </button>
                <a href="{{ route('workorders.create') }}"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center transition-all duration-300 w-full md:w-auto">
                    Agregar
                </a>
            </form>


        </div>

        <!-- Mensajes de éxito o error -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif
        <!-- Tabla de registros -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-5 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 ">
                <thead class="bg-gray-50">
                    <tr>

                        <th class="px-3 py-1 text-left text-xs  text-gray-500 uppercase tracking-wider">
                            ORDEN DE TRABAJO

                        </th>
                        <th class="px-3 py-1 text-left text-xs  text-gray-500 uppercase tracking-wider">
                            Empresa
                        </th>
                        <th class="px-3 py-1 text-left text-xs  text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="tbody">
                </tbody>
            </table>
        </div>
        <!-- Mostrar los enlaces de paginación -->
        {{-- @if ($registros instanceof \Illuminate\Pagination\LengthAwarePaginator && $registros->count() > 0)
            {{ $registros->links() }}
        @endif --}}
    </div>
    <!-- Modal -->

    <!-- Modal fondo oscuro -->
    <div id="ModalAdd" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <!-- Contenido del modal -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-4 overflow-x-auto overflow-y-auto max-h-40">

            <!-- Header -->
            <div class="flex justify-between items-center border-b px-6 py-4 sticky top-0 bg-white">
                <h2 class="text-xl font-semibold">Agregar Nuevo Trabajo</h2>
                <button onclick="closeModal('ModalAdd')" class="text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            </div>

            <!-- Formulario -->
            <form id="formAddTrabajo" enctype="multipart/form-data">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <input type="hidden" id="work_order_id" name="work_order_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>

                    <!-- Nro Trabajo -->
                    <div>
                        <label for="nro_trabajo" class="block text-sm  text-gray-700">Nro Trabajo</label>
                        <input type="text" id="nro_trabajo" name="nro_trabajo" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="description" class="block text-sm  text-gray-700">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <!-- Material Empleado -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="materiales" class="block text-sm  text-gray-700">Material Empleado</label>
                            <textarea id="materiales" name="material" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>

                        <!-- Herramientas -->
                        <div>
                            <label for="herramientas" class="block text-sm  text-gray-700">Herramientas</label>
                            <textarea id="herramientas" name="herramientas" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                    </div>

                    <!-- Observaciones -->
                   <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fechas</label>

                        <div id="fechasContainer" class="space-y-2">
                            <input type="date" name="fechas[]" class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                        </div>

                        <button type="button" id="agregarFecha" class="mt-3 inline-flex items-center px-3 py-2 border border-indigo-500 text-sm font-medium rounded-md text-indigo-500 hover:bg-indigo-50 transition">
                            + Agregar otra fecha
                        </button>
                        </div>

                    <!-- Imágenes -->
                    <div class="imagen-bloque">
                         <label for="" class="block font-medium mb-1">Solo se aceptan imagenes con extension .jpg .jpeg .png</label>
                            <br>
                        <input type="file" name="imagenes[]"  accept=".jpg, .jpeg, .png" onchange="handleFileSelect(event)" multiple>
                      </div>

                    <!-- Vista previa de imágenes -->
                    <div id="previewImagenes" class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4"></div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end space-x-2 px-6 py-4 border-t sticky bottom-0 bg-white">
                    <button type="button" onclick="closeModal('ModalAdd')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cerrar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    <div id="ModalDetails" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl mx-4 overflow-y-auto max-h-[90vh]">

            <!-- Header -->
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h2 class="text-xl font-semibold">Listado de Trabajos</h2>
                <button onclick="closeModal('ModalDetails')" class="text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            </div>

            <!-- Tabla de trabajos -->
            <div class="px-6 py-4">
                <div class="overflow-x-auto overflow-y-auto max-h-96"> <!-- Ajusta el tamaño máximo según sea necesario -->
                    <table class=" text-sm text-left text-gray-700 border border-gray-300" style="width: 100%;">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 border">#</th>
                                <th class="px-3 py-2 border">Nro Trabajo</th>
                                <th class="px-3 py-2 border">Descripción</th>
                                <th class="px-3 py-2 border">Material</th>
                                <th class="px-3 py-2 border">Herramientas</th>
                                <th class="px-3 py-2 border">Observaciones</th>
                                <th class="px-3 py-2 border">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyTrabajos">
                            <!-- Aquí se agregarán las filas con JS -->
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Footer -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t">
                <button type="button" onclick="closeModal('ModalDetails')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cerrar</button>
            </div>
        </div>
    </div>
    <div id="ModalEdit" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-5">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl mx-4 overflow-y-auto max-h-[90vh] p-5">
            <!-- Header -->
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h2 class="text-xl font-semibold">Editar Orden de Trabajo</h2>
                <button onclick="closeModal('ModalEdit')" class="text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            </div>
            <form action="{{ route('workorders.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700" id="formOrder">
                @csrf
                <input type="hidden" id="work_order_id_update">
                <div class="md:col-span-2">
                    <label for="empresa" class="block font-medium mb-1">Empresa</label>
                    <textarea id="empresa" name="empresa" rows="2"
                              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 resize-none">{{ old('empresa') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="order_work" class="block font-medium mb-1">Orden de Trabajo</label>
                    <textarea id="order_work" name="order_work" rows="2"
                              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 resize-none">{{ old('order_work') }}</textarea>
                </div>
                <div>
                    <label for="supervisor_id" class="block font-medium mb-1">Responsable</label>
                    <select id="supervisor_id" name="supervisor_id[]"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400" multiple>
                        <option value="">-- Seleccionar --</option>
                        <!-- Aquí agregas las opciones, por ejemplo, usando un bucle de Laravel -->
                        @foreach ($supervisors as $supervisor)
                            <option value="{{ $supervisor->id }}">{{ $supervisor->name }} {{ $supervisor->paternal_surname }} {{ $supervisor->maternal_surname }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="maintenance_manager_id" class="block font-medium mb-1">Jefe de mantenimiento</label>
                    <select id="maintenance_manager_id" name="maintenance_manager_id[]"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400" multiple>
                        <option value="">-- Seleccionar --</option>
                        <!-- Aquí agregas las opciones, por ejemplo, usando un bucle de Laravel -->
                        @foreach ($maintenanceManagers as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->name }} {{ $manager->paternal_surname }} {{ $manager->maternal_surname }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="workers" class="block font-medium mb-1">Operarios</label>
                    <select id="workers" name="workers[]"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400" multiple>
                        <option value="">-- Seleccionar --</option>
                        <!-- Aquí agregas las opciones, por ejemplo, usando un bucle de Laravel -->
                        @foreach ($workers as $worker)
                            <option value="{{ $worker->id }}">{{ $worker->name }} {{ $worker->paternal_surname }} {{ $worker->maternal_surname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="imagen-bloque">
                    <input type="file" name="imagen" accept="image/*" onchange="handleFileSelect(event)">
                </div>

                <!-- Vista previa de la imagen -->
                <div id="previewImagen" class="mt-4"></div>
                <div class="md:col-span-2">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition-all duration-300">
                        💾 Guardar
                    </button>
                </div>
            </form>
            <!-- Footer -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t">
                <button type="button" onclick="closeModal('ModalEdit')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cerrar</button>
            </div>
        </div>
    </div>
    <!-- Modal IMAGENES-->
<div id="modalImages" class="fixed inset-0 z-50 hidden bg-gray-500 bg-opacity-75 flex justify-center items-center">

    <div class="bg-white rounded-lg shadow-lg  p-6">
        <div class="flex justify-between items-center border-b  sticky top-0 bg-white">
            <h2 class="text-xl font-semibold">Imagenes del Trabajo</h2>
            <button onclick="closeModal('modalImages')" class="text-gray-500 hover:text-red-600 text-2xl">&times;</button>
        </div>
        <form id="formWorkOrderImages" class="space-y-4">
            @csrf
            <!-- ID del Trabajo -->
            <div class="form-group">
                <input type="hidden" id="workOrderId" name="workOrderId" readonly
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div class="imagen-bloque">
                <input type="file" name="imagenes[]" accept="image/*" onchange="addImageWork(event)" multiple>
              </div>
            <!-- Imágenes Asociadas -->
            <div class="form-group">
                <label for="images" class="block text-sm font-medium text-gray-700">Imágenes Asociadas</label>
                <div id="imageContainer" class="mt-2 grid grid-cols-3 gap-4">
                    <!-- Las imágenes se agregarán aquí dinámicamente -->
                </div>
            </div>

            <!-- Botón para guardar cambios -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t sticky bottom-0 bg-white">
                <button type="submit" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                    Guardar Cambios
                </button>
                <button type="button" onclick="closeModal('modalImages')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cerrar</button>

            </div>
        </form>
        <!-- Cerrar el modal -->
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
         var swiper = new Swiper('.swiper-container', {
        slidesPerView: 'auto',
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
     // agregar mas input fechas
    document.getElementById('agregarFecha').addEventListener('click', function() {
        const container = document.getElementById('fechasContainer');
        const input = document.createElement('input');
        input.type = 'date';
        input.name = 'fechas[]';
        input.className = 'block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2';
        container.appendChild(input);
    });
         var swiper = new Swiper('.swiper-container', {
        slidesPerView: 'auto',
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
    async function addImage(id) {
    document.getElementById("modalImages").classList.remove("hidden");

    try {
        let url = `{{ route('workorder.images', ':id') }}`.replace(':id', id);
        let response = await fetch(url);
        let images = await response.json();

        document.getElementById("workOrderId").value = id;
        const imageContainer = document.getElementById("imageContainer");
        imageContainer.innerHTML = "";

        if (images.length === 0) {
            imageContainer.innerHTML = "<p class='text-gray-500 col-span-3'>No hay imágenes disponibles.</p>";
            return;
        }

        images.forEach(image => {
            const wrapper = document.createElement("div");
            wrapper.className = "flex flex-col space-y-2 relative";
            wrapper.setAttribute("data-image-id", image.id);

            const imgElement = document.createElement("img");
            imgElement.src = `/storage/${image.image_path}`;
            imgElement.alt = `Imagen de trabajo ${id}`;
            imgElement.className = "w-full h-20 object-cover rounded shadow";

            const textarea = document.createElement("textarea");
            textarea.name = `imagenes_existentes[${image.id}]`;
            textarea.rows = 2;
            textarea.placeholder = "Escribe una descripción...";
            textarea.className = "w-full border rounded p-1 text-sm resize-none";
            textarea.value = image.descripcion || "";

            const deleteBtn = document.createElement("button");
            deleteBtn.innerHTML = "X";
            deleteBtn.title = "Eliminar imagen";
            deleteBtn.type = "button";
            deleteBtn.className = "absolute top-0 right-0 text-red-600 hover:text-red-800 p-1 bg-white rounded-full shadow";
            deleteBtn.onclick = () => deleteImage(image.id, wrapper);

            wrapper.appendChild(imgElement);
            wrapper.appendChild(textarea);
            wrapper.appendChild(deleteBtn);
            imageContainer.appendChild(wrapper);
        });

    } catch (error) {
        console.error("Error obteniendo las imágenes:", error);
    }
}

async function deleteImage(imageId, wrapperElement) {
    if (!confirm("¿Estás seguro de que deseas eliminar esta imagen?"));

    try {
        let url = `{{ route('workorder.images.delete', ':id') }}`.replace(':id', imageId);
        let response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        if (response.ok) {
            wrapperElement.remove(); // Elimina visualmente del DOM
            alert("Imagen eliminada con éxito.");
        } else {
            alert("Error al eliminar la imagen.");
        }
    } catch (error) {
        console.error("Error al eliminar la imagen:", error);
    }
}


    async function destroy(id) {
    try {
        // Confirmación antes de eliminar
        const confirmation = confirm("¿Estás seguro de que deseas eliminar este detalle?");
        if (!confirmation) return; // Si el usuario cancela, no hacer nada

        // Definir la URL para la solicitud DELETE
        let url = `{{ route('workorders.destroyWork', ':id') }}`.replace(':id', id);

        // Enviar la solicitud DELETE al servidor
        let response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Asegúrate de incluir el token CSRF
            }
        });

        // Verificar si la respuesta fue exitosa
        if (response.ok) {
    // Buscar la fila correspondiente en el DOM usando el ID
    const trHijo = document.getElementById(`nro_trabajo_${id}`);

    // Si la fila hijo existe, obtener su elemento padre (tr que la envuelve)
    if (trHijo) {
        const trPadre = trHijo.closest('tr');  // Busca el tr padre
            // Si el padre existe, ocultarlo
            if (trPadre) {
                trPadre.style.display = 'none';  // Ocultar el tr padre
            }
        }
        // Mostrar mensaje de éxito
        alert("Detalle de orden de trabajo eliminado con éxito.");
    } else {
                alert("Hubo un error al eliminar el detalle.");
            }
    } catch (error) {
        console.error("Error eliminando el detalle:", error);
    }
}



    async function details(id) {
        try {
        let url = `{{ route('workorder.details', ':id') }}`.replace(':id', id);
        let response = await fetch(url);
        let data = await response.json(); // Recibe los datos en JSON

        const tbody = document.getElementById("tbodyTrabajos");
        tbody.innerHTML = ""; // Limpiar contenido anterior

        data.forEach((trabajo, index) => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td class="px-3 py-2 border">${index + 1}</td>
                <td class="px-3 py-2 border">
                    <input type="text" value="${trabajo.nro_trabajo}" id="nro_trabajo_${trabajo.id}" />
                </td>
                <td class="px-3 py-2 border">
                    <input type="text" value="${trabajo.descripcion}" id="descripcion_${trabajo.id}" />
                </td>
                <td class="px-3 py-2 border">
                    <input type="text" value="${trabajo.materiales}" id="materiales_${trabajo.id}" />
                </td>
                <td class="px-3 py-2 border">
                    <input type="text" value="${trabajo.herramientas}" id="herramientas_${trabajo.id}" />
                </td>
                <td class="px-3 py-2 border">
                    <input type="text" value="${trabajo.observaciones}" id="observaciones_${trabajo.id}" />
                </td>
                <td class="px-3 py-1 whitespace-nowrap text-sx ">
                    <button type="button" class="text-green-600 hover:text-green-900"
                        onclick="save(${trabajo.id})">
                        <i class="bi bi-save"></i>
                    </button>
                     <button type="submit" class="text-red-600 hover:text-red-900"
                        onclick="destroy(${trabajo.id})">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                    <button type="button" class="text-red-600 hover:text-red-900"
                        onclick="addImage(${trabajo.id})">
                        <i class="bi bi-image"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });
        document.getElementById("ModalDetails").classList.remove("hidden");
        } catch (error) {
            console.error("Error obteniendo los detalles:", error);
        }
    }
    async function destroy(id) {
            if (!confirm("¿Estás segura de eliminar esta Orden de Trabajo?")) return;

            try {
                let url = `/workorders/${id}`;
                let response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Elimina la fila directamente del DOM
                    alert("Trabajo eliminado exitosamente.");
                    all();
                } else {
                    const error = await response.json();
                    alert("Error al eliminar: " + (error.message || "Intenta nuevamente"));
                }
            } catch (error) {
                console.error("Error eliminando el trabajo:", error);
                alert("Ocurrió un error. Revisa la consola.");
            }
        }

        async function save(id) {
            try {
                // Obtener los valores de los inputs
                const nro_trabajo = document.getElementById(`nro_trabajo_${id}`).value;
                const descripcion = document.getElementById(`descripcion_${id}`).value;
                const materiales = document.getElementById(`materiales_${id}`).value;
                const herramientas = document.getElementById(`herramientas_${id}`).value;
                const observaciones = document.getElementById(`observaciones_${id}`).value;

                // Enviar los datos actualizados al servidor
                let url = `{{ route('workorders.update', ':id') }}`.replace(':id', id);
                let response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nro_trabajo,
                        descripcion,
                        materiales,
                        herramientas,
                        observaciones
                    })
                });

                if (response.ok) {
                    alert("Trabajo actualizado con éxito.");
                } else {
                    alert("Error al actualizar el trabajo.");
                }
            } catch (error) {
                console.error("Error guardando los cambios:", error);
            }
        }

        function openModal(name,id,element) {
            document.getElementById(element).value = id;

            // Mostrar el modal
            document.getElementById(name).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        function handleFileSelect(event) {
    const preview = document.getElementById('previewImagenes');
    preview.innerHTML = ''; // Limpiar vistas previas anteriores

    const files = event.target.files;
    if (!files.length) return;

    const allowedExtensions = ['jpg', 'jpeg', 'png'];

    for (const file of files) {
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            alert('Solo se permiten archivos con las extensiones .jpg, .jpeg o .png');
            event.target.value = ''; // Limpiar input
            return; // Salir si hay un archivo inválido
        }
    }

    // Si todos los archivos son válidos, continuar con la vista previa
    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const container = document.createElement('div');
            container.className = 'mb-4 p-2 border rounded bg-gray-50';

            container.innerHTML = `
                <img src="${e.target.result}" class="w-full h-40 object-cover rounded shadow mb-2">
                <label class="block text-sm text-gray-700 mb-1">Descripción de la imagen:</label>
                <textarea name="descripciones[]" rows="2" class="w-full border rounded p-1 text-sm resize-none" placeholder="Escribe una descripción para la imagen ${index + 1}"></textarea>
            `;

            preview.appendChild(container);
        };
        reader.readAsDataURL(file);
    });
}

    async function edit(id){
        document.getElementById("ModalEdit").classList.remove("hidden");
        let url = `{{ route('workorder.getWorkOrder', ':id') }}`.replace(':id', id);
        let response = await fetch(url);
        let data = await response.json(); // Recibe los datos en JSON
        console.log(data);

        document.getElementById('work_order_id_update').value = data.workOrder.id;
        document.getElementById('empresa').value = data.workOrder.empresa;
        document.getElementById('order_work').value = data.workOrder.order_work;

        // Seleccionar los operarios
        const workerSelect = document.getElementById('workers');
        if (data.workers && Array.isArray(data.workers)) {
            data.workers.forEach(worker => {
                const option = workerSelect.querySelector(`option[value="${worker.worker_id}"]`);
                if (option) {
                    option.selected = true;
                }
            });
        }
        // Seleccionar los supervisores
        const SupervisorSelect = document.getElementById('supervisor_id');
        if (data.workOrderSupervisors && Array.isArray(data.workOrderSupervisors)) {
            data.workOrderSupervisors.forEach(supervisor => {
                const option = SupervisorSelect.querySelector(`option[value="${supervisor.supervisor_id}"]`);
                if (option) {
                    option.selected = true;
                }
            });
        }
        // Seleccionar los jefes
        const maintenanceManagerSelect = document.getElementById('maintenance_manager_id');
        if (data.workOrderMaintenanceManagers && Array.isArray(data.workOrderMaintenanceManagers)) {
            data.workOrderMaintenanceManagers.forEach(maintenanceManager => {
                const option = maintenanceManagerSelect.querySelector(`option[value="${maintenanceManager.maintenance_manager_id}"]`);
                if (option) {
                    option.selected = true;
                }
            });
        }
         // Mostrar la imagen si existe
    const imagenBloque = document.querySelector('#ModalEdit .imagen-bloque'); // Selecciona el contenedor de la imagen en el modal
    const previewImagen = document.getElementById('previewImagen'); // El div para la vista previa

    // Limpiar cualquier vista previa anterior
    previewImagen.innerHTML = '';

    if (data.workOrder.image_path) {
        const imagePreview = document.createElement('img');
        imagePreview.src = `/storage/${data.workOrder.image_path}`; // Asegúrate de que la ruta sea correcta
        imagePreview.classList.add('w-full', 'h-40', 'object-cover', 'rounded', 'shadow', 'mb-2'); // Clases de Tailwind para estilo

        // Crear un contenedor para la imagen y la descripción (si la tienes)
        const previewContainer = document.createElement('div');
        previewContainer.classList.add('mb-4', 'p-2', 'border', 'rounded', 'bg-gray-50');
        previewContainer.appendChild(imagePreview);

        // Si también quieres mostrar la descripción existente
        if (data.workOrder.descripcion) {
            const descriptionLabel = document.createElement('label');
            descriptionLabel.classList.add('block', 'text-sm', 'text-gray-700', 'mb-1');
            descriptionLabel.textContent = 'Descripción del Logo:';

            const descriptionTextarea = document.createElement('textarea');
            descriptionTextarea.name = 'descripcion';
            descriptionTextarea.rows = 2;
            descriptionTextarea.classList.add('w-full', 'border', 'rounded', 'p-1', 'text-sm', 'resize-none');
            descriptionTextarea.value = data.workOrder.descripcion;

            previewContainer.appendChild(descriptionLabel);
            previewContainer.appendChild(descriptionTextarea);
        }

        previewImagen.appendChild(previewContainer);
    } else {
        // Si no hay imagen, puedes mostrar un mensaje o dejar el área vacía
        const noImageMessage = document.createElement('p');
        noImageMessage.textContent = 'No hay imagen adjunta.';
        previewImagen.appendChild(noImageMessage);
    }
    }
    function addImageWork(event) {
    const preview = document.getElementById('imageContainer');
    // preview.innerHTML = ''; // NO BORRAR las vistas previas anteriores

    const files = event.target.files;
    if (!files.length) return;

    Array.from(files).forEach((file, index) => {
        if (!file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const container = document.createElement('div');
            container.className = 'mb-4 p-2 border rounded bg-gray-50';

            container.innerHTML = `
                <img src="${e.target.result}" class="w-full h-20 object-cover rounded shadow mb-2">
                <label class="block text-sm text-gray-700 mb-1">Descripción de la imagen:</label>
                <textarea name="descripciones[]" rows="2" class="w-full border rounded p-1 text-sm resize-none" placeholder="Escribe una descripción para la imagen"></textarea>
            `;

            preview.appendChild(container);
        };
        reader.readAsDataURL(file);
    });
}

        document.addEventListener('DOMContentLoaded', () => {
            all();


        });
        function all() {
                const btnBuscar = document.getElementById('btnBuscar');
                const tableBody = document.getElementById('tbody');
                fetch(
                        `{{ route('workorder.search') }}?`
                    )
                    .then(response => response.json())
                    .then(orders => {
                        let rowsHtml = '';
                        console.log('orders', orders);
                        if (orders.length > 0) {

                            orders.forEach(order_work => {

                                rowsHtml += `
                                    <tr>
                                        <td class="px-3 py-1 whitespace-nowrap text-sx  text-gray-900">
                                            ${order_work.order_work ?? ''}
                                        </td>
                                        <td class="px-3 py-1 whitespace-nowrap text-sx  text-gray-900">${order_work.empresa ?? ''}</td>
                                        <td class="px-3 py-1 whitespace-nowrap text-sx ">
                                             <button type="button" class="text-red-600 hover:text-red-900"
                                                    onclick="openModal('ModalAdd',${order_work.id},'work_order_id')">
                                                    <i class="bi bi-plus-circle-fill"></i>
                                                </button>
                                                 <button type="button" class="text-red-600 hover:text-red-900"
                                                    onclick="details(${order_work.id})">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                 <button type="button" class="text-red-600 hover:text-red-900"
                                                    onclick="pdf(${order_work.id})">
                                                <i class="bi bi-file-earmark-pdf-fill text-red-600 text-xl"></i>
                                                </button>
                                                 <button type="button" class="text-red-600 hover:text-red-900"
                                                    onclick="edit(${order_work.id})">
                                                <i class="bi bi-pencil-square"></i>
                                                </button>
                                                 <button type="button" class="text-red-600 hover:text-red-900"
                                                    onclick="destroy(${order_work.id})">
                                                <i class="bi bi-trash-fill"></i>
                                                </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            rowsHtml = `
                                <tr>
                                    <td colspan="10" class="px-3 py-1 text-center text-gray-500">No hay registros disponibles</td>
                                </tr>
                            `;
                        }

                        tableBody.innerHTML = rowsHtml;
                    })
                    .catch(error => console.error('Error en la búsqueda:', error));
            }
        document.getElementById('formWorkOrderImages').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch('{{ route('workorder.updateImage') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('response', response);

                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: {
                            popup: 'text-sm p-2 w-64 rounded-md shadow-md'
                        }
                    });

                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
        async function pdf(id) {
            try {
                let url = `{{ route('workorder.pdf', ':id') }}`.replace(':id', id);
                window.open(url, '_blank');
            } catch (error) {
                console.error("Error al generar el PDF:", error);
            }
        }
        document.getElementById('formAddTrabajo').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch('{{ route('workorder.add') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('response', response);
                if (!response.ok) {
                    console.log('hola');
                    return response.json().then(err => {
                        let errorMessages = '';
                        console.log(err)
                        if (err.errors) {
                            console.log("1")
                            for (let field in err.errors) {
                                errorMessages += `${field}: ${err.errors[field].join(', ')}\n`;
                            }
                        } else if (err.error) {
                            console.log("2")
                            errorMessages = err.error;
                        } else if (err.errorPago) {
                            console.log("3")
                            errorMessages = err.errorPago;
                        }
                        console.log(errorMessages)

                        if (errorMessages) {
                            console.log("4")
                            Swal.fire({
                                title: 'Errores de Validación',
                                text: errorMessages,
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }

                        throw new Error('Error en la respuesta del servidor');
                    });
                }

                return response.json();
            })
            .then(data => {
                closeModal('ModalAdd');
                const preview = document.getElementById('previewImagenes');
                preview.innerHTML = ''; // Limpiar vistas previas anteriores
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    document.getElementById('formAddTrabajo').reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
    </script>

</x-app-layout>
