@extends('personas.layouts.app')
@section('content')
    <div class="flex min-h-screen justify-between flex-col bgLight">
        <div class="flex flex-col justify-between h-full">
  
       
        <div>
<!--<form id="contactForm" action="https://formspree.io/f/xpwagnrg" method="POST" class="flex flex-col justify-center items-center">-->
{!! Form::open(['url' => route('personas.postContactOrganiser'), 'class' => 'flex flex-col justify-center items-center reset ajax', 'id' => 'contact-form']) !!}
    <div class="bgLoginForm flex flex-col items-center gap-4 containerRegistro my-10">
        <img src="{{ config('app.url') }}/recursos/public/logoMain2.png" />
        <span class="flex gap-1 textoResaltado sectionName">
            Contáctese con Nosotros
        </span>
        <div id="successMessage" style="background-color: white; color: red; padding: 15px; margin: 20px 0; text-align: center; font-weight: 700; width: 100%; display: none;">
            ¡Mensaje enviado con éxito!
        </div>
        <div class="containerFormRegistro">
            <div class="flex flex-col">
                <label class="flex flex-col">
                    Nombre y Apellido:
                    <input type="text" name="name" required />
                </label>
            </div>
            <div class="flex flex-col w-full">
                <label class="flex flex-col">
                    E-mail:
                    <input type="email" name="email" required />
                </label>
            </div>
        </div>
        <div class="containerTextArea">
            <label class="flex flex-col">
                Motivo de Consulta:
                <textarea class="bg-transparent border border-gray-400 mt-2" name="message" required></textarea>
            </label>
        </div>
        <button type="submit" class="btnFormLogin uppercase" id="submit-button">Enviar</button>
    </div>

</form>
        </div>
        </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $('#contact-form').on('submit', function(e) {
        e.preventDefault(); // Evita el envío del formulario normal

        // Deshabilitar el botón y cambiar el texto
        var submitButton = $('#submit-button');
        submitButton.prop('disabled', true).text('Enviando...');

        $.ajax({
            url: $(this).attr('action'), // Usa la URL del formulario
            method: 'POST',
            data: $(this).serialize(), // Serializa el formulario, incluyendo el token CSRF
            success: function(response) {
                if (response.status === 'success') {
                    $('#successMessage').show(); // Muestra el mensaje de éxito
                    $('#contact-form')[0].reset(); // Reinicia el formulario
                } else {
                    alert('Hubo un problema al enviar el mensaje.');
                }
            },
            error: function(e) {
                console.log('Error:', e);
                alert('Hubo un error en la solicitud. Por favor, intenta de nuevo.');
            },
            complete: function() {
                // Habilitar el botón y restablecer el texto
                submitButton.prop('disabled', false).text('Enviar');
            }
        });
    });
</script>
@endsection
