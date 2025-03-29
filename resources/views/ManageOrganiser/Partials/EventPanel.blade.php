<div class="panel panel-success event">
    <div class="panel-heading" data-style="background-color: {{{$event->bg_color}}};background-image: url({{{$event->bg_image_url}}}); background-size: cover;">
        <div class="event-date">
            <div class="month">
                {{strtoupper(explode("|", trans("basic.months_short"))[$event->start_date->format('n')])}}
            </div>
            <div class="day">
                {{$event->start_date->format('d')}}
            </div>
        </div>
        <ul class="event-meta">
            <li class="event-title">
                <a title="{{{$event->title}}}" href="{{route('showEventDashboard', ['event_id'=>$event->id])}}">
                    {{{ Str::limit($event->title, $limit = 75, $end = '...') }}}
					@if($event->is_live == 1)
						 (Aprobado)
					 @else
						 (Pendiente de aprobación)
					 @endif

                </a>
                <span>  Código de Acceso App : {{ str_pad($event->id,3,0,STR_PAD_LEFT) }}-{{ str_pad($event->account_id ,3,0,STR_PAD_LEFT) }}</span>
            </li>
            <li class="event-organiser">
                By <a href='{{route('showOrganiserDashboard', ['organiser_id' => $event->organiser->id])}}'>{{{$event->organiser->name}}}</a>
                <br>
             Evento: <a href='{{route('showOrganiserGlobalEvents', ['organiser_id' => $event->organiser->id])}}'>{{$event->globalEvent->title}}</a>
            </li>

        </ul>

    </div>
<script>
    var grandTotal = @json($event->tickets);
    console.log(grandTotal);
</script>
    <div class="panel-body">
        <ul class="nav nav-section nav-justified mt5 mb5">
			<li>
                <div class="section">
                    <h4 class="nm">{{ $event->tickets->sum('quantity_sold') }}</h4>
                    <p class="nm text-muted">@lang("Event.tickets_sold")</p>
                </div>
            </li>
			<li>
				<div class="section">
					<h4 class="nm">
					@php
					$restantes = $event->tickets->sum('quantity_available') - $event->tickets->sum('quantity_sold');
					@endphp
						{{ $restantes }}
					</h4>

					<p class="nm text-muted">@lang("Ticket.remaining")</p>
				</div>
			</li>
			 <li>
                <div class="section">
                    <h4 class="nm">{{ $event->getEventRevenueAmount()->display() }}</h4>
                    <p class="nm text-muted">@lang("Event.revenue")</p>
                </div>
            </li>
        </ul>
		<ul class="nav nav-section nav-justified mt5 mb5">


			<li>
                <div class="section">
                    <h4 class="nm">{{ $event->tickets->sum('quantity_available') }}</h4>
                    <p class="nm text-muted">Total entradas</p>
                </div>
            </li>
			@php
				$ereservadas = $event->tickets->sum('quantity_available') - ($event->tickets->sum('quantity_remaining') + $event->tickets->sum('quantity_sold'))
			@endphp
			<li>
                <div class="section">
                    <h4 class="nm">{{ $ereservadas }}</h4>
                    <p class="nm text-muted">Total reservas</p>
                </div>
            </li>



        </ul>
<div style="display: flex;">
    @if ($event->imagengrande)
        <div style="width: 50%;">
            <h4>Banner</h4>
            <img src="{{ url('storage/' . $event->imagengrande) }}" alt="Imagen Grande" style="width: 150px;height: 100px;">
        </div>
    @endif
@if ($event->images->count() > 0)
        <div style="width: 50%;">
            <h4>Miniatura</h4>
            <img src="{{ asset($event->images->first()->image_path) }}" alt="Imagen Miniatura" style="width: 150px;height: 100px;">
        </div>
    @endif
</div>

    </div>
    <div class="panel-footer">
        <ul class="nav nav-section nav-justified">
            <li>
                <a href="{{route('showEventCustomize', ['event_id' => $event->id])}}">
                    <i class="ico-edit"></i> @lang("basic.edit")
                </a>
            </li>

            <li>
                <a href="{{route('showEventDashboard', ['event_id' => $event->id])}}">
                    <i class="ico-cog"></i> @lang("basic.manage")
                </a>
            </li>
			<!--<li>
			<form action="{{ route('events.destroy', ['organiser_id' => $event->organiser->id, 'id' => $event->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: transparent;border: transparent;" onclick="return confirm('¿Estás seguro de que quieres eliminar esta Funcion');"><i class="ico-trash"></i> Eliminar</button>
                        </form>
			</li>-->
			<li>
		           <button type="button" class="btn btn-warning btn-archivar"
            data-organiser-id="{{ $event->organiser->id }}"
            data-event-id="{{ $event->id }}"
            onclick="archivarEvento(this)" style="background: transparent;border: transparent;color: #404675;text-transform: capitalize;font-size: 10pt;">
        <i class="ico-trash"></i> Archivar
    </button>
			</li>
        </ul>
    </div>
	<div>

	</div>

<script>
    function archivarEvento(button) {
        var organiserId = $(button).data('organiser-id');
        var eventId = $(button).data('event-id');
        var url = '{{ route('events.archivar', ['organiser_id' => '__ORGANISER_ID__', 'id' => '__ID__']) }}'
                    .replace('__ORGANISER_ID__', organiserId)
                    .replace('__ID__', eventId);

        // Confirmar la acción antes de continuar
        if (confirm('¿Estás seguro de que deseas archivar este evento?')) {
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
                    alert('Ocurrió un error al archivar el evento.');
                }
            });
        }
    }
</script>

</div>
