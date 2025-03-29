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
            <div class="btn-group btn-group-responsive" style="width: 100%;display: flex;align-items: center;">
                Seleccione la productora para filtrar
                <select id="organiserSelect" class="form-control" style="width: auto;margin-left: 30px;">
                    <option value="">Todos</option>
                    @foreach($organizador as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
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
                <th>Titulo Funcion</th>
                <th>Organizador</th>
                <th>Estado</th>

            </tr>
        </thead>
        <tbody id="tablaClientes">
            @foreach($clientes as $cliente)
            <tr>
                <td>{{ $cliente->id }}</td>
                <td>{{ $cliente->title }}</td>
                <td>{{ $cliente->organiser_name }}</td>
                <td>
				@if ($cliente->is_live == 0)
					<a style="margin-right: 20px;" class="btn btn-xs btn-danger"
					   href="#" onclick="confirmAprobar('{{ route('perfil.aprobar', ['organiser_id' => $organiser_id, 'id_evento' => $cliente->id]) }}')">
					   Pendiente
					</a>
				@else
					Aprobado
				@endif
			</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function confirmAprobar(url) {
        // Mostrar un mensaje de confirmación
        if (confirm("¿Desea aprobar esta Funcion?")) {
            // Redirigir al enlace si se confirma
            window.location.href = url;
        }
    }
</script>

<script>
    document.getElementById('organiserSelect').addEventListener('change', function() {
        let organiserId = this.value;

        // Realizar la solicitud AJAX solo si se selecciona un organizador
        if (organiserId) {
            // Usar el nombre de la ruta para generar la URL
            let url = "{{ route('filter.obras', ':organiserId') }}".replace(':organiserId', organiserId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Limpiar la tabla
                    let tablaClientes = document.getElementById('tablaClientes');
                    tablaClientes.innerHTML = '';

                    // Llenar la tabla con los nuevos datos
                    data.clientes.forEach(cliente => {
                        let row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${cliente.id}</td>
                            <td>${cliente.title}</td>
                            <td>${cliente.organiser_name}</td>
                            <td>
                                ${cliente.is_live == 0 ?
                                    `<a style="margin-right: 20px;" class="btn btn-xs btn-danger" href="#" onclick="confirmAprobar('${cliente.aprobar_url}')">Pendiente</a>`
                                    :
                                    'Aprobado'
                                }
                            </td>
                        `;
                        tablaClientes.appendChild(row);
                    });
                });
        } else {
            // Si no se selecciona un organizador, recargar la página para mostrar todas las Funcion
            window.location.reload();
        }
    });
</script>

@stop