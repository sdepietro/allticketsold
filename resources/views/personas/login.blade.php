@extends('personas.layouts.app')
@section('content')
<style>
input:-webkit-autofill {
  background-color: #1684df !important; /* Cambia el color de fondo del autocompletado en Chrome */
  color: #000 !important; /* Cambia el color del texto */
}

input::placeholder {
  color: #1684df; /* Cambia el color del placeholder */
}
</style>

    <div class="flex flex-col justify-between h-full" style="display: flex;flex-direction: column;justify-content: flex-start;height: auto;width: 100%;">
      
      

      <div class="bgLogin">
        <form class="flex flex-col justify-center items-center" action="{{ route('personas.login') }}" method="POST">
	@csrf
          <div class="bgLoginForm flex flex-col items-center gap-4 containerLogin">
            <img src="{{ config('app.url') }}/recursos/public/logoMain2.png" />
            <span class="flex text-white gap-1 font-semibold py-5">
              INGRESAR A PLATAFORMA DE COMPRA
            </span>
            <div class="containerFormLogin">
              <div class="flex flex-col">
                <label class="flex flex-col">
                  Correo:
                  <input type="email" name="email" id="email" required value="{{ old('email') }}" />
			@error('email')
                	<div class="alert alert-danger" style="font-size: small;">*{{ $message }}*</div>
            		@enderror
                </label>
                <label class="flex flex-col">
                  Contraseña:
                  <input type="password" name="contraseña" id="contraseña" required value="{{ old('contraseña') }}" minlength="8"/>
			@error('contraseña')
                	<div class="alert alert-danger" style="font-size: small;">*{{ $message }}*</div>
            		@enderror
                </label>
              </div>
            </div>
            <a href="{{ route('personas.recuperarp') }}" class="text-white border-b-2 pt-5">¿Olvidaste tu contraseña?</a>
            <button type="submit" class="btnFormLogin">INGRESAR</button>
            <p class="text-white ">¿No tienes una cuenta? <a href="{{ route('personas.register') }}" class="textoResaltado">Registrate</a></p>
		
          </div>
        </form>
      </div>
    </div>

<script>
    window.onload = function() {
		let urlGuardada = sessionStorage.getItem('currentUrl');
		console.log(urlGuardada);
    }
	</script>
	
@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif
@endsection
