@extends('personas.layouts.app')
@section('content')

	<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
        /* Estilos generales */
        .custom-container {

            padding: 20px;
        }

        .custom-row {
            display: flex;
            flex-wrap: wrap; /* Permite que los elementos se ajusten en filas */
            margin: -10px;
        }

        .custom-col {
            padding: 10px;
            flex: 1; /* Permite que las columnas crezcan uniformemente */
        }

        /* Estilo para pantallas medianas y grandes */
        .custom-col-md-8 {
            flex: 0 0 60%; /* 8/12 */
            max-width: 60%;
        }

        .custom-col-md-4 {
            flex: 0 0 40%; /* 4/12 */
            max-width: 40%;
        }

        /* Estilos para pantallas pequeñas */
        @media (max-width: 767.98px) {
            .custom-col-md-8, .custom-col-md-4 {
                flex: 0 0 100%; /* Las columnas ocupan el 100% */
                max-width: 100%;


            }
			.custom-row {
            display: flex;
            flex-wrap: wrap; /* Permite que los elementos se ajusten en filas */
            margin: -10px;
			flex-direction: column-reverse;
			}
        }
		 .custom-button {
            width: 100%;
            margin: 10px 0;
            border: 2px solid transparent;
            border-radius: 5px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
			flex-direction: column;
			font-weight: 700;
        }
        .custom-button.selected {
            border-color: #007bff;
            background-color: #007bff;
            color: white;
        }
        .custom-button i {
            margin-right: 8px;
        }
    </style>
<style>
input:-webkit-autofill {
  background-color: #1684df !important; /* Cambia el color de fondo del autocompletado en Chrome */
  color: #000 !important; /* Cambia el color del texto */
}

input::placeholder {
  color: #1684df; /* Cambia el color del placeholder */
}

.hidden {
    display: none;
}


.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    background-color: transparent;
    color: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Estilos para el encabezado */
.section_head {
    text-align: center;
    font-size: 2rem;
    color: white;
    margin-bottom: 20px;
}

/* Estilos para el panel */
.panel {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 20px;
}

.panel-heading {
    background-color: #bb001d;
    color: #fff;
    padding: 15px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.panel-title {
    font-size: 1.25rem;
    margin: 0;
}

.panel-body {
    padding: 15px;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    padding: 8px;
    border: 1px solid #dee2e6;
    text-align: left;
}

.table th {
    background-color: #f2f2f2;
}

/* Estilos para los botones */
.btn {
    padding: 10px 15px;
    font-size: 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-success {
    background-color: #ff0000;
    color: white;
}

.btn-success:hover {
    background-color: #bb001d;
}

/* Estilos para el formulario */
.form-group {
    margin-bottom: 20px;
}

label {
    font-weight: bold;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
	color: black;
    border: 1px solid #ced4da;
    border-radius: 5px;
}

input[type="checkbox"] {
    margin-right: 10px;
}

/* Estilos para los detalles de negocios */
.hidden {
    display: none;
}

.ticket_holders_details {
    margin-top: 20px;
}

/* Estilos responsivos */
@media (max-width: 768px) {
    .col-xs-6 {
        width: 100%;
    }
    .col-md-4, .col-md-8 {
        width: 100%;
    }
}

@media (max-width : 600px) {
    .containerMenuDashboard {
        bottom: -13.0em !important;
    }
}
</style>

    <div class="flex flex-col justify-between h-full" style="display: flex;flex-direction: column;justify-content: flex-start;height: auto;width: 100%;">


<div class="bgMainObras">

<section id='order_form' class="container">
    <div class="wizard">
<div class="row">
                <h1 class="section_head">@lang("Public_ViewEvent.order_details")</h1>
            </div>
		<div class="help-block" style="text-align: center;margin-bottom: 20px;">
                        {!! @trans("Public_ViewEvent.time", ["time"=>"<span style='color: var(--textResaltado);font-weight: 600;font-size: large;' id='countdown'></span>"]) !!}
                    </div>
        <!-- Página 1 -->
        <div class="page" id="page1">

            <div class="row">
                <div class="col-md-12" style="text-align: center">
                    @lang("Public_ViewEvent.below_order_details_header")
                </div>
                <div class="col-md-4 col-md-push-8">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="ico-cart mr5"></i>
                                @lang("Public_ViewEvent.order_summary")
                            </h3>
                        </div>
                        <div class="panel-body pt0">
                            <table class="table mb0 table-condensed">
                                @foreach($tickets as $ticket)
                                <tr>
                                    <td class="pl0" style="color:white">{{{$ticket['ticket']['title']}}} X <b>{{$ticket['qty']}}</b></td>
                                    <td style="text-align: right;color:white">
                                        @isFree($ticket['full_price'])
                                            @lang("Public_ViewEvent.free")
                                        @else
                                            {{ money($ticket['price'], $event->currency) }}
                                        @endif

                                    </td>
                                </tr>
                                @endforeach
					@if (isset($feesOrden) && $feesOrden > 0)
    						<td class="pl0" style="color:white">Servicio</b></td>
                                    		<td style="text-align: right;color:white">{{ money($feesOrden, $event->currency) }}</td>
					@else

					@endif

                            </table>
                        </div>
                        @if($order_total > 0)
                        <div class="panel-footer" style="padding: 20px;">
                            <h5>
                                @lang("Public_ViewEvent.total"): <span style="float: right;"><b>{{ $orderService->getOrderTotalWithBookingFee(true) }}</b></span>
                            </h5>
                            @if($event->organiser->charge_tax)
                            <h5>
                                {{ $event->organiser->tax_name }} ({{ $event->organiser->tax_value }}%):
                                <span style="float: right;"><b>{{ $orderService->getTaxAmount(true) }}</b></span>
                            </h5>
                            <h5>
                                <strong>@lang("Public_ViewEvent.grand_total")</strong>
                                <span style="float: right;"><b>{{  $orderService->getGrandTotal(true) }}</b></span>
                            </h5>
                            @endif
                        </div>
                        @endif
                    </div>

                </div>
                <div class="col-md-8 col-md-pull-4">
                    {!! Form::open(['url' => route('personas.postValidateOrder', ['event_id' => $event_id]), 'class' => 'ajax payment-form', 'id' => 'formularioproceso']) !!}
                    {!! Form::hidden('event_id', $event_id) !!}
					<br>
                    <h3>@lang("Public_ViewEvent.your_information")</h3>
                    <div class="row">
			<div class="col-xs-6">
                            <div class="form-group">
                                {!! Form::label("order_last_name", "DNI") !!}
                               {!! Form::text("order_last_name", Auth::guard('clientes')->user() ? Auth::guard('clientes')->user()->dni : '', ['required' => 'required', 'class' => 'form-control', 'oninput' => 'this.value = this.value.replace(/[^0-9]/g, "")']) !!}
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                {!! Form::label("order_first_name", "Nombres y Apellidos") !!}
                                {!! Form::text("order_first_name", Auth::guard('clientes')->user() ? Auth::guard('clientes')->user()->nombres : '', ['required' => 'required', 'class' => 'form-control', 'id' => 'order_first_name']) !!}
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label("order_email", trans("Public_ViewEvent.email")) !!}
                                {!! Form::text("order_email", Auth::guard('clientes')->user() ? Auth::guard('clientes')->user()->email : '', ['required' => 'required', 'class' => 'form-control', 'id' => 'order_email']) !!}
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="nextPage(1)" style="border: 2px solid white;font-size: 1.2rem;font-weight: 500;">Siguiente</button>
                </div>
            </div>
        </div>

        <!-- Página 2 -->
        <div class="page" id="page4" style="display:none;">
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="custom-checkbox">
                            {!! Form::checkbox('is_business', 1, null, ['data-toggle' => 'toggle', 'id' => 'is_business', 'style' => 'display: none;']) !!}
                            {!! Form::label('is_business', trans("Public_ViewEvent.is_business"), ['class' => 'control-label']) !!}
							<span onclick="nextPage(2)" style="text-decoration: underline;color: var(--textResaltado);font-weight: 600;cursor: pointer;">Si no lo es omita esta sección</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="business_details">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    {!! Form::label("business_name", trans("Public_ViewEvent.business_name")) !!}
                                    {!! Form::text("business_name", null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    {!! Form::label("business_tax_number", trans("Public_ViewEvent.business_tax_number")) !!}
                                    {!! Form::text("business_tax_number", null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6" style="display:none">
                                <div class="form-group">
                                    {!! Form::label("business_address_line1", trans("Public_ViewEvent.business_address_line1")) !!}
                                    {!! Form::text("business_address_line1", null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    {!! Form::label("business_address_line2", "Dirección") !!}
                                    {!! Form::text("business_address_line2", null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    {!! Form::label("business_address_state", trans("Public_ViewEvent.business_address_state_province")) !!}
                                    {!! Form::text("business_address_state", null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    {!! Form::label("business_address_city", trans("Public_ViewEvent.business_address_city")) !!}
                                    {!! Form::text("business_address_city", null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    {!! Form::label("business_address_code", "Código Postal") !!}
                                    {!! Form::text("business_address_code", null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="prevPage(2)" style="border: 2px solid gray;font-size: 1.2rem;font-weight: 500;color: gray;">Anterior</button>
            <button type="button" class="btn btn-primary" onclick="nextPage(2)" style="border: 2px solid white;font-size: 1.2rem;font-weight: 500;">Siguiente</button>
        </div>

        <!-- Página 3 -->
        <div class="page" id="page2" style="display:none;">
            <div class="row">
                <div class="col-md-12">
			<h3>@lang("Public_ViewEvent.ticket_holder_information")</h3>
                    <div class="ticket_holders_details" style="display: flex;flex-wrap: wrap;justify-content: space-around;align-content: space-between;">

                        <?php
                            $total_attendee_increment = 0;
                        ?>
                        @foreach($tickets as $ticket)
                            @for($i=0; $i<=$ticket['qty']-1; $i++)
                            <div class="panel panel-primary col-sm-12 col-md-6 col-lg-4">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <b>{{$ticket['ticket']['title']}}</b>: @lang("Public_ViewEvent.ticket_holder_n", ["n"=>$i+1])
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
					<div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label("ticket_holder_last_name[{$i}][{$ticket['ticket']['id']}]", "DNI") !!}
                                                {!! Form::text("ticket_holder_last_name[{$i}][{$ticket['ticket']['id']}]", null, ['class' => "ticket_holder_last_name.$i.{$ticket['ticket']['id']} ticket_holder_last_name form-control"]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label("ticket_holder_first_name[{$i}][{$ticket['ticket']['id']}]", "Nombres y Apellidos") !!}
                                                {!! Form::text("ticket_holder_first_name[{$i}][{$ticket['ticket']['id']}]", null, ['class' => "ticket_holder_first_name.$i.{$ticket['ticket']['id']} ticket_holder_first_name form-control"]) !!}
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label("ticket_holder_email[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.email_address")) !!}
                                                {!! Form::text("ticket_holder_email[{$i}][{$ticket['ticket']['id']}]", null, ['class' => "ticket_holder_email.$i.{$ticket['ticket']['id']} ticket_holder_email form-control"]) !!}
                                            </div>
                                        </div>
                                        @include('Public.ViewEvent.Partials.AttendeeQuestions', ['ticket' => $ticket['ticket'],'attendee_number' => $total_attendee_increment++])
                                    </div>
                                </div>
                            </div>
                            @endfor
                        @endforeach
                    </div>
                </div>
            </div>
			<button type="button" class="btn btn-secondary" onclick="prevPage(2)" style="border: 2px solid gray;font-size: 1.2rem;font-weight: 500;color: gray;">Anterior</button>
            <button type="button" class="btn btn-primary" onclick="nextPage(2)" style="border: 2px solid white;font-size: 1.2rem;font-weight: 500;">Siguiente</button>

        </div>

		<div class="page" id="page3" style="display:none;">
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="custom-checkbox">

                        </div>
                    </div>
                </div>
            </div>
           <!------ aqio ---->
		  	<div id="pagoexito" style="display:none; margin-top: 20px;margin-bottom: 150px;">
				<h1 class="section_head" style="margin-bottom: 40px;" >¡Importante!</h1>
				<h1  id="idtransaccion" class="section_head" style="margin-bottom: 40px;" ></h1>

				 <div style="display: flex;justify-content: center;margin-bottom: 20px;">
								  <img class="logoMainHeader" src="{{ config('app.url') }}/recursos/public/espera.png"style="
    width: 200px !important;height: 200px !important;filter: invert(100%);
    max-width: 200px !important;" ></img>
								  </div>
				<h3 class="section_head" style="margin-bottom: 40px;" >Por favor espere, No salgas de esta pagina hasta procesar la compra</h3>
			</div>

		   <!------------  aaa ---->


			@if($event->pre_order_display_message)
            <div class="well well-small" style="display:none">
                {!! nl2br(e($event->pre_order_display_message)) !!}
            </div>
            @endif


            <!--{!! Form::submit(trans("Public_ViewEvent.checkout_order"), ['class' => 'btn btn-lg btn-success card-submit', 'style' => 'width:100%;']) !!}-->
            {!! Form::close() !!}
			<script src="https://live.decidir.com/static/v2.5/decidir.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
			<div class="custom-container">
			<div class="custom-row">
			<div class="custom-col custom-col-md-8">
			<form id="formulario">

			 <div class="form-group">
				<label for="card_number">Numero de tarjeta:</label>
				<input class="form-control" type="text" data-decidir="card_number" placeholder="XXXXXXXXXXXXXXXX" value="" id="card_number" oninput="checkCardNumber()"/>
			 </div>

			<!-- Select para cuotas -->
			<div class="form-group" id="cuotasContainer" style="display:none;">
			<label for="installments">Seleccionar cuotas:</label>
				<select class="form-control" id="installments" data-decidir="installments" style="color: black; width: 50%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
					<option value="1">1 cuota</option>
					<option value="3">3 cuotas</option>
					<option value="6">6 cuotas</option>
				</select>
			</div>

			 <div class="form-group">
				<label for="security_code">Codigo de seguridad:</label>
			  <input class="form-control" type="text"  data-decidir="security_code" placeholder="XXX" value="" />
			  </div>
			  <div class="form-group">
				<label for="card_expiration_month">Mes de vencimiento:</label>
				<input class="form-control" type="text"  data-decidir="card_expiration_month" placeholder="MM" value=""/>
			  </div>
			  <div class="form-group">
				<label for="card_expiration_year">Año de vencimiento:</label>
				<input class="form-control" type="text"  data-decidir="card_expiration_year" placeholder="AA" value=""/>
			  </div>
			  <div class="form-group">
				<label for="card_holder_name">Nombre del titular:</label>
				<input class="form-control" type="text" data-decidir="card_holder_name" placeholder="TITULAR" value=""/>
			 </div>

		<!--	  <div class="form-group">
				<label for="card_holder_doc_type">Tipo de documento:</label>
					<select class="form-control" data-decidir="card_holder_doc_type" style="color: black;width: 100%;padding: 10px;border: 1px solid #ced4da;border-radius: 5px;">
						<option value="dni">DNI</option>
					</select>
			  </div>-->
			  <div class="form-group">
				<label for="card_holder_doc_type">Numero de documento:</label>
				<input class="form-control" type="text"data-decidir="card_holder_doc_number" placeholder="XXXXXXXXXX" value=""/>
			  </div>

			 <button style="width: 100%;" class="btn btn-lg btn-success" type="button" onclick="sendFormFunc(event)">Realizar pago</button>

			</form>
			</div>
			 <div id="mpago" class="custom-col custom-col-md-4">
			 <p style="width: 100% !important;!i;!;text-align: center;font-weight: 700;font-size: medium;">Seleccione un método de pago</p>
			 <div class="custom-row" style="justify-content: space-around;align-items: flex-start;">
			 <div class="custom-col custom-col-md-4">
				<button id="a104" class="custom-button" onclick="metodopago(104)">
					<img src="{{ config('app.url') }}/recursos/public/master-card.svg" alt="Mastercard" style="width: 60px; height: auto;"> MASTERCARD
				</button>
				<button id="a1" class="custom-button" onclick="metodopago(1)">
					<img src="{{ config('app.url') }}/recursos/public/visa.svg" alt="Visa" style="width: 60px; height: auto;"> VISA
				</button>
				<button id="a111" class="custom-button" onclick="metodopago(111)">
					<img src="{{ config('app.url') }}/recursos/public/american-express.svg" alt="American Express" style="width: 60px; height: auto;"> AMERICAN EXPRESS
				</button>
				<button id="a63" class="custom-button" onclick="metodopago(63)">
					<img src="{{ config('app.url') }}/recursos/public/cabal_.png" alt="Cabal" style="width: 60px; height: auto;"> CABAL
				</button>
				<button id="a106" class="custom-button" onclick="metodopago(106)">
					<img src="{{ config('app.url') }}/recursos/public/maestro.svg" alt="Maestro" style="width: 60px; height: auto;"> MAESTRO
				</button>
			</div>
			<div class="custom-col custom-col-md-4" style="margin-top: 15px;">
				<button id="a31" class="custom-button" onclick="metodopago(31)">
					<img src="{{ config('app.url') }}/recursos/public/devisa.svg" alt="Visa debito" style="width: 60px;height: auto;background: white;padding: 5px;"> VISA DÉBITO
				</button>
				<button id="a105" class="custom-button" onclick="metodopago(105)">
					<img src="{{ config('app.url') }}/recursos/public/dmastercard.png" alt="Mastercard debito" style="width: 60px;height: auto;background: white;padding: 5px;"> MASTERCARD DÉBITO
				</button>
				<button id="a108" class="custom-button" onclick="metodopago(108)">
					<img src="{{ config('app.url') }}/recursos/public/cabal_debito.png" alt="Cabal debito" style="width: 80px;height: auto;background: white;padding: 5px;"> CABAL DÉBITO
				</button>
				</div>
				 </div>
			</div>
			</div>
			</div>
		<button id="btnanterior" type="button" class="btn btn-secondary" onclick="prevPage(3)" style="margin-bottom: 20px;border: 2px solid gray;font-size: 1.2rem;font-weight: 500;color: gray;">Anterior</button>
        </div>


    </div>
</section>

</div>


    <script src="https://cdn.tailwindcss.com"></script>



	<script>
var bines = @json($event->location_country);
var binesArray = bines.split(';');
//console.log(bines);
function checkCardNumber() {
    const cardNumber = document.getElementById('card_number').value;
    const cuotasContainer = document.getElementById('cuotasContainer');


    if (binesArray && binesArray.length > 0) {

        if (cardNumber && cardNumber.trim() !== '') {

            if (binesArray.some(bine => cardNumber.startsWith(bine))) {
                cuotasContainer.style.display = 'block';
            } else {
                cuotasContainer.style.display = 'none';
            }
        } else {

            cuotasContainer.style.display = 'none';
        }
    } else {

        cuotasContainer.style.display = 'none';
    }
}

    function sendFormFunc(event) {
        // Implementación de la lógica de envío del formulario
        event.preventDefault();
        alert('Pago enviado');
    }
</script>


<script>
    function nextPage(current) {
        document.getElementById('page' + current).style.display = 'none';
        document.getElementById('page' + (current + 1)).style.display = 'block';
    }

    function prevPage(current) {
        document.getElementById('page' + current).style.display = 'none';
        document.getElementById('page' + (current - 1)).style.display = 'block';
    }
</script>


<script type="text/javascript">
let selectedPaymentMethod = 0;
let miip="192.168.0.102";
/*$(document).ready(function() {
    // Verificar el estado al cargar la página
    const divVisible = localStorage.getItem('divVisible');
    if (divVisible === 'true') {
        console.log('El div estaba visible antes de salir.');
        //$('#pagoexito').show(); // Muestra el div si estaba visible
		localStorage.setItem('divVisible', 'false');
		window.location.href = '{{ route("personas.miscompras") }}';
    } else {
        console.log('El div no estaba visible antes de salir.');
    }
});*/

const publicApiKey = "{{ config('decidir.decidir_public_key') }}";

//cambiar url
const urlSandbox = "{{ config('decidir.url_payway') }}";

const decidirSandbox = new Decidir(urlSandbox);
decidirSandbox.setPublishableKey(publicApiKey);
decidirSandbox.setTimeout(5000);//se configura sin timeout

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
//funcion para manejar la respuesta
function sdkResponseHandler(status, response) {
if(selectedPaymentMethod > 0){
	//console.log(status);
	if (status == '200' ||status == '201') {
		//console.log('OK - Respuesta: '+response );
		//console.log('OK - Respuesta:', JSON.stringify(response, null, 2));
		//console.log('TOKEN: '+response.id );

		/*alert("Token de pago: "+response.id);

    /*$.ajax('form_action.php',
    {
        method  : 'POST',
        data    :
        {
              token      : response.id
        },
        success : function (response)
        {
              var res = JSON.parse(response);
              if(res["status"]=="approved"){
                console.log('ok pagado'+response+' - Estado: '+res["status"]);
              }else{
                console.log('Error al pagar'+response+' - Estado: '+res["status"]);
              }
        },
        error   : function (e, status)
        {
              console.log('Error pagado'+response);
        }
    });
    */
	const token = response.id;
	var grandTotal = @json($orderService->grandTotal);
	var siteidtransaction = 'IDT_'+@json($reserved_tickets_id);
	var nombrecli = document.getElementById('order_first_name').value;
	var emailcli = document.getElementById('order_email').value;

	var installments = parseInt(document.getElementById("installments").value);

		const dataToSend = {
            site_transaction_id: siteidtransaction, // Cambia este ID según sea necesario
            token: token,
            customer: {
                id: nombrecli,
                email: emailcli, // Cambia al email real
                ip_address: miip // Cambia a la IP real
            },
            payment_method_id: selectedPaymentMethod, // Cambia según tu método de pago
            bin: response.bin,
            amount: grandTotal, // Cambia este monto según sea necesario
            currency: "ARS",
            installments: installments,
            description: "",
            establishment_name: "ALLTICKETS",
            payment_type: "single",
            sub_payments: []
        };
		//console.log(JSON.stringify(dataToSend));
        $.ajax('/procesarpago', {
            method: 'POST',
            data: JSON.stringify(dataToSend), // Enviar los datos en formato JSON
            contentType: 'application/json',
            success: function (response) {
                     if (response.status === 'approved') {

							document.getElementById('btnanterior').style.display = 'none';

                            $('#formulario').hide();
							$('#mpago').hide();
							$('#business_address_line1').val(response.transaction_id);


                            $('#pagoexito').show();

                             const continuarBtn = $('<button>', {
                                type: 'button',
                                class: 'btn btn-lg btn-success card-submit',
                                style: 'width:100%;display:none',
                                text: 'Continuar',
                                click: function() {

                                    $('#formularioproceso').submit();
                                }
                            });


                            $('#pagoexito').append(continuarBtn);
							 //$('#idtransaccion').text('ID de transacción: ' + response.transaction_id);
							continuarBtn.click();

							 //console.log('Respuesta del servidor:', response);
                             //alert('Transacción exitosa. ID de transacción: ' + response.transaction_id);
                        } else if (response.status === 'rejected')

							{
								//console.log('Respuesta del servidor:', response);
								alert('Su pago ha sido rechazado');
							}

						else {
                           // console.error('Error en la transacción:', response.status_details);
							//console.log('Respuesta del servidor:', response);
							//console.error('ERROR - STATUS: ${error.status} - Respuesta: ${JSON.stringify(error.response)}');
                            alert('Hubo un problema con la transacción. Por favor, revisa los detalles.');
                        }
            },
            error: function (e) {
                console.log('Error al procesar el pago', e);
            }
        });



  }else {
      //console.log('ERROR - STATUS: ' + status + ' - Respuesta: ' +JSON.stringify(response) );
	  alert('Respuesta:' + response.error[0].error.message);
  }
}
else
{
	alert("Elija un método de pago");
}
}


//funcion de invocacion con sdk
function sendFormFunc(event) {
  event.preventDefault();
  var form=document.querySelector('#formulario');
  decidirSandbox.createToken(form, sdkResponseHandler);//formulario y callback
  return false;
}


</script>




<script>
const ticketOrder = {!! json_encode(session()->get('ticket_order_' . $event_id)) !!};
    //console.log(ticketOrder);
</script>
<script>
    var grandTotal = @json($reserved_tickets_id);
    //console.log(grandTotal);
</script>
<script>
var OrderExpires = {{ strtotime($expires) }};
var countdownTime = OrderExpires - Math.floor(Date.now() / 1000); // Tiempo restante en segundos

function setCountdown(element, duration) {
    var timer = duration, seconds, minutes;

    var interval = setInterval(function () {
        seconds = parseInt(timer % 60, 10);
        minutes = parseInt((timer / 60) % 60, 10);

        seconds = seconds < 10 ? "0" + seconds : seconds;
        minutes = minutes < 10 ? "0" + minutes : minutes;

        element.textContent = minutes + ":" + seconds; // Actualiza el contenido del elemento

        if (--timer < 0) {
            clearInterval(interval);
            element.textContent = "EXPIRED"; // Mensaje cuando expira
	    alert("El tiempo expiró");
	    window.location.href = '{{ route("personas.dashboard") }}';
        }
    }, 1000);
}

if (document.getElementById('countdown') && countdownTime > 0) {
    setCountdown(document.getElementById('countdown'), countdownTime);
}
</script>
  <script>
 function metodopago(id) {
            // Asigna el ID a una variable
            selectedPaymentMethod = id;

            let buttons = document.querySelectorAll('.custom-button');
            buttons.forEach(button => {
                button.classList.remove('selected');
            });
            let selectedButton = document.getElementById('a'+selectedPaymentMethod);
            if (selectedButton) {
                selectedButton.classList.add('selected');
               // console.log(`Método de pago seleccionado: ${selectedPaymentMethod}`);
            } else {
                console.log(`Botón con ID ${selectedPaymentMethod} no encontrado.`);
            }
        }
    </script>

	<script>
        fetch('https://api.ipify.org?format=json')
            .then(response => response.json())
            .then(data => {
                miip=data.ip;
            })
            .catch(error => {
                console.error('Error al obtener la IP:', error);
            });
    </script>
@endsection
