@extends('personas.layouts.app')
@section('content')
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta property="og:title" content="{{ $event->title }}" />
    <meta property="og:description" content="Mira esta Funcion" />
    <meta property="og:image" content="{{ url('storage/' . $event->imagengrande) }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8ai2stqy3sqZyUkhPZvIMoBQyMtY0vBI&callback=initMap" async defer></script>

<style>
@media (min-width: 800px) {
       .slideMain {

       object-fit: cover;
    }
 }
@media (min-width: 600px) and (max-width: 799px) {
    .slideMain {

        object-fit: cover;
    }
}
.containerTicketsPrecio > ul > div {
    column-gap: 3em;
    justify-content: space-between;
}
.max-w-xs {
    max-width: 20rem; /* Ancho por defecto */
}

@media (max-width: 600px) {
    .max-w-xs {
        max-width: 9rem !important; /* Ancho cuando la pantalla es menor a 600px */
    }
}
.text-width {
    width: auto; /* Ancho por defecto */
}

.flex-grow {
    flex-grow: 1; /* Permite que el li ocupe el espacio disponible */
}

@media (max-width: 700px) {
    .text-width {
        width: 200px; /* Ajusta el ancho solo para pantallas menores a 600px */
    }
	.imgRes
	{
	    height: 100px !important;
	}
}
@media (max-width: 500px) {
    .tResaltado2 {
        display: flex; /* Usar flex para permitir que el texto se divida */
        flex-direction: column; /* Coloca el texto en dos líneas */
        width: 50px; /* Ajusta el ancho a 150px */
    }
    .imgRes
	{
	    height: 100px !important;
	}
}
@media (max-width: 640px) {
    .btnComprarTicket {
        width: 100% !important; /* Ajusta el ancho a 150px */
    }

}
</style>
<style>
        /* Estilos para el contenedor del modal */
        .modal {
            display: none ; /* Oculto por defecto */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8); /* Fondo oscuro */

            justify-content: center;
            align-items: center;
        }

        /* Estilos para la imagen del modal */
        .modal-content {
            max-width: 90%;
            max-height: 80%;
            transition: transform 0.25s ease; /* Animación suave para zoom */
        }

        /* Estilos para el botón de cerrar */
        .close {
            position: absolute;
            top: 10px;
            right: 25px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
        }

        /* Controles de zoom */
        .zoom-controls {
            position: absolute;
            top: 70px; /* Mover los botones un poco más abajo */
            right: 20px;
            display: flex;
            flex-direction: column;
            z-index: 2; /* Asegura que los botones estén por encima de la imagen */
        }

        .zoom-controls button {
            background-color: rgba(255, 255, 255, 0.7);
            border: none;
            border-radius: 5px;
            padding: 10px;
            margin: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        .zoom-controls button:hover {
            background-color: rgba(255, 255, 255, 0.9);
        }
    </style>

    <div class="overflow-hidden bgMainObras">
      <!-- BANNER MAIN -->
      <div class="swiper slideMain">
        <div class="swiper-wrapper">
          {{-- <div class="swiper-slide">
                <img style="object-fit: fill;" src="{{ url('storage/' . $event->imagengrande) }}" alt="" />
          </div> --}}
        </div>
        <div class="swiper-pagination"></div>
      </div>
      <!-- BANNER MAIN -->
      <div class="flex flex-col gap-10 p-12 containerObras">
        <span class="font-bold textoResaltado tituloSeccionObra">{{ $event->title }}</span>
        <ul class="flex justify-end gap-4 text-white containerSubtitleObra">
            <li class="flex items-center gap-2 font-bold text-end">
                <div class="flex items-center gap-2">
                    <img src="{{ config('app.url') }}/recursos/public/obrasPage/iconocategoria.png" alt="">
                    <span class="textoResaltadoGold">Categoria: </span>
                </div>
                <span>{{ $categoria2->descripcion }}</span>
            </li>
            <li class="flex items-center gap-2 font-bold text-end" style="display:none">
                <div class="flex items-center gap-2">
                    <img src="{{ config('app.url') }}/recursos/public/obrasPage/icono2.png" alt="">
                    <span class="textoResaltadoGold">Estreno: </span>
                </div>
                <span>{{ $event->formatted_date }}</span>
            </li>
            <li class="flex items-center gap-2 font-bold text-end">
                <div class="flex items-center gap-2">
                    <img src="{{ config('app.url') }}/recursos/public/obrasPage/iconoreloj.png" alt="">
                    <span class="textoResaltadoGold">
                        Tiempo de Función: </span>
                </div>
                <span>{{ $event->location_post_code }} min.</span>
            </li>
        </ul>


<!---- seccion entradas ---->
 <div class="flex flex-col gap-4 p-5 rounded-lg bgCardObra">
          <div class="flex gap-4">
            <div class="containerTicketsPrecio">
                    <div class="flex flex-col gap-4">
                      <span class="textoResaltado tituloSeccionObra">Tickets en Venta:</span>
                        <div style="display: flex;justify-content: center;"><img class="imgRes" src="{{ config('app.url') }}/recursos/public/obrasPage/iconoticket.png" alt=""></div>
                    </div>

<ul class="flex flex-col gap-10 pt-3">

@if($event->end_date->isPast())
	<div class="alert alert-boring" style="
    color: #fff !important;
    border-color: #ccc;
    text-align: center;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid wheat;
    border-radius: 0;
    border-left-width: 4px;
    font-size: larger;
">
            @lang("Public_ViewEvent.event_already", ['started' => trans('Public_ViewEvent.event_already_ended')])
        </div>
@else

@if($tickets->isEmpty())
    <p class="text-white" style="font-size: xx-large;text-align: center;">No hay entradas disponibles.</p>
@else
@foreach($tickets->where('is_hidden', false) as $ticket)
           <div class="flex items-center gap-2">
 <li class="flex-grow">
            <p class="font-bold textoResaltado tResaltado2 text-width">{{$ticket->title}}</p>
            <p class="text-white textDescripcionPlateas text-width">{{$ticket->description}}</p>
			<p class="text-white tResaltado2 text-width">Disponibles: {{$ticket->quantity_remaining}}</p>
        </li>
        <label class="flex flex-col items-center justify-center" style="width: 100px !important;">
            <p class="text-white">Precio:</p>
            <p class="px-2 bg-gray-200 border w-max">{{money($ticket->price, $event->currency)}}</p>
        </label>
        <div style="width: 100px !important;">


				@if ($ticket->quantity_remaining > 0)
				<label for="numero{{ $ticket->id }}" class="text-white">Cantidad:</label>
                <select id="numero{{ $ticket->id }}" name="ticketsz_{{$ticket->id}}" class="cursor-pointer w-min" onchange="actualizarValor({{ $ticket->id }})">
				<option value="0">0</option>
					@php
						$availableQuantity = $ticket->quantity_remaining;
					@endphp
					@if ($ticket->max_per_person > $availableQuantity)
						@php
						$ticket->max_per_person = $availableQuantity;
						@endphp
					@endif

                    @for ($i = $ticket->min_per_person; $i <= $ticket->max_per_person; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
				@else
					@if ($ticket->quantity_sold > 0)
						<p style="width: 100px !important;" class="text-white">Agotado</p>
					@else
						<p style="width: 100px !important;" class="text-white">Próximamente</p>
					@endif
					<!--<select  name="sinentradas" class="cursor-pointer w-min" onclick="sinEntradas()">
					<option value="0">0</option>
					</select>-->
				@endif

        </div>


    </div>
@endforeach
@endif
@endif
</ul>




                    <div class="flex flex-col items-center justify-between gap-4 pt-3" style="justify-content: flex-start !important;">
                        <p class="text-2xl textoResaltado">Abona con:</p>
                        <img src="{{ config('app.url') }}/recursos/public/obrasPage/logo-payway.svg" alt="bancolanacion" style="filter: invert(50%);max-width: 60%;margin-bottom: 20px;">

<form id="ticketForm" action="{{ route('personas.postValidateOrder2', ['event_id' => $event->id]) }}" method="POST">
@csrf
@foreach($tickets as $ticket)
{!! Form::hidden('tickets[]', $ticket->id) !!}
<input type="hidden" id="ticket_{{$ticket->id}}" name="ticket_{{$ticket->id}}" value="0"/>
@endforeach

{!! Form::text("order_first_name", Auth::guard('clientes')->user() ? Auth::guard('clientes')->user()->nombres : 'Usuario invitado', ['required' => 'required', 'class' => 'form-control', 'style' => 'display: none;']) !!}

{!! Form::text("order_last_name", Auth::guard('clientes')->user() ? Auth::guard('clientes')->user()->id : 'Invitado', ['required' => 'required', 'class' => 'form-control', 'style' => 'display: none;']) !!}
{!! Form::text("order_email", Auth::guard('clientes')->user() ? Auth::guard('clientes')->user()->email : 'invitado@dominio.com', ['required' => 'required', 'class' => 'form-control', 'style' => 'display: none;']) !!}

{!! Form::text("ticket_holder_first_name", Auth::guard('clientes')->user() ? Auth::guard('clientes')->user()->nombres : 'Usuario invitado', ['required' => 'required', 'class' => 'form-control', 'style' => 'display: none;']) !!}
{!! Form::text("ticket_holder_last_name", Auth::guard('clientes')->user() ? Auth::guard('clientes')->user()->id : 'Invitado', ['required' => 'required', 'class' => 'form-control', 'style' => 'display: none;']) !!}
{!! Form::text("ticket_holder_email", Auth::guard('clientes')->user() ? Auth::guard('clientes')->user()->email : 'invitado@dominio.com', ['required' => 'required', 'class' => 'form-control', 'style' => 'display: none;']) !!}

<button style="width: 100%;" class="w-2/4 px-8 py-5 text-2xl text-white btnComprarTicket">COMPRAR TICKETS</button>
{!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

<!--- seccion entradas --->

        <div class="flex flex-col gap-4 p-5 rounded-lg bgCardObra">
            <span class="textoResaltado tituloSeccionObra">Descripción:</span>
            <div class="flex gap-4">
                <div class="containerSinopsis">

					<div class="text-white content event_details text-size-normal" property="description">
                {!! md_to_html($event->description) !!}
            </div>
                    <div class="flex flex-col items-center justify-between">
@if($image_path)
        <div class="event-image">
            <!-- Usa la función asset para generar la URL completa de la imagen -->
            <img id="myImg" src="{{ url('storage/' . $event->imagenminiatura) }}" alt="Imagen del Evento" style="width: 400px; height: auto;cursor: pointer;">
        </div>
    @else
        <p>No hay imagen disponible para este evento.</p>
    @endif
                    </div>
                </div>
            </div>
        </div>

<div id="myModal" class="modal">
        <span class="close">&times;</span>
        <div class="zoom-controls">
            <button id="zoomIn">+</button>
            <button id="zoomOut">-</button>
        </div>
        <img class="modal-content" id="img01">
    </div>
        <div class="flex flex-col gap-4 p-5 rounded-lg bgCardObra">
            <div class="flex justify-between containerLugarObras">
                <span class="textoResaltado tituloSeccionObra" >Lugar:</span>
                <div class="flex flex-col items-center gap-4" style="width: 90%;">
				@if($teatro->imagen)
					<img src="{{ asset('storage/' . $teatro->imagen) }}" alt="Imagen del Teatro" style="width: 100px;">
					@endif
                <span class="text-white text-size-normal">{{ $teatro->nombre }}</span></div>
                <div class="flex items-center gap-4" style="justify-content: flex-end;">

                    <img src="{{ config('app.url') }}/recursos/public/obrasPage/ubicacionLugar.png" alt="">
                    <span class="flex gap-4 text-white">{{ $teatro->direccion }}</span>
					<input class="form-control" type="hidden" name="coordenadas" id="coordenadas" class="form-control" value="{{ $teatro->coordenadas }}" readonly>
                </div>
            </div>
            <div id="map" style="height: 600px; width: 100%;"></div>
        </div>

<script>
document.getElementById('ticketForm').addEventListener('submit', function(event) {
    let isValid = false;

    // Usamos un bloque para evitar redeclaración de variables
    @foreach($tickets as $ticket)
        (function() {
            let ticketValue = document.getElementById('ticket_{{$ticket->id}}').value;
            if (ticketValue !== "0") {
            isValid = true; // Si hay al menos uno diferente de cero, marcamos como válido
			}
        })();
    @endforeach

    if (!isValid) {
        event.preventDefault(); // Evita que el formulario se envíe
		 alert('Por favor, selecciona al menos un ticket para comprar.');
    }
});
</script>

<script>

    function actualizarValor(ticketId) {
        // Obtener el valor seleccionado del select
        const select = document.getElementById(`numero${ticketId}`);
        const valorSeleccionado = select.value;

        // Asignar el valor al input oculto
        const inputOculto = document.getElementById(`ticket_${ticketId}`);
        if (inputOculto) {
            inputOculto.value = valorSeleccionado;
        } else {
            console.error(`Input no encontrado para ticketId: ${ticketId}`);
        }
    }

    // Aquí puedes agregar otros manejadores de eventos si es necesario
</script>
		<script>
  let map;
        let marker;
        let geocoder;

        function initMap() {
            // Obtener coordenadas desde el campo del formulario
            const coordenadas = document.getElementById('coordenadas').value;
            const [lat, lng] = coordenadas.split(',').map(Number);

            // Ubicación inicial en Uruguay (Montevideo) si las coordenadas no son válidas
            const initialLocation = isNaN(lat) || isNaN(lng) ? { lat: -34.9075, lng: -56.1659 } : { lat, lng };

            map = new google.maps.Map(document.getElementById('map'), {
                center: initialLocation,
                zoom: 12,
            });

            marker = new google.maps.Marker({
                position: initialLocation,
                map: map,
		icon: {
                    url: '{{ config("app.url") }}/recursos/public/iconomapa.png', // Reemplaza con la ruta a tu ícono PNG
                    scaledSize: new google.maps.Size(50, 70), // Tamaño reducido del ícono (anchura, altura)
                },
                draggable: false, // Hacer que el marcador no sea movible
            });

            geocoder = new google.maps.Geocoder();

            // Puedes agregar funcionalidad adicional aquí si lo necesitas
            // Por ejemplo, para obtener una nueva dirección si haces clic en el mapa
        }


</script>
        <div class="flex flex-col items-center gap-4 p-5 rounded-lg bgCardObra">
            <span class="textoResaltado tituloSeccionObra">Compartir Evento:</span>
            <ul class="flex items-center gap-1 containerEnlacesObra">
@if ($event->social_show_facebook == 1)
                 <li id="facebookShare" class="flex gap-1 px-5 py-2 bgFacebook">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>facebook</title> <desc>Created with sketchtool.</desc> <g id="brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="facebook" fill="#ffffff"> <path d="M15.7246987,22.0001499 L15.7246987,14.2550985 L18.3244671,14.2550985 L18.7136868,11.2366604 L15.7246987,11.2366604 L15.7246987,9.30952104 C15.7246987,8.43561277 15.9673427,7.84009322 17.220579,7.84009322 L18.8189724,7.83934386 L18.8189724,5.13968519 C18.5424571,5.10296636 17.5936874,5.02076113 16.4898742,5.02076113 C14.1853552,5.02076113 12.6077192,6.42739225 12.6077192,9.01067469 L12.6077192,11.2366604 L10.0013563,11.2366604 L10.0013563,14.2550985 L12.6077192,14.2550985 L12.6077192,22.0001499 L3.10381314,22.0001499 C2.49405567,22.0001499 2,21.5058694 2,20.8963367 L2,3.10388807 C2,2.49413061 2.49405567,2 3.10381314,2 L20.8961869,2 C21.5057195,2 22,2.49413061 22,3.10388807 L22,20.8963367 C22,21.5058694 21.5057195,22.0001499 20.8961869,22.0001499 L15.7246987,22.0001499 Z" id="Shape"> </path> </g> </g> </g></svg>
                        <span class="font-bold text-white">FACEBOOK</span>
                </li>
                    @else

                    @endif

@if ($event->social_show_linkedin == 1)
                      <li onclick="openPopup2('{{ url()->current() }}'); return false;" class="flex gap-1 px-5 py-2 bgLinkedIn">
                        <svg fill="#ffffff" width="24px" height="24px" viewBox="-3.5 0 19 19" xmlns="http://www.w3.org/2000/svg" class="cf-icon-svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M3.335 6.498a1.152 1.152 0 0 1-1.248 1.148h-.015a1.15 1.15 0 1 1 .03-2.295 1.147 1.147 0 0 1 1.233 1.147zM.982 8.553h2.206v6.637H.982zm10.165 2.83v3.807H8.941v-3.55c0-.893-.319-1.502-1.12-1.502a1.21 1.21 0 0 0-1.13.807 1.516 1.516 0 0 0-.073.538v3.708H4.41s.03-6.017 0-6.639h2.21v.94l-.016.023h.015V9.49a2.19 2.19 0 0 1 1.989-1.095c1.451 0 2.54.949 2.54 2.988z"></path></g></svg>
                        <span class="font-bold text-white">LINKEDIN</span>
                </li>
                    @else

                    @endif
@if ($event->social_show_twitter == 1)
                       <li onclick="openPopup3('{{ url()->current() }}'); return false;" class="flex gap-1 px-5 py-2 bgTwitter">
                        <svg fill="#ffffff" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 512 512" xml:space="preserve" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="7935ec95c421cee6d86eb22ecd12f847"> <path style="display: inline;" d="M459.186,151.787c0.203,4.501,0.305,9.023,0.305,13.565 c0,138.542-105.461,298.285-298.274,298.285c-59.209,0-114.322-17.357-160.716-47.104c8.212,0.973,16.546,1.47,25.012,1.47 c49.121,0,94.318-16.759,130.209-44.884c-45.887-0.841-84.596-31.154-97.938-72.804c6.408,1.227,12.968,1.886,19.73,1.886 c9.55,0,18.816-1.287,27.617-3.68c-47.955-9.633-84.1-52.001-84.1-102.795c0-0.446,0-0.882,0.011-1.318 c14.133,7.847,30.294,12.562,47.488,13.109c-28.134-18.796-46.637-50.885-46.637-87.262c0-19.212,5.16-37.218,14.193-52.7 c51.707,63.426,128.941,105.156,216.072,109.536c-1.784-7.675-2.718-15.674-2.718-23.896c0-57.891,46.941-104.832,104.832-104.832 c30.173,0,57.404,12.734,76.525,33.102c23.887-4.694,46.313-13.423,66.569-25.438c-7.827,24.485-24.434,45.025-46.089,58.002 c21.209-2.535,41.426-8.171,60.222-16.505C497.448,118.542,479.666,137.004,459.186,151.787z"> </path> </g> </g></svg>
                        <span class="font-bold text-white">TWITTER</span>
                </li>
                    @else

                    @endif
@if ($event->social_show_whatsapp == 1)
 		<li id="whatsappShare" class="flex gap-1 px-5 py-2 bgWhatsapp">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M2 22L3.41152 16.8691C2.54422 15.3639 2.08876 13.6568 2.09099 11.9196C2.08095 6.44549 6.52644 2 11.99 2C14.6417 2 17.1315 3.02806 19.0062 4.9034C19.9303 5.82266 20.6627 6.91616 21.1611 8.12054C21.6595 9.32492 21.9139 10.6162 21.9096 11.9196C21.9096 17.3832 17.4641 21.8287 12 21.8287C10.3368 21.8287 8.71374 21.4151 7.26204 20.6192L2 22ZM7.49424 18.8349L7.79675 19.0162C9.06649 19.7676 10.5146 20.1644 11.99 20.1654C16.5264 20.1654 20.2263 16.4662 20.2263 11.9291C20.2263 9.73176 19.3696 7.65554 17.8168 6.1034C17.0533 5.33553 16.1453 4.72636 15.1453 4.31101C14.1452 3.89565 13.0728 3.68232 11.99 3.68331C7.44343 3.6839 3.74476 7.38316 3.74476 11.9202C3.74476 13.4724 4.17843 14.995 5.00502 16.3055L5.19645 16.618L4.35982 19.662L7.49483 18.8354L7.49424 18.8349Z" fill="#ffffff"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M9.52024 7.76662C9.33885 7.35303 9.13737 7.34298 8.96603 7.34298C8.81477 7.33294 8.65288 7.33294 8.48154 7.33294C8.32083 7.33294 8.04845 7.39321 7.81684 7.64549C7.58464 7.89719 6.95007 8.49217 6.95007 9.71167C6.95007 10.9318 7.83693 12.1111 7.95805 12.2724C8.07858 12.4337 9.67149 15.0139 12.192 16.0124C14.2883 16.839 14.712 16.6777 15.1657 16.6269C15.6189 16.5767 16.6275 16.0325 16.839 15.4476C17.0405 14.8733 17.0405 14.3693 16.9802 14.2682C16.9199 14.1678 16.748 14.1069 16.5064 13.9758C16.2541 13.8552 15.0446 13.2502 14.813 13.1693C14.5808 13.0889 14.4195 13.0487 14.2582 13.2904C14.0969 13.5427 13.623 14.0969 13.4724 14.2582C13.3306 14.4195 13.1799 14.4396 12.9377 14.3185C12.686 14.1979 11.8895 13.9356 10.9418 13.0889C10.2056 12.4331 9.71167 11.6171 9.56041 11.3755C9.41979 11.1232 9.54032 10.992 9.67149 10.8709C9.78257 10.7604 9.92378 10.579 10.0449 10.4378C10.1654 10.296 10.2056 10.1855 10.2966 10.0242C10.377 9.86292 10.3368 9.71167 10.2765 9.59114C10.2157 9.48006 9.74239 8.25997 9.52024 7.76603V7.76662Z" fill="#ffffff"></path> </g></svg>
                        <span class="font-bold text-white">WHATSAPP</span>
                </li>
                    @else

                    @endif

@if ($event->social_show_email == 1)
                   <li id="emailShare" class="flex gap-1 px-5 py-2 bgEmail">
                        <svg fill="#ffffff" height="24px" width="24px" version="1.1" id="Icons" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M26.2,8.5c-2.2-3.2-5.6-5.2-9.3-5.5c-0.3,0-0.6,0-0.9,0c-3.7,0-7.2,1.6-9.7,4.3c-2.5,2.7-3.6,6.4-3.2,10.1 c0.7,6,5.5,10.8,11.5,11.4C15,29,15.5,29,16,29c2.6,0,5-0.7,7.2-2.2c0.5-0.3,0.6-0.9,0.3-1.4c-0.3-0.5-0.9-0.6-1.4-0.3 c-2.2,1.4-4.7,2.1-7.3,1.8c-5.1-0.5-9.1-4.6-9.7-9.7C4.7,14.1,5.7,11,7.8,8.7c2.3-2.5,5.6-3.9,9-3.6c3.2,0.2,6,1.9,7.8,4.6 c1.9,2.9,2.4,6.3,1.3,9.6l0,0.1c-0.3,1-1.3,1.7-2.4,1.7c-1.4,0-2.5-1.1-2.5-2.5V17v-2v-4c0-0.6-0.4-1-1-1s-1,0.4-1,1v0 c-0.8-0.6-1.9-1-3-1c-2.8,0-5,2.2-5,5v2c0,2.8,2.2,5,5,5c1.4,0,2.6-0.6,3.5-1.4c0.7,1.4,2.2,2.4,4,2.4c1.9,0,3.6-1.2,4.3-3.1l0-0.1 C29.1,16,28.5,11.9,26.2,8.5z M19,17c0,1.7-1.3,3-3,3s-3-1.3-3-3v-2c0-1.7,1.3-3,3-3s3,1.3,3,3V17z"></path> </g></svg>
                        <span class="font-bold text-white">EMAIL</span>
                </li>
                    @else

                    @endif


            </ul>
        </div>
      </div>


    </div>
<div>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->






    <script src="https://cdn.tailwindcss.com"></script>



<script>
        document.getElementById('emailShare').addEventListener('click', function() {
            var subject = encodeURIComponent('Mira esta Funcion!');
            var body = encodeURIComponent(
                'Hola,\n\n' +
                'Mira esta Funcion:\n\n' +
                'Title: {{ $event->title }}\n' +
                'Link: {{ url()->current() }}\n\n' +
                '![Amazing Image]({{ url('storage/' . $event->imagengrande) }})'
            );

            // Construir el enlace mailto
            var mailtoUrl = `mailto:?subject=${subject}&body=${body}`;

            // Abre el cliente de correo
            window.location.href = mailtoUrl;
        });
    </script>


<script>
        document.getElementById('facebookShare').addEventListener('click', function() {
            var url = encodeURIComponent('{{ url()->current() }}'); // URL del contenido que quieres compartir

            // Construir el enlace de compartir de Facebook
            var shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;

            // Abre el popup de Facebook
            window.open(shareUrl, 'facebookSharePopup', 'width=600,height=400,scrollbars=yes');
        });
    </script>
<script>
        document.getElementById('whatsappShare').addEventListener('click', function() {
            var text = encodeURIComponent('Mira esta Funcion '); // Cambia el texto aquí
            var url = '{{ url()->current() }}';
	    var url2 = 'https://api.whatsapp.com/send?text='+text+' link: '+url;


            // Abre una ventana emergente
            window.open(url2, 'whatsappSharePopup', 'width=600,height=400,scrollbars=yes');
        });
    </script>
 <script>

function openPopup2(url) {
            const width = 600;
            const height = 400;
            const left = (window.innerWidth / 2) - (width / 2);
            const top = (window.innerHeight / 2) - (height / 2);

            window.open(
                'http://www.linkedin.com/shareArticle?mini=true&amp;url='+url+'?title={{urlencode($event->title)}}&amp;summary={{{Str::words(md_to_str($event->description), 20)}}}',
                'facebook-share-popup',
                `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`
            );
        }
function openPopup3(url) {
            const width = 600;
            const height = 400;
            const left = (window.innerWidth / 2) - (width / 2);
            const top = (window.innerHeight / 2) - (height / 2);

            window.open(
                'http://twitter.com/intent/tweet?text=Check out: '+url+' {{{Str::words(md_to_str($event->description), 20)}}}',
                'facebook-share-popup',
                `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`
            );
        }

    </script>


  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDf74Ze1MYbnlYrCTwc7x7a6UnrmrqsUHs&callback=initMap"></script>
  <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener los elementos
            var modal = document.getElementById('myModal');
            var img = document.getElementById('myImg');
            var modalImg = document.getElementById('img01');
            var span = document.getElementsByClassName('close')[0];
            var zoomInBtn = document.getElementById('zoomIn');
            var zoomOutBtn = document.getElementById('zoomOut');
            var scale = 1; // Escala inicial

            // Cuando el usuario hace clic en la imagen, mostrar el modal
            img.onclick = function() {
                modal.style.display = 'flex';
                modalImg.src = this.src;
                scale = 1; // Reiniciar escala
                modalImg.style.transform = `scale(${scale})`;
            }

            // Cuando el usuario hace clic en (x), cerrar el modal
            span.onclick = function() {
                modal.style.display = 'none';
            }

            // Cuando el usuario hace clic en cualquier parte del modal, cerrarlo
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            // Control de zoom con botones
            zoomInBtn.onclick = function() {
                scale += 0.1; // Incrementar la escala
                modalImg.style.transform = `scale(${scale})`;
            }

            zoomOutBtn.onclick = function() {
                scale -= 0.1; // Decrementar la escala
                if (scale < 0.1) scale = 0.1; // Evitar escala negativa o cero
                modalImg.style.transform = `scale(${scale})`;
            }

            // Control de zoom con la rueda del mouse
            modalImg.onwheel = function(event) {
                event.preventDefault();
                if (event.deltaY < 0) {
                    scale += 0.1; // Zoom in
                } else {
                    scale -= 0.1; // Zoom out
                    if (scale < 0.1) scale = 0.1; // Evitar escala negativa o cero
                }
                modalImg.style.transform = `scale(${scale})`;
            }
        });
    </script>
@endsection
