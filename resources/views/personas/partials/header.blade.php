<div class="flex flex-col justify-between h-full">
        <div class="header flex flex-col justify-center items-center gap-10 py-2 w-full">
        <div class="grid grid-cols-3 items-center w-full px-5 containerHeader">
          <span>
 @if (Auth::guard('clientes')->check())
<a href="{{ route('personas.dashboard') }}" class="w-min">
              <img class="logoMainHeader" src="{{ config('app.url') }}/recursos/public/logoMain2.png"></img>
            </a>            
@else
<a href="{{ route('personas.index') }}" class="w-min">
              <img class="logoMainHeader" src="{{ config('app.url') }}/recursos/public/logoMain2.png"></img>
            </a>
@endif
          </span>
<span class="flex items-center bg-white p-2 buscadorMain">
<form id="frmBuscar" action="{{ route('personas.buscar') }}" method="GET" style="display: flex;justify-content: space-between;width: 100%;">
        <input
            class="buscadorBuscador"
            type="search"
            name="search"
            placeholder="Buscar"
            required  oninvalid="this.setCustomValidity('Introduzca título del evento.')" oninput="this.setCustomValidity('')"
        />
        <button type="submit">
            <img class="lupaBuscadorLogo" src="{{ config('app.url') }}/recursos/public/lupaBuscador.png" />
        </button>
</form>
    </span>
          
 @if (Auth::guard('clientes')->check())
<!---- LOGUEADO --->
 <span class="flex flex-col justify-center bg-black p-2 px-5 w-max justify-self-end btnIngresarDash cursor-pointer items-center relative cursor-pointer">
            <input type="checkbox" id="toggle-dropdown-dash" />
            <label class="flex gap-4 cursor-pointer flex items-center" for="toggle-dropdown-dash">
              <img src="{{ config('app.url') }}/recursos/public/userBtn.png"></img>
              <div class="text-white text-sm font-bold uppercase">
                <p>Bienvenid@:</p>
                <p>{{ Auth::guard('clientes')->user()->nombres }}</p>
              </div>
              <img class="arrowBtnDashboard" src="{{ config('app.url') }}/recursos/public/arrowBtnDashboard.png" />
            </label>
            <ul class="items-center gap-4 text-white pt-5 containerMenuDashboard">
              <a class="flex gap-4" href="{{ route('personas.editarperfil') }}">
                <img src="{{ config('app.url') }}/recursos/public/botoneraeditarperfil.png" />
                <span>Editar Perfil</span>
              </a>
              <a class="flex gap-4" href="{{ route('personas.miscompras') }}">
                <img src="{{ config('app.url') }}/recursos/public/botoneramiscompras.png" />
                <span>Mis Compras</span>
              </a>
		<form action="{{ route('personas.logout') }}" method="POST">
        	@csrf
       		 <button type="submit">Cerrar sesión</button>
   		 </form>
              <!--<a href="index.html">Cerrar Sesion</a>-->
  
              <ul class="flex gap-4 pt-5 border-t-2">
   @if(empty($organiser->google_analytics_code))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->google_analytics_code }}">B<img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/instagramIcon.png"></img></a>
      	</li>
	@endif

	@if(empty($organiser->facebook))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->facebook }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/facebookIcon.png"></img></a>
      	</li>
	@endif 
@if(empty($organiser->tax_name))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->tax_name }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/youtubeIcon.png"></img></a>
      	</li>
	@endif 
@if(empty($organiser->twitter))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->twitter }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/twitterIcon.png"></img></a>
      	</li>
	@endif 
                  </ul>
            </ul>
          </span>

<!---- LOGUEADO --->
@else
<!---- SIN LOGUEAR --->
	<span class="flex justify-center rounded-full bg-black p-2 px-5 w-max justify-self-end btnIngresar cursor-pointer">
            <a href="{{ route('personas.login') }}" class="flex gap-4">
              <img src="{{ config('app.url') }}/recursos/public/userBtn.png"></img>
              <p class="text-white text-2xl font-bold uppercase">Ingresa</p>
            </a>
          </span>
<!---- SIN LOGUEAR --->
@endif



<span class="containerMenu">
            <ul class="menu">
              <li>
                <input type="checkbox" id="toggle-services" />
                <label for="toggle-services">
                  <img src="{{ config('app.url') }}/recursos/public/menu-burger-horizontal-svgrepo-com.png" />
                </label>
                <ul class="submenu">
                  <li class="">
                    <a href="{{ route('personas.login') }}" class="gap-4">
                      <img src="{{ config('app.url') }}/recursos/public/userBtn.png"></img>Ingresa
                    </a>
                  </li>
                  <ul class="flex gap-4 pt -5">
  @if(empty($organiser->google_analytics_code))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->google_analytics_code }}">A<img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/instagramIcon.png"></img></a>
      	</li>
	@endif

	@if(empty($organiser->facebook))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->facebook }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/facebookIcon.png"></img></a>
      	</li>
	@endif 
@if(empty($organiser->tax_name))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->tax_name }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/youtubeIcon.png"></img></a>
      	</li>
	@endif 
@if(empty($organiser->twitter))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->twitter }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/twitterIcon.png"></img></a>
      	</li>
	@endif 
                  </ul>
                </ul>
              </li>
            </ul>
          </span>

        </div>
      </div>
        <div class="navBarMain grid grid-cols-2 items-center px-5 py-1">
          <ul class="flex gap-4 text-xl text-white font-semibold items-center listadoCategoriaNav">
             @foreach($categorias as $categoria)
            <li style="display: inline-block;white-space: nowrap;">
               <a href="{{ route('personas.categoria', $categoria->id) }}">{{ $categoria->descripcion }}</a>
            </li>
        	@endforeach
          </ul>
            <ul class="flex gap-4 items-center justify-self-end containerSiguenos">
              <span class="font-bold text-xl">SIGUENOS:</span>
             @if(empty($organiser->google_analytics_code))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->google_analytics_code }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/instagramIcon.png"></img></a>
      	</li>
	@endif

	@if(empty($organiser->facebook))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->facebook }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/facebookIcon.png"></img></a>
      	</li>
	@endif 
@if(empty($organiser->tax_name))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->tax_name }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/youtubeIcon.png"></img></a>
      	</li>
	@endif 
@if(empty($organiser->twitter))
	@else
       	<li>
            <a target="_blank" href="{{ $organiser->twitter }}"><img class="iconEnlacesRedes" src="{{ config('app.url') }}/recursos/public/twitterIcon.png"></img></a>
      	</li>
	@endif 
            </ul>
        </div>
      </div>