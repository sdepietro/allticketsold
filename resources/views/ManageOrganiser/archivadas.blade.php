@extends('Shared.Layouts.Master')

@section('title')
    @parent
    {{ trans('Organiser.organiser_events') }}
@stop

@section('page_title')
    {{ trans('Organiser.organiser_name_events', ['name'=>$organiser->name]) }}
@stop

@section('top_nav')
    @include('ManageOrganiser.Partials.TopNav')
@stop

@section('head')
    {!! Html::script('https://maps.googleapis.com/maps/api/js?libraries=places&key='.config("attendize.google_maps_geocoding_key")) !!}
    {!! Html::script('vendor/geocomplete/jquery.geocomplete.min.js')!!}

@stop

@section('menu')
    @include('ManageOrganiser.Partials.Sidebar')
@stop

@section('page_header')
    <div class="col-md-9">
        <div class="btn-toolbar">
            <div class="btn-group btn-group-responsive">

            </div>
        </div>
    </div>
    <div class="col-md-3">

    </div>
@stop

@section('content')

@if($events2->count())
        <div class="row">
            <div class="col-md-3 col-xs-6">
                <div class="order_options">
                    <span class="event_count">
                       <h4>Funciones Archivadas</h4>
                    </span>
                </div>
            </div>

        </div>
    @endif
	<div class="row">
        @if($events2->count())
            @foreach($events2 as $event2)
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
       <div class="panel panel-success event">
    <div class="panel-heading" data-style="background-color: {{{$event2->bg_color}}};background-image: url({{{$event2->bg_image_url}}}); background-size: cover;">
        <div class="event-date">
            <div class="month">
                {{strtoupper(explode("|", trans("basic.months_short"))[$event2->start_date->format('n')])}}
            </div>
            <div class="day">
                {{$event2->start_date->format('d')}}
            </div>
        </div>
        <ul class="event-meta">
            <li class="event-title">
                <a title="{{{$event2->title}}}" href="{{route('showEventDashboard', ['event_id'=>$event2->id])}}">
                    {{{ Str::limit($event2->title, $limit = 75, $end = '...') }}}
                </a>
            </li>
            <li class="event-organiser">
                By <a href='{{route('showOrganiserDashboard', ['organiser_id' => $event2->organiser->id])}}'>{{{$event2->organiser->name}}}</a>
            </li>
        </ul>

    </div>

    <div class="panel-body">
        <ul class="nav nav-section nav-justified mt5 mb5">
            <li>
                <div class="section">
                    <h4 class="nm">{{ $event2->tickets->sum('quantity_sold') }}</h4>
                    <p class="nm text-muted">@lang("Event.tickets_sold")</p>
                </div>
            </li>

            <li>
                <div class="section">
                    <h4 class="nm">{{ $event2->getEventRevenueAmount()->display() }}</h4>
                    <p class="nm text-muted">@lang("Event.revenue")</p>
                </div>
            </li>
        </ul>
<div style="display: flex;">
    @if ($event2->imagengrande)
        <div style="width: 50%;">
            <h4>Banner</h4>
            <img src="{{ url('storage/' . $event2->imagengrande) }}" alt="Imagen Grande" style="width: 150px;height: 100px;">
        </div>
    @endif
@if ($event2->imagenminiatura)
        <div style="width: 50%;">
            <h4>Miniatura</h4>
            <img src="{{ url('storage/' . $event2->images->first()->image_path) }}" alt="Imagen Miniatura" style="width: 150px;height: 100px;">
        </div>
    @endif
</div>

    </div>
    <div class="panel-footer">
        <ul class="nav nav-section nav-justified">
            <li>
                <a href="{{route('showEventCustomize', ['event_id' => $event2->id])}}">
                    <i class="ico-edit"></i> @lang("basic.edit")
                </a>
            </li>

            <li>
                <a href="{{route('showEventDashboard', ['event_id' => $event2->id])}}">
                    <i class="ico-cog"></i> @lang("basic.manage")
                </a>
            </li>
			<!--<li>
			<form action="{{ route('events.destroy', ['organiser_id' => $event2->organiser->id, 'id' => $event2->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: transparent;border: transparent;" onclick="return confirm('¿Estás seguro de que quieres eliminar esta Funcion');"><i class="ico-trash"></i> Eliminar</button>
                        </form>
			</li>-->
<li>
		           <button type="button" class="btn btn-warning btn-archivar"
            data-organiser-id="{{ $event2->organiser->id }}"
            data-event-id="{{ $event2->id }}"
            onclick="archivarEvento(this)" style="background: transparent;border: transparent;color: #404675;text-transform: capitalize;font-size: 10pt;">
        <i class="ico-trash"></i> Desarchivar
    </button>
			</li>
        </ul>
    </div>
	<div>
	</div>
</div>
                </div>
            @endforeach
        @endif

<script>
    function archivarEvento(button) {
        var organiserId = $(button).data('organiser-id');
        var eventId = $(button).data('event-id');
        var url = '{{ route('events.desarchivar', ['organiser_id' => '__ORGANISER_ID__', 'id' => '__ID__']) }}'
                    .replace('__ORGANISER_ID__', organiserId)
                    .replace('__ID__', eventId);

        // Confirmar la acción antes de continuar
        if (confirm('¿Estás seguro de que deseas desarchivar este evento?')) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'POST', // Asegúrate de usar POST si está definido como POST en la ruta
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Mostrar un único mensaje de éxito y recargar la página
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('Ocurrió un error al desarchivar el evento.');
                }
            });
        }
    }
</script>
    </div>
@stop
