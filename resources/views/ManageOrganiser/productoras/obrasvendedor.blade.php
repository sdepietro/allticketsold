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
                <a href="{{ route('showOrganiserRpublicas', ['organiser_id' => $organiser_id]) }}"  class="btn btn-success"><i class="ico-arrow-left"></i> Volver a la lista</a>
            </div>
        </div>
    </div>
@stop
@section('content')

        <div class="panel-body">
            <h2>Asignar Funcion</h2>
            <br>
            <div class="row">
              <div class="table-responsive ">
					<table class="table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Funcion</th>
								<th>Url</th>

						<th>Accion</th>
							</tr>
						</thead>
						<tbody>

				@foreach($clientes as $cliente)
							<tr>
								<td>{{ $cliente->id }}</td>
								<td>{{ $cliente->title }}</td>
								<td><a href="{{ $cliente->url }}" target="_blank">{{ $cliente->url }}</a>
								<button onclick="copiarUrl('{{ $cliente->url }}')" class="btn btn-primary btn-sm" style="margin-left: 20px;">Copiar</button>
								</td>

						<td class="text-center" style="display: flex;">
						@if ($cliente->asignacion == 1)
								<form action="{{ route('asignar.eliminar', ['organiser_id' => $organiser_id, 'id' => $vendedor->id, 'aid' => $cliente->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta asginación?');">
									@csrf
									@method('DELETE')
									<button type="submit" class="btn btn-danger">Eliminar</button>
								</form>
						@else
							<form action="{{ route('vendedor.asignar', ['organiser_id' => $organiser_id, 'id' => $vendedor->id, 'obra_id' => $cliente->id]) }}" method="GET">
								@csrf
								<button type="submit" class="btn btn-primary">Asignar</button>
							</form>
						@endif

							 </td>
							</tr>

							@endforeach
						</tbody>
					</table>

				</div>

            </div>
        </div>
<script>
    function copiarUrl(url) {
        // Crear un elemento de texto temporal para copiar la URL
        var tempInput = document.createElement("input");
        tempInput.value = url;
        document.body.appendChild(tempInput);

        // Seleccionar y copiar el contenido del input
        tempInput.select();
        document.execCommand("copy");

        // Eliminar el input temporal
        document.body.removeChild(tempInput);

        // Opcional: Notificar al usuario que la URL ha sido copiada
        alert("URL copiada al portapapeles!");
    }
</script>

@stop