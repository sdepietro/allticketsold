<div class="panel panel-success event">
    <div class="panel-heading" data-style="background-color: {{{$event->bg_color}}};background-image: url({{{$event->bg_image_url}}}); background-size: cover;">
        <div class="event-date">
            <div class="month">
                {{strtoupper(explode("|", trans("basic.months_short"))[$event->created_at->format('n')])}}
            </div>
            <div class="day">
                {{$event->created_at->format('d')}}
            </div>
        </div>
        <ul class="event-meta">
            <li class="event-title">
                <a title="{{{$event->title}}}" href="{{route('showOrganiserEventsFunciones', ['organiser_id'=>$event->organiser->id,'id_globalEvent'=>$event->id])}}">
                    {{{ Str::limit($event->title, $limit = 75, $end = '...') }}}
                    @if($event->estado == 'Activo')
                    (Activo)
                    @else
                    (Desactivado)
                    @endif
                </a>
            </li>
            <li class="event-organiser">
                By <a href='{{route('showOrganiserDashboard', ['organiser_id' => $event->organiser->id])}}'>{{{$event->organiser->name}}}</a>
            </li>

        </ul>

    </div>
    <script>
        //   var grandTotal = @json($event->tickets);
        //   console.log(grandTotal);

    </script>
    <div class="panel-body">
        {{-- <ul class="nav nav-section nav-justified mt5 mb5">
			<li>
                <div class="section">
                    <h4 class="nm">Cat</h4>
                    <p class="nm text-muted">@lang("Event.tickets_sold")</p>
                </div>
            </li>
			<li>
				<div class="section">
					<h4 class="nm">

						Resta
					</h4>

					<p class="nm text-muted">@lang("Ticket.remaining")</p>
				</div>
			</li>
			 <li>
                <div class="section">
                    <h4 class="nm">bbbbb</h4>
                    <p class="nm text-muted">@lang("Event.revenue")</p>
                </div>
            </li>
        </ul>
		<ul class="nav nav-section nav-justified mt5 mb5">


			<li>
                <div class="section">
                    <h4 class="nm">xxx</h4>
                    <p class="nm text-muted">Total entradas</p>
                </div>
            </li>

			<li>
                <div class="section">
                    <h4 class="nm">yyy</h4>
                    <p class="nm text-muted">Total reservas</p>
                </div>
            </li>



        </ul> --}}
        <div style="display: flex;">

            <div style="width: 50%;">
                <h4>Banner</h4>
                <img src="{{ $event->img_main }}" alt="Imagen Grande" style="width: 150px;height: 100px;">
            </div>


            <div style="width: 50%;">
                <h4>Miniatura</h4>
                <img src="{{ asset($event->img_mini) }}" alt="Imagen Miniatura" style="width: 150px;height: 100px;">
            </div>

        </div>

    </div>
    <div class="panel-footer">
        <ul class="nav nav-section nav-justified">
            <li>
                <a href="{{route('showOrganiserEventsFunciones', ['organiser_id'=>$event->organiser->id,'id_globalEvent'=>$event->id])}}">
                    <i class="ico-ticket"></i> Funciones {{ count( $event->event) }}
                </a>
            </li>
            <li>
                <a href="{{route('showGlobalEventCustomize', ['event_id' => $event->id])}}">
                    <i class="ico-edit"></i> @lang("basic.edit")
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
                {{-- <button type="button" class="btn btn-warning btn-archivar" data-organiser-id="{{ $event->organiser->id }}" data-event-id="{{ $event->id }}" onclick="archivarEvento(this)" style="background: transparent;border: transparent;color: #404675;text-transform: capitalize;font-size: 10pt;">
                    <i class="ico-trash"></i> Archivar
                </button> --}}
            </li>
        </ul>
    </div>
    <div>

    </div>



</div>
