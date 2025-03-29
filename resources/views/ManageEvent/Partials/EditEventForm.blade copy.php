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
		<div class="form-group">
			{!! Form::label('country_short', 'Fecha de la Funcion', ['class' => 'control-label']) !!}

			@if(!empty($event) && isset($event->location_country_code) && $event->location_country_code)
				@php
					try {
						$formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $event->location_country_code)->format('Y-m-d\TH:i');
					} catch (\Exception $e) {
						$formattedDate = ''; // Si hay un error en el formato, se asigna un valor vacío.
					}
				@endphp
				{!! Form::input('datetime-local', 'country_short', $formattedDate,
					['class' => 'form-control', 'placeholder' => 'DD-MM-AAAA HH:MM']) !!}
			@else
				{!! Form::input('datetime-local', 'country_short', '',
					['class' => 'form-control', 'placeholder' => 'DD-MM-AAAA HH:MM']) !!}
			@endif
		</div>
		<div class="form-group">
            {!! Form::label('name', 'Promociones (Separar con ";" los bines ingresados', array('class'=>'control-label required ')) !!}
            {!!  Form::text('country', $event->location_country,
                                        array(
                                        'class'=>'form-control',
                                        'placeholder'=> 'Ej: 4300,4500'
                                        ))  !!}
			</div>




        <div class="form-group address-automatic" style="display:{{$event->location_is_manual ? 'none' : 'block'}};">
            {!! Form::label('name', trans("Event.venue_name"), array('class'=>'control-label required ')) !!}
            {!!  Form::text('venue_name_full', old('venue_name_full'),
                                        array(
                                        'class'=>'form-control geocomplete location_field',
                                        'placeholder'=>trans("Event.venue_name_placeholder")//'E.g: The Crab Shack'
                                        ))  !!}

            <!--These are populated with the Google places info-->
            <div>
               {!! Form::text('formatted_address', $event->location_address, ['class' => 'location_field']) !!}
               {!! Form::text('street_number', $event->location_street_number, ['class' => 'location_field']) !!}
               {!! Form::text('locality', $event->location_address_line_1, ['class' => 'location_field']) !!}

               {!! Form::text('place_id', $event->location_google_place_id, ['class' => 'location_field']) !!}
               {!! Form::text('name', $event->venue_name, ['class' => 'location_field']) !!}
               {!! Form::text('location', '', ['class' => 'location_field']) !!}
               {!! Form::text('postal_code', $event->location_post_code, ['class' => 'location_field']) !!}
               {!! Form::text('route', $event->location_address_line_1, ['class' => 'location_field']) !!}
               {!! Form::text('lat', $event->location_lat, ['class' => 'location_field']) !!}
               {!! Form::text('lng', $event->location_long, ['class' => 'location_field']) !!}
               {!! Form::text('administrative_area_level_1', $event->location_state, ['class' => 'location_field']) !!}
               {!! Form::text('sublocality', '', ['class' => 'location_field']) !!}

            </div>
            <!-- /These are populated with the Google places info-->

        </div>

        <div class="address-manual" style="display:block;">
            <!--<div class="form-group">
                {!! Form::label('location_venue_name', trans("Event.venue_name"), array('class'=>'control-label required ')) !!}
                {!!  Form::text('location_venue_name', $event->venue_name, [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>trans("Event.venue_name_placeholder") // same as above
                            ])  !!}
            </div>-->

		<div class="form-group">
		{!! Form::label('location_venue_name', 'TEATROS', array('class'=>'control-label required ')) !!}
		{!! Form::select('location_venue_name',
		$teatros->pluck('nombre', 'id'), // Crea un array con el ID como clave y la descripción como valor
        	$event->venue_name, // Valor guardado o valor antiguo (en caso de validación fallida)
        	[
            	'class' => 'form-control',
            	'placeholder' => 'SELECCIONAR TEATRO',
            	'required' => 'required'
        	]) !!}
		</div>
            <div class="form-group">
                {!! Form::label('location_address_line_1', 'ARCHIVADO', array('class'=>'control-label')) !!}
                {!!  Form::text('location_address_line_1', $event->location_address_line_1, [
                                        'class'=>'form-control location_field'

                            ])  !!}
            </div>
            <!--<div class="form-group">
                {!! Form::label('location_address_line_2', trans("Event.address_line_2"), array('class'=>'control-label')) !!}
                {!!  Form::text('location_address_line_2', $event->location_address_line_2, [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>trans("Event.address_line_2_placeholder")//'E.g: Dublin.'
                            ])  !!}
            </div>-->
		<div class="form-group">
   		{!! Form::label('location_address_line_2', trans("Event.address_line_2"), array('class'=>'control-label required')) !!}
    		{!! Form::select('location_address_line_2',
      		$categorias->pluck('descripcion', 'id'), // Crea un array con el ID como clave y la descripción como valor
       		$event->location_address_line_2, // Valor seleccionado previamente
       		[
         	'class' => 'form-control',
         	'placeholder' => trans("Event.address_line_2"),
		'required' => 'required'
      		]
   		) !!}
		</div>

            <div class="row">
                <div class="col-md-6">
                    <!--<div class="form-group">
                        {!! Form::label('location_state', 'DESTACADO', array('class'=>'control-label')) !!}
                        {!!  Form::text('location_state', $event->location_state, [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>trans("Event.city_placeholder")//'E.g: Dublin.'
                            ])  !!}
                    </div>-->
		<div class="form-group">
    		{!! Form::label('location_state', 'DESTACADO', ['class' => 'control-label']) !!}
    		{!! Form::select(
        	'location_state', // Nombre del campo en el formulario
        	[
            	'yes' => 'Sí', // Opción con valor 'yes'
            	'no' => 'No'   // Opción con valor 'no'
        	],
        	old('location_state', $event->location_state), // Valor seleccionado por defecto
       		 [
            	'class' => 'form-control location_field',
            	'placeholder' => 'SELECCIONE', // Texto del placeholder
            	'required' => 'required' // Añade el atributo required si es necesario
        	]
    		) !!}
		</div>
                </div>
                <div class="col-md-6">
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

        <div class="clearfix" style="margin-top:-10px; padding: 5px; padding-top: 0px;">
            <span class="pull-right">
                @lang("Event.or(manual/existing_venue)") <a data-clear-field=".location_field" data-toggle-class=".address-automatic, .address-manual" data-show-less-text="{{$event->location_is_manual ? trans("Event.enter_manual"):trans("Event.enter_existing")}}" href="javascript:void(0);" class="show-more-options clear_location">{{$event->location_is_manual ? trans("Event.enter_existing"):trans("Event.enter_manual")}}</a>
            </span>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('start_date', trans("Event.event_start_date"), array('class'=>'required control-label')) !!}
                    {!!  Form::text('start_date', $event->getFormattedDate('start_date'),
                                                        [
                                                    'class'=>'form-control start hasDatepicker ',
                                                    'data-field'=>'datetime',
                                                    'data-startend'=>'start',
                                                    'data-startendelem'=>'.end',
                                                    'readonly'=>''

                                                ])  !!}
                </div>
            </div>

            <div class="col-sm-6 ">
                <div class="form-group">
                    {!!  Form::label('end_date', trans("Event.event_end_date"),
                                        [
                                    'class'=>'required control-label '
                                ])  !!}
                    {!!  Form::text('end_date', $event->getFormattedDate('end_date'),
                                                [
                                            'class'=>'form-control end hasDatepicker ',
                                            'data-field'=>'datetime',
                                            'data-startend'=>'end',
                                            'data-startendelem'=>'.start',
                                            'readonly'=>''
                                        ])  !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                   {!! Form::label('event_image', trans("Event.imagenminiatura"), array('class'=>'control-label ')) !!}
                   {!! Form::styledFile('event_image', 1) !!}
                </div>
		<div class="form-group">
    		{!! Form::label('imagengrande', trans("Event.imagengrande"), array('class'=>'control-label ')) !!}
    		{!! Form::styledFile('imagengrande') !!}
		</div>

		<div class="form-group">
       		{!! Form::label('imagenminiatura', trans("Event.event_flyer"), ['class'=>'control-label']) !!}
        	{!! Form::styledFile('imagenminiatura') !!}
    		</div>

                @if($event->images->count())
                    <div class="form-group" style="display:none">
                        {!! Form::label('event_image_position', trans("Event.event_image_position"), array('class'=>'control-label')) !!}
                        {!! Form::select('event_image_position', [
                                '' => trans("Event.event_image_position_hide"),
                                'before' => trans("Event.event_image_position_before"),
                                'after' => trans("Event.event_image_position_after"),
                                'left' => trans("Event.event_image_position_left"),
                                'right' => trans("Event.event_image_position_right"),
                            ],
                            old('event_image_position'),
                            ['class'=>'form-control']
                        ) !!}
                    </div>
                    {!! Form::label('', trans("Event.current_event_flyer"), array('class'=>'control-label ', 'style' => 'display: none;')) !!}
                    <div class="form-group" style="display:none">
                        <div class="well well-sm well-small">
                           {!! Form::label('remove_current_image', trans("Event.delete?"), array('class'=>'control-label ')) !!}
                           {!! Form::checkbox('remove_current_image') !!}

                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <div class="float-l">
                    @if($event->images->count())
                    <div class="thumbnail">
			<p>Imagen Miniatura</p>
                       {!!Html::image('/'.$event->images->first()['image_path'], null, ['width' => '200px']) !!}
                    </div>
                    @endif
                </div>
<div class="float-l">
                    @if($event->images->count())
                    <div class="thumbnail">
			<p>Imagen Grande</p>
                       <img src="{{ url('storage/' . $event->imagengrande) }}" alt="Imagen Grande" style="width: 200px;height: 100px;">
                    </div>
                    @endif
                </div>
<div class="float-l">
                    @if($event->images->count())
                    <div class="thumbnail">
			<p>Imagen Sipnosis</p>
                       <img src="{{ url('storage/' . $event->imagenminiatura) }}" alt="Imagen Miniatura" style="width: 200px;height: 100px;">
                    </div>
                    @endif
                </div>
            </div>

        </div>
        <div class="row" style="display:none">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('google_tag_manager_code', trans("Organiser.google_tag_manager_code"), ['class'=>'control-label']) !!}
                    {!!  Form::text('google_tag_manager_code', old('google_tag_manager_code'), [
                            'class'=>'form-control',
                            'placeholder' => trans("Organiser.google_tag_manager_code_placeholder"),
                        ])
                    !!}
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

