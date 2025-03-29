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

    @media (min-width: 801px) and (max-width: 1200px) {

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

    }

</style>

<div class="overflow-hidden bgMain">
    <!-- BANNER MAIN -->
    <div class="swiper slideMain">

        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <!-- Asegúrate de que la ruta de la imagen sea correcta -->
                <img style="object-fit: fill;" src="{{ asset($global_event->img_main) }}" alt="{{ $global_event->title }}" />
                <a href="#" target="_blank">
                    <span class="btnVerObra" style="flex-direction: column">
                       <div style="display: flex">
                        <img class="bannerimg" src="{{ config('app.url') }}/recursos/public/btnVer.png" style="width:170px; max-width:100%" alt="">
                        <div class="flex items-center gap-4 containerVerBtnObra" >
                           <div style="width: 100%">
                            <p class="font-bold text-white" style="font-size: 1.9em">{{ $global_event->title }}</p>
                           </div>

                        </div>
                       </div>
                        <div>
                            <p class="font-bold text-white" style="font-size: 0.8em;color: rgb(255, 255, 255);width: 100%;font-weight: 200">{{ $global_event->description }}</p>
             </div>
                    </span>

                </a>

            </div>

        </div>

        <div class="swiper-pagination"></div>
    </div>
    <!-- INICIO SECCION DESTACADOS-->




    <!--FIN SECCION DESTACADOS -->
    @foreach($categorias as $categoria)
    <div id="seccion{{ $categoria->id }}" class="pt-12 seccion-bloque">
        <div class="flex items-center w-full gap-5 pl-12">
            @if($categoria->imagen)
            <img src="{{ asset('storage/' . $categoria->imagen) }}" alt="Imagen" class="img-thumbnail" style="max-width: 100px;">
            @else
            <img class="logoSection" src="{{ config('app.url') }}/recursos/public/standup.png" alt="" />
            @endif



            <span class="w-full titleSection borderSeccion" style="border-color: black">{{ $categoria->descripcion }}</span>
        </div>

        <div class="p-12 swiper slideObras">
            <div class="swiper-wrapper">
                @if(isset($eventos[$categoria->id]))
                <!-- Verifica si el evento pertenece a la categoría actual -->
                @foreach($eventos[$categoria->id] as $event)
                @if($event->end_date->isPast())
                @else
                <div class="swiper-slide swiper-slide2 animationShow" onclick="redirigir('{{ $event->title }}')">
                    @if ($event->images->count() > 0)
                    <img src="{{ asset($event->images->first()->image_path) }}" alt="" />
                    @else
                    Imagen
                    @endif
                    <div class="containerDescription">
                        <div class="flex flex-col text-justify">
                            <span>{{ $event->title }}</span>

                            <p class="text-sm">Ventas {{ $event->formatted_date }}</p>
                            <p class="text-sm">{{ $event->teatro->nombre }}</p>
                        </div>
                        <div class="flex flex-col justify-between">
                            <div class="containerDuracion">
                                <img src="{{ config('app.url') }}/recursos/public/reloj.png" alt="">
                                <p>{{ $event->location_post_code }} min.</p>
                            </div>
                            <a onclick="redirigir('{{ $event->title }}')" style="color: #fff;background: var(--bgHeader);padding: 5px 25px;cursor: pointer;">COMPRAR</a>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                @endif


            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    @endforeach
    <!--FIN SECCION -->


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
