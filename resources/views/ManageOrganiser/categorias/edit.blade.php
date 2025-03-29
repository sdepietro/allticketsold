@extends('Shared.Layouts.Master')

@section('title')
    @parent
    {{ trans('Organiser.clientes') }}
@endsection

@section('top_nav')
    @include('ManageOrganiser.Partials.TopNav')
@stop
@section('page_title')
    {{ trans('Organiser.organiser_name_categorias', ['name'=>$organiser->name])}}
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
                <a href="{{ route('categorias.index', ['organiser_id' => $organiser_id]) }}"  class="btn btn-success"><i class="ico-arrow-left"></i> Volver a la lista</a>
            </div>
        </div>
    </div>
@stop
@section('content')
<div class="container">
    <h1>Editar Categoría</h1>

    <form action="{{ route('categorias.update', ['organiser_id' => $organiser_id, 'id' => $categorias->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="descripcion" class="control-label">Descripción</label>
            <input class="form-control" type="text" id="descripcion" name="descripcion" class="form-control" value="{{ old('descripcion', $categorias->descripcion) }}" required>
        </div>
<div class="form-group">
        <label class="control-label" for="posicion">Posición</label>
        <input class="form-control" type="number" class="form-control" id="posicion" name="posicion" value="{{ $categorias->posicion }}">
    </div>

    <div class="form-group">
        <label class="control-label" for="imagen">Imagen</label>
        <input class="form-control" type="file" class="form-control-file" id="imagen" name="imagen">
        @if($categorias->imagen)
            <img src="{{ asset('storage/' . $categorias->imagen) }}" alt="Imagen" class="img-thumbnail mt-2" style="max-width: 200px;">
        @endif
    </div>

    	<div class="form-group">
    	<label class="control-label" for="activado">Activar en pantalla principal</label>
    		<select class="form-control" id="activado" name="activado">
        	<option value="1" {{ $categorias->activado == 1 ? 'selected' : '' }}>Sí</option>
        	<option value="0" {{ $categorias->activado == 0 ? 'selected' : '' }}>No</option>
    		</select>
	</div>
        <!-- Puedes agregar otros campos aquí según sea necesario -->

        <button class="btn btn-success" type="submit" class="btn btn-primary mt-3">Actualizar</button>
    </form>

</div>
@stop