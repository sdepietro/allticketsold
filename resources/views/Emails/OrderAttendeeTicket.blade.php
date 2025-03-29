@extends('Emails.Layouts.Master')

@section('message_content')

@lang("basic.hello") {{ $attendee->first_name }},<br><br>

<p><a href="{{ $ticket_url }}" target="_blank">Descarga tu ticket aqui</a></p>

{{ @trans("Order_Emails.tickets_attached") }} <a href="https://www.allticket.com.ar/">alltickets.com.ar</a>.

@stop
