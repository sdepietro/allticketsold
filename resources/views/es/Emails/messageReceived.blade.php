@extends('es.Emails.Layouts.Master')

@section('message_content')

    <p>Hola,</p>
    <p>Ha recibido un mensaje de <b>Alltickets</b>.</p><br><br>
    Nombres y Apellidos: {{nl2br($sender_name)}}<br><br>
	Email: {{nl2br($sender_email)}}<br><br>
    Mensaje: {{nl2br($message_content)}}<br><br>
   

    <p>
        <b>Gracias por ponerte en contacto con nosotros. </b>
        
    </p>
@stop

@section('footer')


@stop
