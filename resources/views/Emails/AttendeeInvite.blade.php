@extends('en.Emails.Layouts.Master')

@section('message_content')
Hola, {{$attendee->first_name}},<br><br>

Has sido invitado al evento  <b>{{$attendee->order->event->title}}</b>.<br/>
Su entrada para el evento está adjunta a este correo electrónico.

<br><br>
Gracias por elegir All Tickets
@stop
