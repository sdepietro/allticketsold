
@extends('Shared.Layouts.Master')

@section('title')
    @parent
    {{ trans('Organiser.productora') }}
@endsection

@section('top_nav')
    @include('ManageOrganiser.Partials.TopNav')
@stop
@section('page_title')
    {{ trans('Organiser.organiser_name_prod', ['name'=>$organiser->name])}}
@stop

@section('menu')
    @include('ManageOrganiser.Partials.Sidebar')
@stop

@section('head')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" integrity="sha256-szHusaozbQctTn4FX+3l5E0A5zoxz7+ne4fr8NgWJlw=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.4/raphael-min.js" integrity="sha256-Gk+dzc4kV2rqAZMkyy3gcfW6Xd66BhGYjVWa/FjPu+s=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js" integrity="sha256-0rg2VtfJo3VUij/UY9X0HJP7NET6tgAY98aMOfwP0P8=" crossorigin="anonymous"></script>

    {!! Html::script('https://maps.googleapis.com/maps/api/js?libraries=places&key='.config("attendize.google_maps_geocoding_key")) !!}
    {!! Html::script('vendor/geocomplete/jquery.geocomplete.min.js')!!}
    {!! Html::script('vendor/moment/moment.js')!!}
    {!! Html::script('vendor/fullcalendar/dist/fullcalendar.min.js')!!}
    <?php
    if(Lang::locale()!="en")
        echo Html::script('vendor/fullcalendar/dist/lang/'.Lang::locale().'.js');
    ?>
    {!! Html::style('vendor/fullcalendar/dist/fullcalendar.css')!!}

    
@stop
@section('page_header')
    <div class="col-md-9">
        <div class="btn-toolbar">
            <div class="btn-group btn-group-responsive">
                <a href="{{ route('crearperfil', ['organiser_id' => $organiser_id]) }}"  class="btn btn-success"><i class="ico-plus"></i> Crear Productora</a>
            </div>
        </div>
    </div>
@stop
@section('content')
<div class="table-responsive ">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombres</th>
                <th>Email</th>
                <th>Estado</th>
		<th>Accion</th>
            </tr>
        </thead>
        <tbody>
     
@foreach($clientes as $cliente)
            <tr>
                <td>{{ $cliente->id }}</td>
	
                <td>{{ $cliente->first_name }}</td>
                <td>{{ $cliente->email }}</td>
                <td>{{ $cliente->is_parent }}</td>
		<td class="text-center" style="display: flex;">
       			
        	
        		<a style="margin-right: 20px;" class="btn btn-xs btn-success" href="{{ route('perfil.edit', ['organiser_id' => $organiser_id, 'id' => $cliente->id]) }}">Editar</a>
        		<form action="{{ route('perfil.eliminar', ['organiser_id' => $organiser_id, 'id' => $cliente->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este perfil?');">
					@csrf
					@method('DELETE')
					<button type="submit" class="btn btn-danger">Eliminar</button>
				</form>
   			 </td>
            </tr>
            @endforeach
        </tbody>
    </table>
<script>
function confirmDeletion(event) {
    if (!confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
        event.preventDefault(); // Evita el envío del formulario
        return false;
    }
    return true; // Permite el envío del formulario
}
</script>
</div>
@stop