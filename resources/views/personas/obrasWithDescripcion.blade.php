@extends('personas.layouts.app_basic')
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

    .containerTicketsPrecio>ul>div {
        column-gap: 3em;
        justify-content: space-between;
    }

    .max-w-xs {
        max-width: 20rem;
        /* Ancho por defecto */
    }

    @media (max-width: 600px) {
        .max-w-xs {
            max-width: 9rem !important;
            /* Ancho cuando la pantalla es menor a 600px */
        }
    }

    .text-width {
        width: auto;
        /* Ancho por defecto */
    }

    .flex-grow {
        flex-grow: 1;
        /* Permite que el li ocupe el espacio disponible */
    }

    @media (max-width: 700px) {
        .text-width {
            width: 200px;
            /* Ajusta el ancho solo para pantallas menores a 600px */
        }

        .imgRes {
            height: 100px !important;
        }
    }

    @media (max-width: 500px) {
        .tResaltado2 {
            display: flex;
            /* Usar flex para permitir que el texto se divida */
            flex-direction: column;
            /* Coloca el texto en dos líneas */
            width: 50px;
            /* Ajusta el ancho a 150px */
        }

        .imgRes {
            height: 100px !important;
        }
    }

    @media (max-width: 640px) {
        .btnComprarTicket {
            width: 100% !important;
            /* Ajusta el ancho a 150px */
        }

    }

</style>
<style>
    /* Estilos para el contenedor del modal */
    .modal {
        display: none;
        /* Oculto por defecto */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
        /* Fondo oscuro */

        justify-content: center;
        align-items: center;
    }

    /* Estilos para la imagen del modal */
    .modal-content {
        max-width: 90%;
        max-height: 80%;
        transition: transform 0.25s ease;
        /* Animación suave para zoom */
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
        top: 70px;
        /* Mover los botones un poco más abajo */
        right: 20px;
        display: flex;
        flex-direction: column;
        z-index: 2;
        /* Asegura que los botones estén por encima de la imagen */
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
<div class="flex flex-col gap-10 p-12 containerObras" style="    overflow: visible;
    padding: 0rem;
    padding-left: 10px;
    padding-right: 20px;">



    <!---- seccion entradas ---->
    <div class="flex flex-col gap-4 p-5 rounded-lg bgCardObra">
        <div class="flex gap-4">
            <div class="containerTicketsPrecio">
                <div class="flex flex-col gap-4" style="gap: 2px ;   align-self: center;">

                    {{-- <span class="textoResaltado tituloSeccionObra"  style="font-size: 30px">{{ $event->title }}</span> --}}
                      <span  style="color: white"> {{ $event->title }} {{ $event->startDateFormatted() }}</span>
                      <span style="color: white">Duracion:{{ $event->location_post_code }} min</span>

               </div>

                <ul class="flex flex-col gap-10 pt-3" style="    padding: 5px;
                border-radius: 10px;
                border: 1px white solid;">

                    @if($event->end_date->isPast())
                    <div class="alert alert-boring" style="
                    color: #fff !important;

                    text-align: center;
                    padding: 10px;
                    margin-bottom: 20px;



                    font-size: larger;
                ">>
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


                    <form target='_parent' id="ticketForm" action="{{ route('personas.postValidateOrder2', ['event_id' => $event->id]) }}" method="POST">
                        @csrf
                        @foreach($tickets as $ticket)
                        {!! Form::hidden('tickets[]', $ticket->id) !!}
                        <input type="hidden" id="ticket_{{$ticket->id}}" name="ticket_{{$ticket->id}}" value="0" />
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
            <span class="textoResaltado tituloSeccionObra">Lugar:</span>
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
            const initialLocation = isNaN(lat) || isNaN(lng) ? {
                lat: -34.9075
                , lng: -56.1659
            } : {
                lat
                , lng
            };

            map = new google.maps.Map(document.getElementById('map'), {
                center: initialLocation
                , zoom: 12
            , });

            marker = new google.maps.Marker({
                position: initialLocation
                , map: map
                , icon: {
                    url: '{{ config("app.url") }}/recursos/public/iconomapa.png', // Reemplaza con la ruta a tu ícono PNG
                    scaledSize: new google.maps.Size(50, 70), // Tamaño reducido del ícono (anchura, altura)
                }
                , draggable: false, // Hacer que el marcador no sea movible
            });

            geocoder = new google.maps.Geocoder();

            // Puedes agregar funcionalidad adicional aquí si lo necesitas
            // Por ejemplo, para obtener una nueva dirección si haces clic en el mapa
        }

    </script>

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
                '![Amazing Image]({{ url('
                storage / ' . $event->imagengrande) }})'
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
            var url2 = 'https://api.whatsapp.com/send?text=' + text + ' link: ' + url;


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
                'http://www.linkedin.com/shareArticle?mini=true&amp;url=' + url + '?title={{urlencode($event->title)}}&amp;summary={{{Str::words(md_to_str($event->description), 20)}}}'
                , 'facebook-share-popup'
                , `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`
            );
        }

        function openPopup3(url) {
            const width = 600;
            const height = 400;
            const left = (window.innerWidth / 2) - (width / 2);
            const top = (window.innerHeight / 2) - (height / 2);

            window.open(
                'http://twitter.com/intent/tweet?text=Check out: ' + url + ' {{{Str::words(md_to_str($event->description), 20)}}}'
                , 'facebook-share-popup'
                , `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`
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
