@extends('personas.layouts.app')
@section('content')
<style>
        /* Para navegadores basados en WebKit (Chrome, Safari) */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Para navegadores Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }
		
		.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: hidden; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 80%; 
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
    </style>

    <div class="flex flex-col justify-between h-full">
      
      
      <div class="bgLogin">
<form class="flex flex-col justify-center items-center" action="{{ route('personas.register') }}" method="POST">
    @csrf
    <div class="bgLoginForm flex flex-col items-center gap-4 containerRegistro">
        <img src="{{ config('app.url') }}/recursos/public/logoMain2.png" />
        <span class="flex text-white gap-1">
            ¿Ya tienes Cuenta?
            <a href="{{ route('personas.login') }}" class="textoResaltado">
                Ingresa Aquí
            </a>
        </span>

        <div class="containerFormRegistro">
            <div class="flex flex-col">
                <label class="flex flex-col">
                    Nombre y Apellido:
                    <input type="text" name="nombres" id="nombres" required value="{{ old('nombres') }}" />
                    @error('nombres')
                    <div class="alert alert-danger" style="font-size: small;">*{{ $message }}*</div>
                    @enderror
                </label>
                <label class="flex flex-col">
                    DNI:
                    <input type="text" name="dni" id="dni" required value="{{ old('dni') }}"/>
					<div class="alert alert-danger" style="font-size: small;color: var(--bgLoginForm);">-</div>
                </label>
				
				<label class="flex flex-col">
                    Celular:
                    <input type="number" name="telefono" id="telefono" required value="{{ old('telefono') }}"/>
                    @error('telefono')
                    <div class="alert alert-danger" style="font-size: small;">*{{ $message }}*</div>
                    @enderror
                </label>
            </div>
            <div class="flex flex-col">
                <label class="flex flex-col">
                    Email:
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" />
                    @error('email')
                    <div class="alert alert-danger" style="font-size: small;">*{{ $message }}*</div>
                    @enderror
                </label>
				<label class="flex flex-col">
                    Contraseña:
                    <input type="password" name="contraseña" id="contraseña" required value="{{ old('contraseña') }}"/>
                    <div class="alert alert-danger" style="font-size: small;">La contraseña debe tener al menos 8 caracteres</div>
                </label>
                <label class="flex flex-col">
                    Repita Contraseña:
                    <input type="password" name="contraseña_confirmation" id="contraseña_confirmation" required value="{{ old('contraseña_confirmation') }}"/>
                    @error('contraseña_confirmation')
                    <div class="alert alert-danger" style="font-size: small;">*{{ $message }}*</div>
                    @enderror
                </label>
            </div>
        </div>

        <div class="flex flex-col">
            <label class="flex items-center">
                <input type="checkbox" id="termsCheckbox" />
                <span class="ml-2 text-white">Acepto los <a href="#" id="termsLink" class="textoResaltado">términos y condiciones</a></span>
            </label>
        </div>

        <button type="submit" class="btnFormLogin uppercase" id="submitButton" disabled>Registrarme</button>
    </div>
</form>
      </div>
	  <div id="termsModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h1>Términos y Condiciones</h1>
        @if(empty($organiser->tax_id))
			<p>No se encontró los términos y condiciones</p> 
			
		@else
			<textarea class="form-control" placeholder="Términos y condiciones" rows="4" name="tax_id" cols="50" id="tax_id" style="width: 100%; height: 500px; border: none; background: transparent; resize: none;" readonly>
    {{ $organiser->about }}
</textarea>
	@endif
    </div>
</div>
	 


@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif


<script>
document.addEventListener('DOMContentLoaded', function() {
    const termsCheckbox = document.getElementById('termsCheckbox');
    const submitButton = document.getElementById('submitButton');
    const termsModal = document.getElementById('termsModal');
    const termsLink = document.getElementById('termsLink');
    const closeModal = document.getElementById('closeModal');

    // Habilitar o deshabilitar el botón de registro
    termsCheckbox.addEventListener('change', function() {
        submitButton.disabled = !this.checked;
    });

    // Mostrar el modal
    termsLink.addEventListener('click', function(e) {
        e.preventDefault();
        termsModal.style.display = 'block';
    });

    // Cerrar el modal
    closeModal.addEventListener('click', function() {
        termsModal.style.display = 'none';
    });

    // Cerrar el modal al hacer clic fuera de él
    window.addEventListener('click', function(event) {
        if (event.target === termsModal) {
            termsModal.style.display = 'none';
        }
    });
});
</script>
@endsection
