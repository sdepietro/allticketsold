<style>
.dropdown-menu {
    display: none; /* Ocultar el submenú por defecto */
    position: absolute; /* Para que se muestre correctamente */
    background-color: #3f4142; /* Color de fondo */
    border: 1px solid #ccc; /* Borde del submenú */
    z-index: 1000; /* Asegúrate de que esté encima de otros elementos */
    left: 100%; /* Alinea el submenú a la derecha del menú principal */
    top: 0; /* Alinea el submenú en la parte superior */
}

/* Mostrar el submenú al pasar el mouse */
.dropdown:hover .dropdown-menu {
    display: block; /* Mostrar submenú al pasar el mouse */
}

/* Estilo de los elementos del submenú */
.dropdown-menu li a {
    color: #333; /* Color del texto por defecto */
    padding: 10px; /* Espaciado interno */
    text-decoration: none; /* Sin subrayado */
    display: block; /* Hacer que el área clickeable sea más grande */
}

/* Cambiar color al pasar el mouse */
.dropdown-menu li a:hover {
    background-color: #428bca; /* Cambiar color de fondo al pasar el mouse */
    color: #000; /* Cambiar color del texto al pasar el mouse */
}
</style>
<aside class="sidebar sidebar-left sidebar-menu" style="background-color: #3f4142;">
    <section class="content">
        <h5 class="heading">@lang("Organiser.organiser_menu")</h5>

        <ul id="nav" class="topmenu">
            <li class="{{ Request::is('*dashboard*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserDashboard', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-home2"></i></span>
                    <span class="text">@lang("Organiser.dashboard")</span>
                </a>
            </li>
            <!--<li class="{{ Request::is('*events*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserEvents', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-calendar"></i></span>
                    <span class="text">@lang("Organiser.event")</span>
                </a>
            </li>
	    <li class="{{ Request::is('*archivadas*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserArchivados', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-calendar"></i></span>
                    <span class="text">@lang("Organiser.event2")</span>
                </a>
            </li>-->
		<li class="dropdown {{ Request::is('*events*') || Request::is('*archivadas*') ? 'active' : '' }}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="figure"><i class="ico-calendar"></i></span>
            <span class="text">Eventos </span>
        </a>
        <ul class="dropdown-menu" style="min-width: 200px;">
            <li class="">
                <a href="{{ route('showOrganiserGlobalEvents', array('organiser_id' => $organiser->id)) }}">
                    <span class="figure"><i class="ico-calendar"></i></span>
                    <span class="text">Eventos</span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('showOrganiserEvents', array('organiser_id' => $organiser->id)) }}">
                    <span class="figure"><i class="ico-calendar"></i></span>
                    <span class="text">@lang("Organiser.event")</span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('showOrganiserArchivados', array('organiser_id' => $organiser->id)) }}">
                    <span class="figure"><i class="ico-calendar"></i></span>
                    <span class="text">@lang("Organiser.event2")</span>
                </a>
            </li>
        </ul>
    </li>

            <li class="{{ Request::is('*customize*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserCustomize', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-cog"></i></span>
                    <span class="text">@lang("Organiser.customize")</span>
                </a>
            </li>
	@if($organiser->id == 1)
		<li class="{{ Request::is('*clientes*') ? 'active' : '' }}">
                <a href="{{route('clientes.index', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-user"></i></span>
                    <span class="text">Clientes</span>
                </a>
            </li>
	<!--<li class="{{ Request::is('*categorias*') ? 'active' : '' }}">
                <a href="{{route('categorias.index', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-th"></i></span>
                    <span class="text">Categorias</span>
                </a>
            </li>
<li class="{{ Request::is('*banners*') ? 'active' : '' }}">
                <a href="{{route('banners.index', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-th"></i></span>
                    <span class="text">Banners</span>
                </a>
            </li>
			<li class="{{ Request::is('*teatros*') ? 'active' : '' }}">
                <a href="{{route('teatros.index', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-th"></i></span>
                    <span class="text">Teatros</span>
                </a>
            </li>
			<li class="{{ Request::is('*preguntas*') ? 'active' : '' }}">
                <a href="{{route('preguntas.index', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-th"></i></span>
                    <span class="text">Preguntas Frecuentes</span>
                </a>
            </li>-->
		<li class="dropdown {{ Request::is('*categorias*') || Request::is('*banners*') || Request::is('*teatros*') || Request::is('*preguntas*') ? 'active' : '' }}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="figure"><i class="ico-cog"></i></span>
            <span class="text">@lang("Auxiliares")</span>
        </a>
        <ul class="dropdown-menu" style="min-width: 300px;">
            <li class="">
                <a href="{{route('categorias.index', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-th"></i></span>
                    <span class="text">Categorias</span>
                </a>
            </li>
            <li class="">
                <a href="{{route('banners.index', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-th"></i></span>
                    <span class="text">Banners</span>
                </a>
            </li>
            <li class="">
                <a href="{{route('teatros.index', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-th"></i></span>
                    <span class="text">Teatros</span>
                </a>
            </li>
            <li class="">
                <a href="{{route('preguntas.index', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-th"></i></span>
                    <span class="text">Preguntas Frecuentes</span>
                </a>
            </li>


        </ul>
		</li>

		<li class="dropdown {{ Request::is('*productoras*') || Request::is('*perfiles*') ? 'active' : '' }}">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<span class="figure"><i class="ico-calendar"></i></span>
					<span class="text">Productoras</span>
				</a>
				<ul class="dropdown-menu" style="min-width: 200px;">
					<li class="">
						<a href="{{ route('showOrganiserPerfiles', array('organiser_id' => $organiser->id)) }}">
							<span class="figure"><i class="ico-calendar"></i></span>
							<span class="text">Perfiles</span>
						</a>
					</li>
					<li class="">
						<a href="{{ route('showOrganiserObras', array('organiser_id' => $organiser->id)) }}">
							<span class="figure"><i class="ico-calendar"></i></span>
							<span class="text">Aprobaciones</span>
						</a>
					</li>

					{{-- <li class="">
						<a href="#">
							<span class="figure"><i class="ico-calendar"></i></span>
							<span class="text">Ajustes</span>
						</a>
					</li> --}}
				</ul>
			</li>
			@else
				<li class="{{ Request::is('*rpublicas*') ? 'active' : '' }}">
                <a href="{{ route('showOrganiserRpublicas', array('organiser_id' => $organiser->id)) }}">
                    <span class="figure"><i class="ico-user"></i></span>
                    <span class="text">Relaciones Públicas</span>
                </a>
            </li>
			<li class="{{ Request::is('*contabilidad*') ? 'active' : '' }}">
                <a href="#">
                    <span class="figure"><i class="ico-user"></i></span>
                    <span class="text">Contabilidad</span>
                </a>
            </li>
			@endif
		 </ul>

    </li>




    </section>
</aside>

