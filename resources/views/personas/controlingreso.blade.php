<!DOCTYPE html>
<html lang="en">
  <head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ALL Tickets</title>
<link rel="shortcut icon" href="{{ config('app.url') }}/assets/images/fav2/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ config('app.url') }}/assets/images/fav2/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ config('app.url') }}/assets/images/fav2/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ config('app.url') }}/assets/images/fav2/favicon-16x16.png">
    <link rel="manifest" href="{{ config('app.url') }}/assets/images/fav2/site.webmanifest">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ config('app.url') }}/recursos/styles/stylesGlobals.css" />
    <link rel="stylesheet" href="{{ config('app.url') }}/recursos/styles/stylesResponsive.css" />
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
  </head>
  <body>

    <div class="overflow-hidden bgMain">
      <!-- BANNER MAIN -->
      @foreach($categorias as $categoria)
	  @if(isset($eventos[$categoria->id]))
      <div id="seccion{{ $categoria->id }}" class="pt-12 seccion-bloque">
        <div class="flex items-center w-full gap-5 pl-12"  style="flex-direction: column;padding-left: 1rem !important;">
		@if($categoria->imagen)
                            <img src="{{ asset('storage/' . $categoria->imagen) }}" alt="Imagen" class="img-thumbnail" style="max-width: 100px;">
                        @else
                            <img class="logoSection" src="{{ config('app.url') }}/recursos/public/standup.png" alt="" />
                        @endif
          
		  
		  
          <span class="titleSection borderSeccion w-full" style="border-color: black;text-align: center;"
            >{{ $categoria->descripcion }}</span
          >
        </div>
        
 <div class="flex flex-col gap-4 p-5" style="padding: 3rem;flex-wrap: wrap;flex-direction: row;justify-content: space-evenly;">

	 
	 <!-- Verifica si el evento pertenece a la categoría actual -->
         @foreach($eventos[$categoria->id] as $event)
           <div class="flex flex-col animationShow" onclick="redirigir('{{ $event->id }}')" style="margin-top: 40px;">
              <img src="{{ asset($event->images->first()->image_path) }}" alt="" />
                 <div class="containerDescription">
                <div class="flex flex-col text-justify">
                  <span>{{ $event->title }}</span>
                  <p class="text-sm">{{ $event->formatted_date }}</p>
                  <p class="text-sm">{{ $event->teatro->nombre }}</p>
                </div>
                <div class="flex flex-col justify-between">
                  <div class="containerDuracion">
                    <img src="{{ config('app.url') }}/recursos/public/reloj.png" alt="">
                    <p>{{ $event->location_post_code }} min.</p>
                  </div>
                </div>
              </div>
            </div>
           @endforeach
	   @endif
        </div>
      </div>
@endforeach

<br><br><br>
	  <!--FIN SECCION -->
      
      <div class="footer flex flex-col justify-center items-center gap-10 pb-2 pt-6 w-full bottom-0">

          <div style="display: flex;margin-bottom: 20px;">
          <div class="flex flex-col items-center gap-2">
              <a href="{{ route('personas.login') }}"><img class="logoFooter" src="{{ config('app.url') }}/recursos/public/logoTicketFooter2.png"></img></a>
              <p class="textCopyRight">All Tickets © 2024 - Todos los derechos reservados</p>
          </div>
		  
		  </div>
      </div>
    </div>

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

 window.location.href="{{ config('app.url') }}/personas/"+idc+"/check_in";
}
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ config('app.url') }}/recursos/animationSlider.js"></script>
  </body>
</html>
