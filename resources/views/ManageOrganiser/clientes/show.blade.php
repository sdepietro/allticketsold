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
    <h2>Detalles del Cliente</h2>
<div class="form-group">
        <label for="nombres" class="control-label">DNI:</label>
        <input class="form-control" type="text" id="dni" name="dni" value="{{ $cliente->dni }}" readonly>
    </div>
    <div class="form-group">
        <label for="nombres" class="control-label">Nombres:</label>
        <input class="form-control" type="text" id="nombres" name="nombres" value="{{ $cliente->nombres }}" readonly>
    </div>
    <div class="form-group">
        <label for="telefono" class="control-label">Teléfono:</label>
        <input class="form-control" type="text" id="telefono" name="telefono" value="{{ $cliente->telefono }}" readonly>
    </div>

    <div class="form-group">
        <label for="email" class="control-label">Email:</label>
        <input class="form-control" type="email" id="email" name="email" value="{{ $cliente->email }}" readonly>
    </div>

    <div class="form-group">
        <label for="contraseña" class="control-label">Contraseña (min 8 caracteres):</label>
        <input class="form-control" type="text" id="contraseña" name="contraseña" value="{{ $cliente->contraseña }}" readonly>
    </div>

    <!-- Enlaces de navegación -->
    <div>
        <a class="btn btn-success" href="{{ route('clientes.edit', ['organiser_id' => $organiser_id, 'id' => $cliente->id]) }}">Editar Cliente</a>

        <form action="{{ route('clientes.destroy', ['organiser_id' => $organiser_id, 'id' => $cliente->id]) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">Eliminar Cliente</button>
        </form>
    </div>
@stop