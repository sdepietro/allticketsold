      <div class="footer flex flex-col justify-center items-center gap-10 pb-2 pt-6 w-full bottom-0">
          <div class="relative w-full flex flex-col justify-center containerEnlacesFooter">
              <ul class="flex gap-4 min-w-max justify-center containerEnlacesFo">
			   @if (Auth::guard('clientes')->check())
				    <li><a href="{{ route('personas.dashboard') }}">Inicio</a></li>
				   @else
                  <li><a href="{{ route('personas.index') }}">Inicio</a></li>
			  @endif
                    <li><a href="{{ route('personas.sobre-nosotros') }}">Sobre Nosotros</a></li>
                    <li><a href="{{ route('personas.preguntas-frecuentes') }}">Preguntas Frecuentes</a></li>
                    <li><a href="{{ route('personas.terminos-condiciones') }}">Políticas de Privacidad</a></li>
					<li><a href="{{ route('personas.formularioarrep') }}">Botón de arrepentimiento</a></li>
                    <li><a href="{{ route('personas.contacto') }}">Contacto</a></li>
              </ul>
              <div class="flex gap-4 absolute top-0 right-5 containerRedesFo">
                  @if(empty($organiser->google_analytics_code))
	@else
       	<span><a target="_blank" href="{{ $organiser->google_analytics_code }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/instagramIcon.png"></img></a></span>
	@endif
	@if(empty($organiser->facebook))
	@else
       	 <span> <a target="_blank" href="{{ $organiser->facebook }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/facebookIcon.png"></img></a></span>
	@endif 
	@if(empty($organiser->tax_name))
	@else
       <span><a target="_blank" href="{{ $organiser->tax_name }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/youtubeIcon.png"></img></a></span>
	@endif 
	@if(empty($organiser->twitter))
	@else
       	 <span><a target="_blank" href="{{ $organiser->twitter }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/twitterIcon.png"></img></a></span>
	@endif 
              </div>
          </div>
      
          <div style="display: flex;margin-bottom: 20px;">
          <div class="flex flex-col items-center gap-2">
              <a href="{{ route('personas.login') }}"><img class="logoFooter" src="{{ config('app.url') }}/recursos/public/logoTicketFooter2.png"></img></a>
              <p class="textCopyRight">All Tickets © 2024 - Todos los derechos reservados</p>
          </div>
		  <div style="width: 65px;margin-left: 20px;">
				<a href="http://qr.afip.gob.ar/?qr=73DF_pBKBbPdgvBRcY2V5w,," target="_F960AFIPInfo">
					<img src="http://www.afip.gob.ar/images/f960/DATAWEB.jpg" border="0">
				</a>
		  </div>
		  </div>
      </div>
	  
	 </div>
  

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ config('app.url') }}/recursos/animationSlider.js"></script>
