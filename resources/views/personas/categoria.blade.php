@extends('personas.layouts.app')
@section('content')
<style>
    .no-events-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: auto;
        /* Ocupa toda la altura del viewport */
        background-color: transparent;
        /* Fondo gris claro */
        padding: 20px;
        box-sizing: border-box;
    }

    .no-events-message {
        text-align: center;
        background: transparent;
        /* Fondo blanco para el mensaje */
        border-radius: 8px;
        /* Bordes redondeados */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Sombra sutil */
        padding: 30px;
        max-width: 600px;
        /* Limitar el ancho máximo del contenedor */
    }

    .no-events-message p {
        font-size: 18px;
        color: #333;
        /* Texto en gris oscuro */
        margin-bottom: 20px;
    }

</style>


<div class="overflow-hidden bgMain">
    <!-- BANNER MAIN -->
    @foreach($categorias2 as $categoria)
    <div id="seccion{{ $categoria->id }}" class="pt-12 seccion-bloque">
        <div class="flex items-center w-full gap-5 pl-12" style="flex-direction: column;padding-left: 1rem !important;">
            @if($categoria->imagen)
            <img src="{{ asset('storage/' . $categoria->imagen) }}" alt="Imagen" class="img-thumbnail" style="max-width: 100px;">
            @else
            <img class="logoSection" src="{{ config('app.url') }}/recursos/public/standup.png" alt="" />
            @endif



            <span class="w-full titleSection borderSeccion" style="border-color: black;text-align: center;">{{ $categoria->descripcion }}</span>
        </div>

        <div class="flex flex-col gap-4 p-5" style="padding: 3rem;flex-wrap: wrap;flex-direction: row;justify-content: space-evenly;">

            @if(isset($geventos))
            <!-- Verifica si el evento pertenece a la categoría actual -->
            @foreach($geventos as $event)

            <div class="flex flex-col animationShow" onclick="redirigirId('{{ $event->id }}')" style="margin-top: 40px;max-width: 300px;">
                <img src="{{ asset($event->img_mini) }}" alt="" />
                <div class="containerDescription">
                    <div class="flex flex-col text-justify">
                        <span>{{ $event->title }}</span>

                        <p class="text-sm">Funciones {{ count($event->event) }}</p>
                        {{-- <p class="text-sm">{{ $event->teatro->nombre }}</p> --}}
                    </div>
                    <div class="flex flex-col justify-between">
                        <div class="containerDuracion">
                            <img src="{{ config('app.url') }}/recursos/public/reloj.png" alt="">
                            <p>{{ $event->location_post_code }} min.</p>
                        </div>
                        <a onclick="redirigirId('{{ $event->id }}')" style="color: #fff;background: var(--bgHeader);padding: 5px 25px;cursor: pointer;">COMPRAR</a>
                    </div>
                </div>
            </div>

            @endforeach
            @else

            <div class="no-events-container">
                <div class="flex flex-col items-center w-full gap-4 containerLogin">

                    <p class="flex gap-1 py-5 font-semibold" style="text-align: center;">Aún no hay Funciones programadas para esta categoría.</p>

                </div>
            </div>
            @endif
        </div>
    </div>
    @endforeach

    <br><br><br>
    <!--FIN SECCION -->



    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        function scrollToSection(id) {
            var section = document.getElementById(id);
            if (section) {
                section.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }


        function redirigirId(idc) {

            //let encodedTitle = encodeURIComponent(idc);
            // Convertir a minúsculas
            //  let lowerCaseString = idc.toLowerCase();

            // Reemplazar los espacios por guiones
            //   let modifiedString = lowerCaseString.replace(/ /g, '-');

            window.location.href = "{{ config('app.url') }}/evento-obras/" + idc;
        }

        function redirigir(idc) {

            //let encodedTitle = encodeURIComponent(idc);
            // Convertir a minúsculas
            let lowerCaseString = idc.toLowerCase();

            // Reemplazar los espacios por guiones
            let modifiedString = lowerCaseString.replace(/ /g, '-');

            window.location.href = "{{ config('app.url') }}/obras/" + modifiedString;
        }

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Leer el parámetro de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const sectionId = urlParams.get('page');

            if (sectionId) {
                const section = document.getElementById(sectionId);
                if (section) {
                    section.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });

    </script>
    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".slideMain", {
            speed: 2000
            , loop: true
            , autoplay: {
                delay: 4000
                , disableOnInteraction: false
            , }
            , pagination: {
                el: ".swiper-pagination"
                , clickable: true
            , }
        , });
        var swiper2 = new Swiper(".slideObras", {
            spaceBetween: 15
            , speed: 1000
            , loop: true
            , navigation: {
                nextEl: ".swiper-button-next"
                , prevEl: ".swiper-button-prev"
            , }
            , breakpoints: {
                300: {
                    slidesPerView: 1
                , }
                , 500: {
                    slidesPerView: 2.5
                , }
                , 900: {
                    slidesPerView: 3.5
                , }
            }
        });

    </script>
    @endsection
