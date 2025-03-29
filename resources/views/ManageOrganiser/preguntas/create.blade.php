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
                <a href="{{ route('preguntas.index', ['organiser_id' => $organiser_id]) }}"  class="btn btn-success"><i class="ico-arrow-left"></i> Volver a la lista</a>
            </div>
        </div>
    </div>
@stop
@section('content')
 <h1>Crear Pregunta</h1>
@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="preguntaForm" action="{{ route('preguntas.store', $organiser_id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="pregunta" class="control-label">Pregunta</label>
            <input class="form-control"  type="text" name="pregunta" value="{{ old('pregunta') }}" required>
        </div>
        <div class="form-group">
            <label for="respuesta" class="control-label">Respuesta</label>
            <textarea class="form-control"  name="respuesta" required>{{ old('respuesta') }}</textarea>
        </div>
        <div class="form-group" style="display:none">
            <label for="activado" class="control-label">Activado</label>
            <input  type="checkbox" name="activado" {{ old('activado') ? 'checked' : '' }}>
        </div>
        <button class="btn btn-success" type="submit">Guardar</button>
    </form>
	
	<script>
        document.getElementById('preguntaForm').onsubmit = function() {
            var checkbox = document.getElementById('activadoCheckbox');
            if (checkbox.checked) {
                checkbox.value = '1'; // Asegura que el valor sea 1 si está marcado
            } else {
                checkbox.value = '0'; // Asegura que el valor sea 0 si no está marcado
            }
        };
    </script>
@stop