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
 		<a href="{{ route('categorias.create', $organiser_id) }}" class="btn btn-success"><i class="ico-plus"></i> Crear Categoría</a>
               
            </div>
        </div>
    </div>
@stop
@section('content')
    <h1>Categorías</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

   

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
				<th>Posición</th>
                <th>Imagen</th>
                <th>Activado EN PANTALLA PRINCIPAL</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->descripcion }}</td>
					<td>{{ $categoria->posicion }}</td>
                    <td>
                        @if($categoria->imagen)
                            <img src="{{ asset('storage/' . $categoria->imagen) }}" alt="Imagen" class="img-thumbnail" style="max-width: 100px;">
                        @else
                            No disponible
                        @endif
                    </td>
                    <td>{{ $categoria->activado == 1 ? 'Sí' : 'No' }}</td>
                    <td>
                        <a href="{{ route('categorias.show', ['organiser_id' => $organiser_id, 'id' => $categoria->id]) }}" class="btn btn-info">Ver</a>
                        <a href="{{ route('categorias.edit', ['organiser_id' => $organiser_id, 'id' => $categoria->id]) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('categorias.destroy', ['organiser_id' => $organiser_id, 'id' => $categoria->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar esta categoría?');">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop