@extends('Shared.Layouts.Master')

@section('title')
    @parent
    {{ trans('Organiser.clientes') }}
@endsection

@section('top_nav')
    @include('ManageOrganiser.Partials.TopNav')
@stop
@section('page_title')
    {{ trans('Organiser.organiser_name_clientes', ['name'=>$organiser->name])}}
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
                <a href="{{ route('clientes.index', ['organiser_id' => $organiser_id]) }}"  class="btn btn-success"><i class="ico-arrow-left"></i> Volver a la lista</a>
            </div>
        </div>
    </div>
@stop
@section('content')
<h2>Editar Cliente</h2>
    <!-- Formulario para editar el cliente -->
    <form action="{{ route('clientes.update', ['organiser_id' => $organiser_id, 'id' => $cliente->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Campo para nombres -->
	<div class="form-group">
            <label for="nombres" class="control-label">DNI:</label>
            <input class="form-control" type="text" id="dni" name="dni" value="{{ old('dni', $cliente->dni) }}" required>
        </div>
        <div class="form-group">
            <label for="nombres" class="control-label">Nombres:</label>
            <input class="form-control" type="text" id="nombres" name="nombres" value="{{ old('nombres', $cliente->nombres) }}" required>
        </div>

        <!-- Campo para teléfono -->
        <div class="form-group">
            <label for="telefono" class="control-label">Teléfono:</label>
            <input class="form-control" type="text" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" required>
        </div>

        <!-- Campo para email -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $cliente->email) }}" required>
        </div>

        <!-- Campo para contraseña -->
        <div class="form-group">
            <label for="contraseña" class="control-label">Contraseña (min 8 caracteres):</label>
            <input class="form-control" type="password" minlength="8" id="contraseña" name="contraseña" placeholder="Ingrese una nueva contraseña (si desea cambiarla)">
        </div>

        <button class="btn btn-success" type="submit">Actualizar Cliente</button>
    </form>

@stop