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
	  <br>
<br>
        <form id="formulario1" class="flex flex-col justify-center items-center" method="POST">
	@csrf
          <div class="bgLoginForm flex flex-col items-center gap-4 containerLogin">
            <img src="{{ config('app.url') }}/recursos/public/logoMain2.png" />
            <span class="flex text-white gap-1 font-semibold py-5">
              RECUPERAR CONTRASEÑA
            </span>
            <div class="containerFormLogin">
              <div class="flex flex-col">
                <label class="flex flex-col">
                  Ingrese su correo:
                  <input type="email" name="email" id="email" required value="{{ old('email') }}" />
			@error('email')
                	<div class="alert alert-danger" style="font-size: small;">*{{ $message }}*</div>
            		@enderror
                </label>
                
              </div>
            </div>
            <button id="submitBtn" type="button" onclick="recuperarp(event)" class="btnFormLogin">Recuperar</button>
            <p class="text-white "> <a href="{{ route('personas.login') }}" class="textoResaltado">Regresar</a></p>
		
          </div>
        </form>
		
		<form id="formulario2" class="flex flex-col justify-center items-center" method="POST" style="display:none">
	@csrf
		 <input type="hidden" name="emailctp" id="emailctp"/>
          <div class="bgLoginForm flex flex-col items-center gap-4 containerLogin">
            <img src="{{ config('app.url') }}/recursos/public/logoMain2.png" />
            <span class="flex text-white gap-1 font-semibold py-5">
              RECUPERAR CONTRASEÑA
            </span>
            <div class="containerFormLogin">
              <div class="flex flex-col">
                <label class="flex flex-col">
                  Ingrese el código temporal:
                  <input type="text" name="ctp" id="ctp" required value="" />
                </label>
				<label class="flex flex-col">
                  Ingrese nueva contraseña:
                  <input type="password" name="npass" id="npass" required value=""  minlength="8"/>
                </label>
				<label class="flex flex-col">
                  Repita la contraseña:
                  <input type="password" name="rpass" id="rpass" required value=""  minlength="8"/>
                </label>
                
              </div>
            </div>
            <button type="button" onclick="recuperarp2(event)" class="btnFormLogin">Enviar</button>
            <p class="text-white "> <a href="{{ route('personas.login') }}" class="textoResaltado">Regresar</a></p>
		
          </div>
        </form>
		<br>
<br><br>
<br><br>

      </div>
    </div>

<script>
function recuperarp(event) {
    event.preventDefault(); // Evita el envío tradicional del formulario

    // Obtener el valor del correo
    var emailInput = document.getElementById("email");
    var emailValue = emailInput.value.trim();
	
	var submitBtn = document.getElementById("submitBtn");

    // Validación de si el campo no está vacío y si es un correo válido
    if (!emailValue) {
        emailInput.setCustomValidity("El campo de correo no puede estar vacío.");
    } else if (!isValidEmail(emailValue)) {
        emailInput.setCustomValidity("Por favor, ingrese un correo electrónico válido.");
    } else {
        emailInput.setCustomValidity(""); // Si es válido, eliminamos el mensaje de error
    }

    // Si la validación falla, mostramos un mensaje de error
    if (!emailInput.checkValidity()) {
        alert(emailInput.validationMessage);
    } else {
        // Deshabilitar el botón y cambiar el texto a "Enviando..."
        submitBtn.disabled = true;
        submitBtn.innerText = "Enviando...";
        // Si todo es válido, enviamos la solicitud AJAX
        var formData = new FormData();
        formData.append('email', emailValue);  // Agrega el email al FormData
        formData.append('_token', document.querySelector('input[name="_token"]').value);  // Incluye el CSRF token

        // Realizar la solicitud AJAX con fetch
        fetch("{{ route('personas.claveotp') }}", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())  // Convertir la respuesta en JSON
        .then(data => {
            // Si la respuesta es exitosa, mostramos el formulario 2
            if (data.success) {
                document.getElementById("formulario1").style.display = "none";
                document.getElementById("formulario2").style.display = "flex";
				document.getElementById("emailctp").value = emailValue;
				
                alert("Se ha enviado un código de verificación a tu correo.");
            } else {
                // Si la respuesta contiene un error, mostramos el mensaje
                alert(data.message || "Hubo un problema al procesar la solicitud.");
            }
        })
        .catch(error => {
            // En caso de error en la solicitud
            console.error("Error:", error);
            alert("Hubo un problema al procesar la solicitud.");
        })
        .finally(() => {
            // Volver a habilitar el botón y restaurar el texto
            submitBtn.disabled = false;
            submitBtn.innerText = "Enviar";
        });
    }
}

function isValidEmail(email) {
    // Expresión regular para validar el correo electrónico
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}


function recuperarp2(event) {
    event.preventDefault(); // Evita el envío tradicional del formulario

    // Obtener los valores de los campos
	var emailValue = document.getElementById("emailctp").value.trim();

    var ctpInput = document.getElementById("ctp");
    var ctpValue = ctpInput.value.trim();

    var npassInput = document.getElementById("npass");
    var npassValue = npassInput.value.trim();

    var rpassInput = document.getElementById("rpass");
    var rpassValue = rpassInput.value.trim();

    // Validación del código temporal
    if (!ctpValue) {
        ctpInput.setCustomValidity("El campo de código temporal no puede estar vacío.");
    } else {
        ctpInput.setCustomValidity(""); // Si es válido, eliminamos el mensaje de error
    }

    // Validación de las contraseñas
    if (!npassValue) {
        npassInput.setCustomValidity("El campo de nueva contraseña no puede estar vacío.");
    } else {
        npassInput.setCustomValidity(""); // Si es válido, eliminamos el mensaje de error
    }

    if (!rpassValue) {
        rpassInput.setCustomValidity("El campo de repetir contraseña no puede estar vacío.");
    } else {
        rpassInput.setCustomValidity(""); // Si es válido, eliminamos el mensaje de error
    }

    // Verificar que las contraseñas coincidan
    if (npassValue !== rpassValue) {
        rpassInput.setCustomValidity("Las contraseñas no coinciden.");
    } else {
        rpassInput.setCustomValidity(""); // Si las contraseñas coinciden, eliminamos el mensaje de error
    }

    // Si la validación falla, mostramos un mensaje de error
    if (!ctpInput.checkValidity() || !npassInput.checkValidity() || !rpassInput.checkValidity()) {
        alert("Por favor, corrige los errores en el formulario.");
    } else {
        // Si todo es válido, enviamos la solicitud AJAX
        var formData = new FormData();
		formData.append('email', emailValue);
        formData.append('ctp', ctpValue);  // Agrega el código temporal al FormData
        formData.append('npass', npassValue);  // Agrega la nueva contraseña al FormData
        formData.append('rpass', rpassValue);  // Agrega la confirmación de contraseña al FormData
        formData.append('_token', document.querySelector('input[name="_token"]').value);  // Incluye el CSRF token

        // Realizar la solicitud AJAX con fetch
        fetch("{{ route('personas.cambiarclaveotp') }}", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())  // Convertir la respuesta en JSON
        .then(data => {
            // Si la respuesta es exitosa, mostramos un mensaje de éxito
			console.log(data);
            if (data.success) {
                alert("Contraseña cambiada exitosamente.");
                console.log("Redirigiendo a:", "{{ route('personas.login') }}");
        
				// Intentar redirigir
				window.location.href = "https://allticketdev.bigresources.com.ar/iniciar-sesion";
				
            } else {
                // Si la respuesta contiene un error, mostramos el mensaje
                alert(data.message || "Hubo un problema al procesar la solicitud.");
            }
        })
        .catch(error => {
            // En caso de error en la solicitud
            console.error("Error:", error);
            alert("Hubo un problema al procesar la solicitud.");
        });
    }
}



</script>

@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif
@endsection