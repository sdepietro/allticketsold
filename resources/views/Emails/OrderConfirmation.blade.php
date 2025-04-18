@extends('Emails.Layouts.Master')

@section('message_content')
@lang("basic.hello") {{$order->full_name}}, {!! @trans("Order_Emails.successful_order", ["name"=>$order->event->title]) !!}<br><br>
<p><a href="{{ $order_url }}" target="_blank">Descarga tus tickets aqui</a></p>
<br><br>
<strong>Para ingresar al recinto deberás mostrar tu e-ticket impreso o desde tu móvil.</strong>
@if(!$order->is_payment_received)
<br><br>
<strong>{{ @trans("Order_Emails.order_still_awaiting_payment") }}</strong>
<br><br>
{{ $order->event->offline_payment_instructions }}
<br><br>
@endif
<br><br>
<strong>1)</strong> Regístrate e ingresa a TU CUENTA<br>
<strong>2)</strong> Encontrá tu compra en la pestaña MIS COMPRAS.<br>
<strong>3)</strong> Tocá en el E-TICKET/S, para visualizarlo y/o descargarlo<br><br>

Sus entradas se adjuntan a este correo electrónico. También puede ver los detalles de su pedido y descargar sus entradas en: <a href="https://www.alltickets.com.ar/">alltickets.com.ar</a><br>
<br>
<h3>Detalles de la Orden</h3>
Referencia: <strong>{{$order->order_reference}}</strong><br>
Nombres y Apellidos: <strong>{{$order->full_name}}</strong><br>
Fecha: <strong>{{$order->created_at->format(config('attendize.default_datetime_format'))}}</strong><br>
Email: <strong>{{$order->email}}</strong><br>


@if (isset($order->business_name) && !empty($order->business_name)) {
<h3>Detalles Empresa</h3>
@if ($order->business_name) @lang("Public_ViewEvent.business_name"): <strong>{{$order->business_name}}</strong><br>@endif
@if ($order->business_tax_number) @lang("Public_ViewEvent.business_tax_number"): <strong>{{$order->business_tax_number}}</strong><br>@endif
@if ($order->business_address_line_two) @lang("Public_ViewEvent.business_address_line2"): <strong>{{$order->business_address_line_two}}</strong><br>@endif
@if ($order->business_address_state_province) @lang("Public_ViewEvent.business_address_state_province"): <strong>{{$order->business_address_state_province}}</strong><br>@endif
@if ($order->business_address_city) @lang("Public_ViewEvent.business_address_city"): <strong>{{$order->business_address_city}}</strong><br>@endif
@if ($order->business_address_code) @lang("Public_ViewEvent.business_address_code"): <strong>{{$order->business_address_code}}</strong><br>@endif
@endif

<div style="padding:10px; background: #F9F9F9; border: 1px solid #f1f1f1;">
    <table style="width:100%;">
        <tr>
            <td>
                <strong>Ticket</strong>
            </td>
            <td>
                <strong>Qty.</strong>
            </td>
            <td>
                <strong>Precio U.</strong>
            </td>
            <td>
                <strong>Servicios</strong>
            </td>
            <td>
                <strong>Total</strong>
            </td>
        </tr>
        @foreach($order->orderItems as $order_item)
        <tr>
            <td>{{$order_item->title}}</td>
            <td>{{$order_item->quantity}}</td>
            <td>
                @isFree($order_item->unit_price)
                FREE
                @else
                {{money($order_item->unit_price, $order->event->currency)}}
                @endif
            </td>
            <td>
                @isFree($order_item->unit_price)
                -
                @else
                {{money(($order_item->unit_booking_fee * $order_item->quantity), $order->event->currency)}}
                @endif
            </td>
            <td>
                @isFree($order_item->unit_price)
                FREE
                @else
                {{money(($order_item->unit_price + $order_item->unit_booking_fee) * ($order_item->quantity),
                $order->event->currency)}}
                @endif
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3"></td>
            <td><strong>Sub Total</strong></td>
            <td colspan="2">
                {{$orderService->getOrderTotalWithBookingFee(true)}}
            </td>
        </tr>
        @if($order->event->organiser->charge_tax == 1)
        <tr>
            <td colspan="3"></td>
            <td>
                <strong>{{$order->event->organiser->tax_name}}</strong><em>({{$order->event->organiser->tax_value}}%)</em>
            </td>
            <td colspan="2">
                {{$orderService->getTaxAmount(true)}}
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="3"></td>
            <td><strong>Total</strong></td>
            <td colspan="2">
                {{$orderService->getGrandTotal(true)}}
            </td>
        </tr>
    </table>
    <br><br>
</div>
<br><br>
Gracias por elegir All Tickets
@stop
