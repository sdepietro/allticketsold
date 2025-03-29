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
    <h1>Crear Categoría</h1>

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categorias.store', $organiser_id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="descripcion" class="control-label">Descripción</label>
            <input class="form-control"  type="text" id="descripcion" name="descripcion" class="form-control" value="{{ old('descripcion') }}" required>
        </div>
		<div class="form-group">
        <label  class="control-label" for="posicion">Posición</label>
        <input class="form-control"  type="number" class="form-control" id="posicion" name="posicion">
		</div>

		<div class="form-group">
        <label class="control-label" for="imagen">Imagen</label>
        <input class="form-control" type="file" class="form-control-file" id="imagen" name="imagen">
        </div>

		<div class="form-group">
    		<label class="control-label" for="activado">Activado</label>
    		<select class="form-control" id="activado" name="activado">
        		<option value="1">Sí</option>
        		<option value="0">No</option>
    		</select>
		</div>

        <!-- Puedes agregar otros campos aquí si es necesario -->

        <button class="btn btn-success" type="submit" class="btn btn-primary mt-3">Guardar</button>
    </form>

</div>
@stop