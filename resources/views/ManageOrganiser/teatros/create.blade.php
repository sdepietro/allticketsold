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
                <a href="{{ route('teatros.index', ['organiser_id' => $organiser_id]) }}"  class="btn btn-success"><i class="ico-arrow-left"></i> Volver a la lista</a>
            </div>
        </div>
    </div>
@stop
@section('content')
    <h1>Crear Nuevo Teatro</h1>
    
    <form action="{{ route('teatros.store', ['organiser_id' => $organiser_id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label class="control-label" for="nombre">Nombre</label>
            <input class="form-control" type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
            @error('nombre')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label  class="control-label" for="direccion">Dirección</label>
            <input class="form-control" type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}" required>
            @error('direccion')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="control-label" for="coordenadas">Coordenadas</label>
            <input class="form-control" type="text" name="coordenadas" id="coordenadas" class="form-control" value="{{ old('coordenadas') }}" required>
            @error('coordenadas')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="control-label" for="imagen">Imagen</label>
            <input class="form-control" type="file" name="imagen" id="imagen" class="form-control">
            @error('imagen')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
		
		<div class="form-group">
            <label for="map">Ubicación en el Mapa</label>
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Teatro</button>
        <a href="{{ route('teatros.index', ['organiser_id' => $organiser_id]) }}" class="btn btn-secondary">Cancelar</a>
    </form>
	
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDf74Ze1MYbnlYrCTwc7x7a6UnrmrqsUHs&libraries=places&callback=initMap" async defer></script>
<script>
   /* let map;
    let marker;
    let geocoder;

    function initMap() {
        // Ubicación inicial en Uruguay (Montevideo)
        const initialLocation = { lat: -34.9075, lng: -56.1659 };

        map = new google.maps.Map(document.getElementById('map'), {
            center: initialLocation,
            zoom: 12,
        });

        marker = new google.maps.Marker({
            position: initialLocation,
            map: map,
            draggable: true,
        });

        geocoder = new google.maps.Geocoder();

        // Cuando se mueve el marcador, actualizar el campo de coordenadas y dirección
        google.maps.event.addListener(marker, 'dragend', function(event) {
            updateCoordinatesAndAddress(event.latLng);
        });

        // Cuando se hace clic en el mapa, actualizar el marcador, coordenadas y dirección
        map.addListener('click', function(event) {
            marker.setPosition(event.latLng);
            updateCoordinatesAndAddress(event.latLng);
        });
    }

    function updateCoordinatesAndAddress(latLng) {
        // Actualizar el campo de coordenadas
        document.getElementById('coordenadas').value = latLng.lat() + ', ' + latLng.lng();

        // Obtener la dirección a partir de las coordenadas
        geocoder.geocode({ location: latLng }, function(results, status) {
            if (status === 'OK' && results[0]) {
                document.getElementById('direccion').value = results[0].formatted_address;
            } else {
                document.getElementById('direccion').value = 'Dirección no disponible';
            }
        });
    }*/

  let map;
        let marker;
        let geocoder;
        let autocomplete;

        function initMap() {
            // Ubicación inicial en Uruguay (Montevideo)
            const initialLocation = { lat: -34.9075, lng: -56.1659 };

            map = new google.maps.Map(document.getElementById('map'), {
                center: initialLocation,
                zoom: 12,
            });

            marker = new google.maps.Marker({
                position: initialLocation,
                map: map,
                draggable: true,
            });

            geocoder = new google.maps.Geocoder();

            // Configurar el autocompletado para el campo de dirección
            const input = document.getElementById('direccion');
            autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.setFields(['address_component', 'geometry']);

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                if (place.geometry) {
                    const location = place.geometry.location;
                    map.setCenter(location);
                    marker.setPosition(location);
                    updateCoordinatesAndAddress(location);
                } else {
                    alert('Dirección no encontrada');
                }
            });

            // Manejar el evento de movimiento del marcador
            google.maps.event.addListener(marker, 'dragend', function(event) {
                updateCoordinatesAndAddress(event.latLng);
            });

            // Manejar el evento de clic en el mapa
            map.addListener('click', function(event) {
                marker.setPosition(event.latLng);
                updateCoordinatesAndAddress(event.latLng);
            });
        }

        function updateCoordinatesAndAddress(latLng) {
            // Actualizar el campo de coordenadas
            document.getElementById('coordenadas').value = `${latLng.lat()}, ${latLng.lng()}`;

            // Obtener la dirección a partir de las coordenadas
            geocoder.geocode({ location: latLng }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    document.getElementById('direccion').value = results[0].formatted_address;
                } else {
                    document.getElementById('direccion').value = 'Dirección no disponible';
                }
            });
        }
</script>

@stop