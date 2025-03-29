@include('ManageOrganiser.Partials.EventCreateAndEditJS')

{!! Form::model($event, array('url' => route('postEditEvent', ['event_id' => $event->id]), 'class' => 'ajax gf')) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('global_event_id','Evento de la Funcion',  array('class'=>'control-label required')) !!}
          <select name="global_event_id" class="form-control"  required>
            <option value="">Seleccione el evento de la Funcion</option>
            @foreach ($global_events as $g )
                <option value="{{ $g->id }}" @if($event->global_event_id == $g->id) selected @endif>{{ $g->title }}</option>
            @endforeach

          </select>
        </div>
        <div class="form-group">
          {!! Form::label('currency_id', trans("ManageEvent.default_currency"), array('class'=>'control-label required')) !!}
          {!! Form::select('currency_id', $currencies, $event->currency_id, ['class' => 'form-control', 'style' => 'display:none;']) !!}
	  <input type="text" value="PESOS ARGENTINOS" class="form-control" readonly/>
        </div>
        <div class="form-group">
            {!! Form::label('is_live', trans("Event.event_visibility"), array('class'=>'control-label required')) !!}
            {!!  Form::select('is_live', [
            '1' => trans("Event.vis_public"),
            '0' => trans("Event.vis_hide")],null,
                                        array(
                                        'class'=>'form-control'
                                        ))  !!}
        </div>
        <div class="form-group">
            {!! Form::label('title', trans("Event.event_title"), array('class'=>'control-label required')) !!}
            {!!  Form::text('title', old('title'),
                                        array(
                                        'class'=>'form-control',
                                        'placeholder'=>trans("Event.event_title_placeholder", ["name"=>Auth::user()->first_name])
                                        ))  !!}
        </div>

        <div class="form-group" style="display: none">
           {!! Form::label('description', trans("Event.event_description"), array('class'=>'control-label')) !!}
            {!!  Form::textarea('description', old('description'),
                                        array(
                                        'class'=>'form-control editable',
                                        'rows' => 5
                                        ))  !!}
        </div>






         <input type="text" value="{{$event->globalEvent->teatro->nombre  }}" style="display: none">


            <div class="row">
                <div class="col-md-6">
                    <!--<div class="form-group">
                        {!! Form::label('location_state', 'DESTACADO', array('class'=>'control-label')) !!}
                        {!!  Form::text('location_state', $event->location_state, [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>trans("Event.city_placeholder")//'E.g: Dublin.'
                            ])  !!}
                    </div>-->

                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('location_post_code', trans("Event.post_code"), array('class'=>'control-label')) !!}
                        {!!  Form::text('location_post_code', $event->location_post_code, [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>trans("Event.post_code_placeholder")// 'E.g: 94568.'
                            ])  !!}
                    </div>
                </div>
            </div>
        </div>


    <div class="col-md-12">
        <div class="text-right panel-footer mt15">
           {!! Form::hidden('organiser_id', $event->organiser_id) !!}
           {!! Form::submit(trans("Event.save_changes"), ['class'=>"btn btn-success"]) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

