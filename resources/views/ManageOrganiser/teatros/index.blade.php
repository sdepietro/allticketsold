@extends('Shared.Layouts.Master')

@section('title')
    @parent
    {{ trans('Organiser.teatros') }}
@endsection

@section('top_nav')
    @include('ManageOrganiser.Partials.TopNav')
@stop
@section('page_title')
    {{ trans('Organiser.organiser_name_teatros', ['name'=>$organiser->name])}}
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
                <a href="{{ route('teatros.create', ['organiser_id' => $organiser_id]) }}" class="btn btn-success"><i class="ico-plus"></i> Crear Teatro</a>
            </div>
        </div>
    </div>
@stop
@section('content')
    <h1>Teatros</h1>
	<div class="table-responsive ">
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Coordenadas</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teatros as $teatro)
            <tr>
                <td>{{ $teatro->nombre }}</td>
                <td>{{ $teatro->direccion }}</td>
                <td>{{ $teatro->coordenadas }}</td>
                <td>
                    @if($teatro->imagen)
                    <img src="{{ asset('storage/' . $teatro->imagen) }}" alt="Imagen del Teatro" style="width: 100px;">
                    @endif
                </td>
                <td>
                    <a href="{{ route('teatros.edit', ['organiser_id' => $organiser_id, 'id' => $teatro->id]) }}" class="btn btn-sm btn-info">Editar</a>
                    <form action="{{ route('teatros.destroy', ['organiser_id' => $organiser_id, 'id' => $teatro->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
	</div>
@stop