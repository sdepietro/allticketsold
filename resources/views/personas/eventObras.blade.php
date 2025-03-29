@extends('personas.layouts.app')
@section('content')
<style>
    @media (min-width: 1300px) {
        .slideMain {

            object-fit: cover;
        }
    }

    @media (max-width: 599px) {
        .slideMain {

            object-fit: cover;
        }
    }

    @media (min-width: 600px) and (max-width: 999px) {
        .slideMain {

            object-fit: cover;
        }
    }

    @media (min-width: 1200px) {
        .swiper-slide2 {
            width: 470px !important;
        }
    }

    @media (min-width: 600px) and (max-width: 799px) {
        .swiper-slide2 {
            width: 318px !important;
            height: 220px !important;
        }
    }

    @media (min-width: 800px) and (max-width: 1199px) {
        .swiper-slide2 {
            width: 400px !important;
        }

    }

    @media screen and (max-width: 900px) {
        .btnVerObra>div>img {
            height: 39px;
        }

        .btnVerObra {
            font-size: 0.4em;
        }
    }

    @media (max-width : 600px) {
        .bannerimg {
            height: 34px !important;
            width: 85px !important;
            max-width: 120px !important;
            object-fit: contain !important;
        }

        .arrowBtnObra {
            height: 21px !important;
        }

        .bannertexto {
            font-size: 1.9em !important;
        }
    }

    @media (min-width: 601px) and (max-width: 800px) {
        .btnVerObra img {
            height: 50px;
        }

        .arrowBtnObra {
            height: 25px !important;
        }

        .bannerimg {

            width: 80px !important;
            max-width: 80px !important;
            object-fit: contain !important;
            height: 30px ! IMPORTANT;
        }

        .bannertexto {
            font-size: 3em !important;
        }
    }

    @media (min-width: 150px) and (max-width: 2500px) {

        .arrowBtnObra {
            height: 25px !important;
        }

        .bannerimg {

            width: 130px !important;
            max-width: 130px !important;
            object-fit: contain !important;
            height: 41px ! IMPORTANT;
        }

        .bannertexto {
            font-size: 2em !important;
        }


        /**
 * Oscuro: #283035
 * Azul: #03658c
 * Detalle: #c7cacb
 * Fondo: #dee1e3
 ----------------------------------*/




        ul {
            list-style-type: none;
        }



        /** ====================
 * Lista de Comentarios
 =======================*/
        .comments-container {
            margin: 60px auto 15px;
            width: 768px;
        }

        .comments-container h1 {
            font-size: 36px;
            color: #283035;
            font-weight: 400;
        }

        .comments-container h1 a {
            font-size: 18px;
            font-weight: 700;
        }

        .comments-list {
            margin-top: 30px;
            position: relative;
        }

        /**
 * Lineas / Detalles
 -----------------------*/
        .comments-list:before {
            content: '';
            width: 2px;
            height: 100%;
            background: #c7cacb;
            position: absolute;
            left: 32px;
            top: 0;
        }

        .comments-list:after {
            content: '';
            position: absolute;
            background: #c7cacb;
            bottom: 0;
            left: 27px;
            width: 7px;
            height: 7px;
            border: 3px solid #dee1e3;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
        }

        .reply-list:before,
        .reply-list:after {
            display: none;
        }

        .reply-list li:before {
            content: '';
            width: 60px;
            height: 2px;
            background: #c7cacb;
            position: absolute;
            top: 25px;
            left: -55px;
        }


        .comments-list li {
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .comments-list li:after {
            content: '';
            display: block;
            clear: both;
            height: 0;
            width: 0;
        }

        .reply-list {
            padding-left: 88px;
            clear: both;
            margin-top: 15px;
        }

        /**
 * Avatar
 ---------------------------*/
        .comments-list .comment-avatar {
            width: 65px;
            height: 65px;
            position: relative;
            z-index: 99;
            float: center;
            border: 3px solid #FFF;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .comments-list .comment-avatar img {
            width: 100%;
            height: 100%;
        }

        .reply-list .comment-avatar {
            width: 50px;
            height: 50px;
        }

        .comment-main-level:after {
            content: '';
            width: 0;
            height: 0;
            display: block;
            clear: both;
        }

        /**
 * Caja del Comentario
 ---------------------------*/
        .comments-list .comment-box {
            width: 680px;
            float: right;
            position: relative;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
            -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
        }

        .comments-list .comment-box:before,
        .comments-list .comment-box:after {
            content: '';
            height: 0;
            width: 0;
            position: absolute;
            display: block;
            border-width: 10px 12px 10px 0;
            border-style: solid;
            border-color: transparent #FCFCFC;
            top: 8px;
            left: 0px;
        }

        .comments-list .comment-box:before {
            border-width: 11px 13px 11px 0;
            border-color: transparent rgba(0, 0, 0, 0.05);
            left: -0px;
        }

        .reply-list .comment-box {
            width: 610px;
        }

        .comment-box .comment-head {
            background: #FCFCFC;
            padding: 10px 12px;
            border-bottom: 1px solid #E5E5E5;
            overflow: hidden;
            -webkit-border-radius: 4px 4px 0 0;
            -moz-border-radius: 4px 4px 0 0;
            border-radius: 4px 4px 0 0;
        }

        .comment-box .comment-head i {
            float: right;
            margin-left: 14px;
            position: relative;
            top: 2px;
            color: #A6A6A6;
            cursor: pointer;
            -webkit-transition: color 0.3s ease;
            -o-transition: color 0.3s ease;
            transition: color 0.3s ease;
        }

        .comment-box .comment-head i:hover {
            color: #03658c;
        }

        .comment-box .comment-name {
            color: #283035;
            font-size: 14px;
            font-weight: 700;
            float: left;
            margin-right: 10px;
        }

        .comment-box .comment-name a {
            color: #283035;
        }

        .comment-box .comment-head span {
            float: left;
            color: #999;
            font-size: 13px;
            position: relative;
            top: 1px;
        }

        .comment-box .comment-content {
            background: #FFF;
            padding: 12px;
            font-size: 15px;
            color: #595959;
            -webkit-border-radius: 0 0 4px 4px;
            -moz-border-radius: 0 0 4px 4px;
            border-radius: 0 0 4px 4px;
        }

        .comment-box .comment-name.by-author,
        .comment-box .comment-name.by-author a {
            color: #03658c;
        }

        .comment-box .comment-name.by-author:after {
            content: 'autor';
            background: #03658c;
            color: #FFF;
            font-size: 12px;
            padding: 3px 5px;
            font-weight: 700;
            margin-left: 10px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
        }

        /** =====================
 * Responsive
 ========================*/
        @media only screen and (max-width: 766px) {
            .comments-container {
                width: 480px;
            }

            .comments-list .comment-box {
                width: 100%;
            }

            .reply-list .comment-box {
                width: 320px;
            }
        }

    }

</style>




<div class="bgMain">
    <!-- BANNER MAIN -->
    <div class="swiper slideMain">

        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <!-- Asegúrate de que la ruta de la imagen sea correcta -->
                <img style="object-fit: fill;" src="{{ asset($global_event->img_main) }}" alt="{{ $global_event->title }}" />
                {{-- <a href="#" target="_blank">
                    <span class="btnVerObra" style="flex-direction: column">
                        <div style="display: flex">
                            <img class="bannerimg" src="{{ config('app.url') }}/recursos/public/btnVer.png" style="width:170px; max-width:100%" alt="">
                            <div class="flex items-center gap-4 containerVerBtnObra">
                                <div style="width: 100%">
                                    <p class="font-bold text-white" style="font-size: 1.9em">{{ $global_event->title }}</p>
                                </div>

                            </div>
                        </div>
                        <div>
                            <p class="font-bold text-white" style="font-size: 0.8em;color: rgb(255, 255, 255);width: 100%;font-weight: 200">{{ $global_event->description }}</p>
                        </div>
                    </span>

                </a> --}}

            </div>

        </div>

        <div class="swiper-pagination"></div>


    </div>
    <!-- INICIO SECCION DESTACADOS-->
    <!--FIN SECCION DESTACADOS -->

    <div id="seccion" class="pt-12 seccion-bloque" style="background-color: #1d313d">
        <div class="flex items-center w-full gap-5 pl-12">
            <span class="textoResaltado tituloSeccionObra" style="border-color: black;padding-bottom: 10px">{{ $global_event->title }}</span>
        </div>



        <div class="acc-container" style="display: flex;flex-direction: column;height: fit-content;">
            @foreach ($eventos as $event)
            @if($eventos->last() == $event)

            <iframe onload="resizeIframe(this)" scrolling="no"  width="100%" height="100%" id="i{{ $event->title }}" src="{{ url('/obras-with-descripcion/'.$event->title) }}" frameborder="0"></iframe>
            @else
            <iframe onload="resizeIframe(this)" scrolling="no"  width="100%" height="100%" id="i{{ $event->title }}" src="{{ url('/obras/'.$event->title) }}" frameborder="0"></iframe>
            @endif
            @endforeach

        </div>



    </div>




    <div class="pt-12 footer seccion-bloque">
        <span class="allTicketTransparent">
            <img src="{{ config('app.url') }}/recursos/public/allTicketTransparent2.png" alt="">
        </span>
        <div class="flex items-center w-full gap-5 pl-12">
            <img class="logoSection" src="{{ config('app.url') }}/recursos/public/obrasDestacadas.png" alt="" />
            <span class="w-full titleSection borderSeccion">Eventos Destacados</span>
        </div>

        <div class="p-12 swiper slideObras">
            <div class="swiper-wrapper">
                @foreach ($eventos2 as $categoriaId => $eventosCategoria)
                @foreach ($eventosCategoria as $event2)
                @if($event2->end_date->isPast())

                @else
                <div class="swiper-slide swiper-slide2 animationShow" onclick="redirigir('{{ $event2->title }}')">
                    @if ($event2->images->count() > 0)
                    <img src="{{ asset($event2->images->first()->image_path) }}" alt="" />
                    @else
                    Imagen
                    @endif
                    <div class="containerDescription">
                        <div class="flex flex-col text-justify">
                            <span>{{ $event2->title }} </span>

                            <p class="text-sm">Ventas {{ $event2->formatted_date }}</p>
                            <p class="text-sm">{{ $event2->teatro->nombre }}</p>
                        </div>
                        <div class="flex flex-col justify-between">
                            <div class="containerDuracion">
                                <img src="{{ config('app.url') }}/recursos/public/reloj.png" alt="">
                                <p>{{ $event2->location_post_code }} min.</p>
                            </div>
                            <a onclick="redirigir('{{ $event2->title }}')" style="color: #fff;background: var(--bgHeader);padding: 5px 25px;cursor: pointer;">COMPRAR</a>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                @endforeach
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>



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

        function redirigir(idc) {

            //let encodedTitle = encodeURIComponent(idc);
            // Convertir a minúsculas
            let lowerCaseString = idc.toLowerCase();

            // Reemplazar los espacios por guiones
            let modifiedString = lowerCaseString.replace(/ /g, '-');

            window.location.href = "{{ url('/') }}/obras/" + modifiedString;
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
            spaceBetween: 40
            , speed: 1000
            , loop: true
            , transitionDelay: 2000
            , navigation: {
                nextEl: ".swiper-button-next"
                , prevEl: ".swiper-button-prev"
            , }
            , breakpoints: {
                300: {
                    slidesPerView: 1
                , }
                , 500: {
                    slidesPerView: 2
                , }
                , 900: {
                    slidesPerView: 4
                , }
            }
        });

    </script>







    @endsection
