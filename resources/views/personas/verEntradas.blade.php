@extends('personas.layouts.app')
@section('content')
    <div class="containerEdit bgMainObras">

        <div class="my-12">
            <div class="container text-white py-section bgCardObra">
              <div class="grid grid-cols-12 md:gap-4 gap-y-10">
                <div class="col-span-12 md:col-span-3">
                  <h3 class="text-3xl font-bold font-primary">
                    Mi cuenta
                  </h3>
                  <div class="flex flex-col mt-6">
                    <a
                      class="block py-3 pl-4 font-semibold border-l-4 hover:border-red-600 hover:text-red-600"
                      href="{{ route('personas.editarperfil') }}"
                      >Detalles de cuenta</a
                    >
                    <a
                      class="block py-3 pl-4 font-semibold text-red-600 border-l-4 border-red-600 border-emerald-100"
                      href="{{ route('personas.miscompras') }}"
                      >Mis Compras</a
                    >
                    <a
                      class="block py-3 pl-4 font-semibold border-l-4 border-emerald-100 hover:border-red-600 hover:text-red-600"
                       href="{{ route('personas.cambiarpass') }}"
                      >Cambiar contraseña</a
                    >
                  </div>
                  <div class="mt-6">
                    <form action="{{ route('personas.logout') }}" method="POST">
        	@csrf
       		 <button class="px-4 py-2 text-sm font-bold leading-none text-red-600 uppercase border border-red-600 rounded hover:bg-red-600 hover:text-white disabled:opacity-25"
 type="submit">Cerrar sesión</button>
   		 </form>
                  </div>
                </div>
                <div class="col-span-12 md:col-span-9">
                  <div class="space-y-5 containerTableMisCompras">
                    <h2 class="text-3xl font-medium">Mis Entradas</h2>

					@if($attendees->count())
                    <table class="w-full overflow-hidden rounded-lg">
                    <thead>
                    <tr class="bg-dark-blue-700">

					<th class="p-4 font-medium text-left text-heading">Dni</th>
                    <th class="p-4 font-medium text-left text-heading">Nombres y Apellidos</th>
                    <th class="p-4 font-medium text-left text-heading">Correo</th>
                    <th class="p-4 font-medium text-left text-heading">Entrada</th>

					<th class="p-4 font-medium text-left text-heading">Acción</th>
                  </tr>
                  </thead>
                    <tbody class="divide-y divide-dark-blue-700">
					@foreach($attendees as $attendee)
					<tr class="hover:bg-dark-blue-700">

					<td class="px-4 py-4 text-left underline">
                        <p class="text-sm">{{$attendee->last_name}}</p>
                      </td>
                      <td class="px-4 py-4 text-left underline">
                        <p class="text-sm">{{$attendee->first_name}}</p>
                      </td>
					  <td class="px-4 py-4 text-left ">
					  <p>{{$attendee->email}}</p>
                      </td>
                      <td class="px-4 py-4 text-left ">
                        <p>{{{$attendee->ticket->title}}}</p>
                    </td>

                      <td class="px-4 py-4 text-left "><button class="btn btn-primary" onclick="openEditModal({{$attendee->id}}, '{{$attendee->first_name}}', '{{$attendee->last_name}}', '{{$attendee->email}}', '{{$compraid}}')">
                           <span class="block px-4 py-2 mr-3 text-sm font-bold text-white bg-red-600 rounded-full  w-max"> Editar</span>
                        </button>

						<a class="bg-green-600 btn" target="_blank"
						   href="{{route('showOrderTickets2', ['order_reference' => $attendee->order_id, 'ticket_reference' => $attendee->private_reference_number])}}?download=1">
						   <span class="block px-4 py-2 mr-3 text-sm font-bold text-white bg-green-600 rounded-full  w-max" style="margin-top: 10px;">
						   Descargar</span>
						   </a>
						</td>

                    </tr>
					@endforeach

                      </tbody>
                        </table>
						@else
							<p class="text-sm">No se encontraron asistentes registrados a esta compra, la modificación esta disponible 24 horas antes de que empiece la Funcion.</p>
					 @endif


                        <div class="px-4 py-4 border-t border-dark-blue-400 ">
                          <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between text-gray-300">
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">

                            </div>

                          </nav>
                          </div>
                          </div>
                          </div>
              </div>
            </div>
        </div>
		<!-- Modal -->
@if($attendees->count())
<div id="editModal" class="fixed inset-0 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="max-w-full p-4 bg-white rounded-lg panel panel-primary sm:w-1/2 md:w-1/2 lg:w-1/3" style="
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 20px;
    background-color: #1d313d;padding: 0;
">
        <div class="mb-4 panel-heading" style="
    background-color: #bb001d;
    color: #fff;
    padding: 15px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
">
            <h3 class="panel-title">
                <b>EDITAR ASISTENTE</b>
            </h3>
        </div>

        <div class="panel-body" style="padding: 20px;">
            <form action="{{ route('personas.editarentrada', ['event_id' => ':compraid', 'attendee_id' => ':attendee_id']) }}" method="POST" id="editForm">
                @csrf
                @method('POST')

                <!-- Fila para DNI y Nombres -->
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dni" class="block text-sm font-medium" style="color: white;">DNI</label>
                            <input type="text" id="dni" name="last_name" class="w-full p-2 mt-2 border rounded ticket_holder_last_name form-control" value="{{ old('dni', $attendee->dni) }}">
                        </div>
                    </div>

                    <div class="col-md-6" style="
    margin-top: 10px;
">
                        <div class="form-group">
                            <label for="first_name" class="block text-sm font-medium" style="color: white;">Nombres y Apellidos</label>
                            <input type="text" id="first_name" name="first_name" class="w-full p-2 mt-2 border rounded ticket_holder_first_name form-control" value="{{ old('first_name', $attendee->full_name) }}">
                        </div>
                    </div>
                </div>

                <!-- Fila para Correo electrónico -->
                <div class="mb-4 row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="email" class="block text-sm font-medium" style="color: white;">Correo electrónico</label>
                            <input type="email" id="email" name="email" class="w-full p-2 mt-2 border rounded ticket_holder_email form-control" value="{{ old('email', $attendee->email) }}">
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-bold leading-none text-green-600 uppercase border border-green-600 rounded hover:bg-green-600 hover:text-white disabled:opacity-25">Guardar</button>
                    <button type="button" onclick="closeEditModal()" class="p-2 ml-2 text-white bg-red-500 rounded hover:bg-red-400">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<script>
    // Función para abrir el modal y rellenar los campos
    function openEditModal(attendee_id, fullName, dni, email, compraid) {
    // Coloca los datos en los campos del formulario
    document.getElementById('first_name').value = fullName;
    document.getElementById('dni').value = dni;
    document.getElementById('email').value = email;

    // Actualiza la URL del formulario con los parámetros dinámicos
    let formAction = "{{ route('personas.editarentrada', ['event_id' => ':compraid', 'attendee_id' => ':attendee_id']) }}";
    formAction = formAction.replace(':compraid', compraid).replace(':attendee_id', attendee_id);
    document.getElementById('editForm').action = formAction;

    // Muestra el modal
    document.getElementById('editModal').classList.remove('hidden');
	}

    // Función para cerrar el modal
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

@endsection
