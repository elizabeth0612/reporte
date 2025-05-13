<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        </h2>
    </x-slot>
    <div class="max-w-6xl mx-auto px-6 py-10 bg-white shadow-md rounded-2xl">
        <h2 class="text-xl font-bold text-gray-800 mb-6">📋 Registro de Orden de Trabajo</h2>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('workorders.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700" id="formOrder">
            @csrf
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
                <label for="" class="block font-medium mb-1">Solo se aceptan imagenes con extension .jpg .jpeg .png</label>
                <br>
                <input type="file" name="imagen" accept=".jpg, .jpeg, .png" onchange="handleFileSelect(event)">
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
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
      function handleFileSelect(event) {
    const preview = document.getElementById('previewImagen');
    preview.innerHTML = ''; // Limpiar vista previa anterior

    const file = event.target.files[0]; // Solo tomamos el primer archivo

    // Verificar si hay un archivo y si es una imagen
    if (!file || !file.type.startsWith('image/')) return;

    // Definir extensiones permitidas
    const allowedExtensions = ['jpg', 'jpeg', 'png'];
    const fileExtension = file.name.split('.').pop().toLowerCase(); // Obtener la extensión del archivo

    // Validar la extensión del archivo
    if (!allowedExtensions.includes(fileExtension)) {
            Swal.fire({
                icon: 'error',
                title: 'error!',
                text: 'Solo archivos con las extensiones .jpg, .jpeg o .png',
                showConfirmButton: false,
                timer: 2000
            });
        event.target.value = ''; // Limpiar el campo de entrada
        return; // Detener la ejecución si el archivo no es válido
    }

    // Crear la vista previa de la imagen
    const reader = new FileReader();
    reader.onload = function (e) {
        const container = document.createElement('div');
        container.className = 'mb-4 p-2 border rounded bg-gray-50';

        container.innerHTML = `
            <img src="${e.target.result}" class="w-full h-40 object-cover rounded shadow mb-2">
            <label class="block text-sm text-gray-700 mb-1">Descripción del Logo:</label>
            <textarea name="descripcion" rows="2" class="w-full border rounded p-1 text-sm resize-none" placeholder="Escribe una descripción del Logo"></textarea>
        `;

        preview.appendChild(container);
    };
    reader.readAsDataURL(file);
}

     document.getElementById('formOrder').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch('{{ route('workorders.store') }}', {
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
                 const preview = document.getElementById('previewImagen');
                 preview.innerHTML = ''; // Limpiar vista previa anterior
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1000
                    });
                    document.getElementById('formOrder').reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
   </script>

</x-app-layout>
