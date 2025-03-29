@extends('personas.layouts.app')
@section('content')
<style>
.no-events-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: auto; /* Ocupa toda la altura del viewport */
    background-color: transparent;  /* Fondo gris claro */
    padding: 20px;
    box-sizing: border-box;
}

.no-events-message {
    text-align: center;
    background: transparent; /* Fondo blanco para el mensaje */
    border-radius: 8px; /* Bordes redondeados */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra sutil */
    padding: 30px;
    max-width: 600px; /* Limitar el ancho máximo del contenedor */
}

.no-events-message p {
    font-size: 18px;
    color: #333; /* Texto en gris oscuro */
    margin-bottom: 20px;
}

</style>



    <div class="overflow-hidden bgMain">
      <!-- BANNER MAIN -->
 <div class="pt-12 seccion-bloque">
        <div class="flex items-center w-full gap-5 pl-12"  style="flex-direction: column;padding-left: 1rem !important;">

          <img class="logoSection" src="{{ config('app.url') }}/recursos/public/buscar.png" style="width: 67px;" alt="" />

          <span class="w-full titleSection borderSeccion" style="border-color: black;text-align: center;"
            >Resultados de la busqueda</span
          >
        </div>


 <div class="flex flex-col gap-4 p-5" style="padding: 3rem;flex-wrap: wrap;flex-direction: row;justify-content: space-evenly;">
 @if($eventos->isNotEmpty())
	@foreach($categorias as $categoria)
	 @if(isset($eventos[$categoria->id]))
	 <!-- Verifica si el evento pertenece a la categoría actual -->
         @foreach($eventos[$categoria->id] as $event)
		 @if($event->end_date->isPast())
		 @else
           <div class="flex flex-col animationShow" onclick="redirigir('{{ $event->title }}')" style="margin-top: 40px;">
              <img src="{{ asset($event->images->first()->image_path) }}" alt="" />
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
	   @else
	   @endif
	   @endforeach
	   @else
	   <div class="no-events-container">
    		<div class="flex flex-col items-center w-full gap-4 containerLogin">

        	<p class="flex gap-1 py-5 font-semibold" style="text-align: center;">No se encontraron Funciones.</p>

    		</div>
		</div>
		@endif
        </div>
      </div>
<br>

<br><br><br>
	  <!--FIN SECCION -->



    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
        function scrollToSection(id) {
            var section = document.getElementById(id);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth' });
            }
        }
function redirigir(idc)
{

//let encodedTitle = encodeURIComponent(idc);
// Convertir a minúsculas
let lowerCaseString = idc.toLowerCase();

// Reemplazar los espacios por guiones
let modifiedString = lowerCaseString.replace(/ /g, '-');

 window.location.href="{{ config('app.url') }}/obras/"+modifiedString;
}
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            // Leer el parámetro de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const sectionId = urlParams.get('page');

            if (sectionId) {
                const section = document.getElementById(sectionId);
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>
    <!-- Initialize Swiper -->
    <script>
      var swiper = new Swiper(".slideMain", {
        speed: 2000,
        loop: true,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
      });
      var swiper2 = new Swiper(".slideObras", {
        spaceBetween: 15,
        speed: 1000,
        loop: true,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            300: {
                slidesPerView: 1,
            },
            500: {
                slidesPerView: 2.5,
            },
            900: {
                slidesPerView: 3.5,
            }
        }
      });
    </script>
 @endsection
