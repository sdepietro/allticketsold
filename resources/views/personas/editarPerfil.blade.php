@extends('personas.layouts.app')
@section('content')
<script>
        function validateForm() {
            var email = document.getElementById("email").value;
            var emailConfirmation = document.getElementById("email_confirmation").value;

            if (email !== emailConfirmation) {
                alert("Los correos electrónicos no coinciden.");
                return false;
            }

            return true;
        }
    </script>

    <div class="containerEdit bgMainObras">
        
        <div class="my-12">
            <div class="container py-section bgCardObra text-white">
              <div class="grid grid-cols-12 md:gap-4 gap-y-10">
                <div class="col-span-12 md:col-span-3">
                  <h3 class="text-3xl font-primary font-bold">
                    Mi cuenta
                  </h3>
                  <div class="flex flex-col mt-6">
                    <a
                      class="block py-3 pl-4 border-l-4 font-semibold border-red-600 text-red-600"
                      href="{{ route('personas.editarperfil') }}"
                      >Detalles de cuenta</a
                    >
                    <a
                      class="block py-3 pl-4 border-l-4 font-semibold border-emerald-100 hover:border-red-600 hover:text-red-600"
                       href="{{ route('personas.miscompras') }}"
                      >Mis Compras</a
                    >
                    <a
                      class="block py-3 pl-4 border-l-4 font-semibold border-emerald-100 hover:border-red-600 hover:text-red-600"
                      href="{{ route('personas.cambiarpass') }}"
                      >Cambiar contraseña</a
                    >
                  </div>
                  <div class="mt-6">
                    
<form action="{{ route('personas.logout') }}" method="POST">
        	@csrf
       		 <button class="uppercase font-bold rounded text-red-600 border border-red-600 text-sm py-2 px-4 hover:bg-red-600 hover:text-white disabled:opacity-25 leading-none"
 type="submit">Cerrar sesión</button>
   		 </form>
                  </div>
                </div>

                <div class="col-span-12 md:col-span-9">
                    <div class="space-y-5">
			<div style="display: flex;justify-content: space-between;align-items: center;">
                        <h2 class="text-3xl font-medium">Detalles de Cuenta</h2>
			@if (session('success'))
        		<p class="text-red-600">*{{ session('success') }}</p>
    			@endif
			</div>
                        <form action="{{ route('personas.actualizarPerfil', ['id' => Auth::guard('clientes')->user()->id]) }}" method="POST" onsubmit="return validateForm()">
			@csrf                          
			<div class="grid grid-cols-1 md:grid-cols-2 gap-8 ">
                                <div>
                                <label for="email" class="block font-light text-sm text-white  undefined">Nombre y Apellido *</label>
                                <div class="flex flex-col items-start">
                                <input type="text" id="nombres" name="nombres" class="input  w-full mt-1" required="" value="{{ Auth::guard('clientes')->user()->nombres }}">
                            </div>
                        </div>
                            <div>
                                <label for="email" class="block font-light text-sm text-white  undefined">Telefono *</label>
                                <div class="flex flex-col items-start">
                                <input type="text" id="telefono" name="telefono" class="input  w-full mt-1" required="" value="{{ Auth::guard('clientes')->user()->telefono }}">
                            </div>
                        </div>
                            <div>
                                <label for="email" class="block font-light text-sm text-white  undefined">Email *</label>
                                <div class="flex flex-col items-start">
                                <input type="email" id="email" name="email" class="input  w-full mt-1" required="" value="{{ Auth::guard('clientes')->user()->email }}">
				@error('email')
            			<p>{{ $message }}</p>
        			@enderror
                            </div>
                        </div>
                            <div>
                                <label for="email" class="block font-light text-sm text-white  undefined">Confirmar Email *</label>
                                <div class="flex flex-col items-start">
                                <input type="email" name="email_confirmation" id="email_confirmation"  class="input w-full mt-1" required="" value="{{ Auth::guard('clientes')->user()->email }}">
				@error('email_confirmation')
            			<p>{{ $message }}</p>
        			@enderror
                            </div>
                        </div>
                        </div>
                            <button type="submit" class="btn bg-gradient-red-invert relative disabled:opacity-50 mt-6 btnGuardarEdit">
                                <span class="bg-red-600 text-white px-6 py-2 font-bold uppercase">Guardar</span>
                            </button>
                        </form>
                        </div>
            </div>
              </div>
            </div>
        </div>
@endsection 
