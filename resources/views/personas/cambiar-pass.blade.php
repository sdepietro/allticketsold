@extends('personas.layouts.app')
@section('content')
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
                      class="block py-3 pl-4 border-l-4 font-semibold hover:border-red-600 hover:text-red-600"
                      href="{{ route('personas.editarperfil') }}"
                      >Detalles de cuenta</a
                    >
                    <a
                      class="block py-3 pl-4 border-l-4 font-semibold border-emerald-100 hover:border-red-600 hover:text-red-600"
                      href="{{ route('personas.miscompras') }}"
                      >Mis Compras</a
                    >
                    <a
                      class="block py-3 pl-4 border-l-4 font-semibold border-emerald-100 border-red-600 text-red-600"
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
                <div class="col-span-12 md:col-span-9"><div class="space-y-5">
<div style="display: flex;justify-content: space-between;align-items: center;">
<h2 class="text-3xl font-medium">Cambiar contraseña</h2>
@if (session('success'))
    <p class="text-red-600">*{{ session('success') }}</p>
  @endif
</div>
<form action="{{ route('personas.actualizarpass', Auth::guard('clientes')->user()->id) }}" method="POST">
@csrf   
<div class="grid grid-cols-1 md:grid-cols-2  gap-6 ">
<div class="md:col-span-2">
<label for="current_password" class="block font-light text-sm text-white  undefined">Contraseña Actual *</label>
<div class="flex flex-col items-start">
<input type="password" name="current_password" class="input  w-full mt-1" required="" value="">
@error('current_password')
  <p class="text-red-500 text-sm">{{ $message }}</p>
@enderror
</div>
</div>
<div>
<label for="password" class="block font-light text-sm text-white  undefined">Contraseña nueva *</label>
<div class="flex flex-col items-start">
<input type="password" name="password" class="input  w-full mt-1" required="" value="">
@error('password')
   <p class="text-red-500 text-sm">{{ $message }}</p>
@enderror
</div>
</div>
<div>
<label for="password_confirmation" class="block font-light text-sm text-white  undefined">Confirmar contraseña nueva *</label>
<div class="flex flex-col items-start">
<input type="password" name="password_confirmation" class="input  w-full mt-1" required="" value="">
@error('password_confirmation')
  <p class="text-red-500 text-sm">{{ $message }}</p>
@enderror
</div>
</div>
</div>
<button type="submit" class="btn bg-gradient-red-invert relative disabled:opacity-50 mt-6 btnGuardarEdit">
<span class="bg-red-600 text-white px-6 py-2 font-bold uppercase">Guardar</span></button></form></div></div>
              </div>
            </div>
        </div>
        
@endsection
