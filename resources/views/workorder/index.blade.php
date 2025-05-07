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
                    <div>
                        <label for="observaciones" class="block text-sm  text-gray-700">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" rows="2" class="mt-1 block w-full  border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
        
                    <!-- Imágenes -->
                    <div class="imagen-bloque">
                        <input type="file" name="imagenes[]" accept="image/*" onchange="handleFileSelect(event)" multiple>
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
        let url = `{{ route('workorders.destroy', ':id') }}`.replace(':id', id);
        
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

        Array.from(files).forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

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
                                            <a href="/workorder/${order_work.id}/edit" class="text-indigo-600 hover:text-indigo-900 "><i class="bi bi-pencil-square"></i> </a>
                                            <form action="/workorder/${order_work.id}" method="POST" style="display: inline;">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('¿Estás seguro de que deseas eliminar?');">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                                
                                            </form>
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
        });
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
