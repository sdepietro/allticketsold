<div role="dialog" class="modal fade" style="display: none;">

    @include('ManageOrganiser.Partials.EventCreateAndEditJS');

    {!! Form::open(array('url' => route('postCreateEvent'), 'class' => 'ajax gf', 'files' => true)) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="text-center modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-calendar"></i>
                    @lang("Event.create_event")</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('global_event_id','Evento de la Funcion', array('class'=>'control-label required')) !!}
                            <select name="global_event_id" class="form-control" required>
                                <option value="">Seleccione el evento de la Funcion</option>
                                @foreach ($global_events as $g )
                                <option value="{{ $g->id }}">{{ $g->title }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group">
                            {!! Form::label('title', trans("Event.event_title"), array('class'=>'control-label required')) !!}
                            {!! Form::text('title', old('title'),array('class'=>'form-control','placeholder'=>trans("Event.event_title_placeholder", ["name"=>Auth::user()->first_name]) )) !!}
                        </div>

                        <div class="form-group custom-theme">
                            {!! Form::label('description', trans("Event.event_description"), array('class'=>'control-label required')) !!}
                            {!! Form::textarea('description', old('description'),
                            array(
                            'class'=>'form-control editable',
                            'rows' => 5
                            )) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('country_short', 'Fecha de la Funcion', array('class'=>'control-label required')) !!}
                            {!! Form::input('datetime-local', 'country_short', old('country_short'), array('class'=>'form-control', 'placeholder'=>'DD-MM-AAAA HH:MM')) !!}
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('start_date', trans("Event.event_start_date"), array('class'=>'required control-label')) !!}
                                    {!! Form::text('start_date', old('start_date'),
                                    [
                                    'class'=>'form-control start hasDatepicker ',
                                    'data-field'=>'datetime',
                                    'data-startend'=>'start',
                                    'data-startendelem'=>'.end',
                                    'readonly'=>''

                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('end_date', trans("Event.event_end_date"),
                                    [
                                    'class'=>'required control-label '
                                    ]) !!}

                                    {!! Form::text('end_date', old('end_date'),
                                    [
                                    'class'=>'form-control end hasDatepicker ',
                                    'data-field'=>'datetime',
                                    'data-startend'=>'end',
                                    'data-startendelem'=>'.start',
                                    'readonly'=> ''
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('event_image', trans("Event.imagenminiatura"), array('class'=>'control-label ')) !!}
                            {!! Form::styledFile('event_image') !!}

                        </div>
                        <div class="form-group">
                            {!! Form::label('imagengrande', trans("Event.imagengrande"), array('class'=>'control-label ')) !!}
                            {!! Form::styledFile('imagengrande') !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('imagenminiatura', trans("Event.event_image"), ['class'=>'control-label']) !!}
                            {!! Form::styledFile('imagenminiatura') !!}
                        </div>


                        @if(!empty(config("attendize.google_maps_geocoding_key")))
                        <div class="form-group address-automatic">
                            {!! Form::label('name', trans("Event.venue_name"), array('class'=>'control-label required ')) !!}
                            {!! Form::text('venue_name_full', old('venue_name_full'),
                            array(
                            'class'=>'form-control geocomplete location_field',
                            'placeholder'=>trans("Event.venue_name_placeholder")
                            )) !!}

                            <!--These are populated with the Google places info-->
                            <div>
                                {!! Form::hidden('formatted_address', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('street_number', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('country', '', ['class' => 'location_field']) !!}

                                {!! Form::hidden('place_id', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('name', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('location', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('postal_code', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('route', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('lat', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('lng', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('administrative_area_level_1', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('sublocality', '', ['class' => 'location_field']) !!}
                                {!! Form::hidden('locality', '', ['class' => 'location_field']) !!}
                            </div>
                            <!-- /These are populated with the Google places info-->
                        </div>
                        <div class="address-manual" style="display:none;">
                            <h5>
                                @lang("Event.address_details")
                            </h5>

                            <div class="form-group">
                                {!! Form::label('location_venue_name', trans("Event.venue_name"), array('class'=>'control-label required ')) !!}
                                {!! Form::text('location_venue_name', old('location_venue_name'), [
                                'class'=>'form-control location_field',
                                'placeholder'=>trans("Event.venue_name_placeholder")
                                ]) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('location_address_line_1', trans("Event.address_line_1"), array('class'=>'control-label')) !!}
                                {!! Form::text('location_address_line_1', old('location_address_line_1'), [
                                'class'=>'form-control location_field',
                                'placeholder'=>trans("Event.address_line_1_placeholder")
                                ]) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('location_address_line_2', trans("Event.address_line_2"), array('class'=>'control-label')) !!}
                                {!! Form::text('location_address_line_2', old('location_address_line_2'), [
                                'class'=>'form-control location_field',
                                'placeholder'=>trans("Event.address_line_2_placeholder")
                                ]) !!}
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('location_state', trans("Event.city"), array('class'=>'control-label')) !!}
                                        {!! Form::text('location_state', old('location_state'), [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>trans("Event.city_placeholder")
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('location_post_code', trans("Event.post_code"), array('class'=>'control-label')) !!}
                                        {!! Form::text('location_post_code', old('location_post_code'), [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>trans("Event.post_code_placeholder")
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span>
                            <a data-clear-field=".location_field" data-toggle-class=".address-automatic, .address-manual" data-show-less-text="@lang(" Event.or(manual/existing_venue)") <b>@lang("Event.enter_existing")</b>" href="javascript:void(0);"
                                class="in-form-link show-more-options clear_location">
                                @lang("Event.or(manual/existing_venue)") <b>@lang("Event.enter_manual")</b>
                            </a>
                        </span>
                        @else
                        <div class="address-manual">
                            <h5>
                                @lang("Event.address_details")
                            </h5>

                            <!--<div class="form-group">
                                {!! Form::label('location_venue_name', trans("Event.venue_name"), array('class'=>'control-label required ')) !!}
                                {!!  Form::text('location_venue_name', old('location_venue_name'), [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>trans("Event.venue_name_placeholder")
                                        ])  !!}
                            </div>-->
                            <div class="form-group">
                                {!! Form::label('location_venue_name', 'TEATROS', array('class'=>'control-label required ')) !!}
                                {!! Form::select('location_venue_name',
                                $teatros->pluck('nombre', 'id'), // Crea un array con el ID como clave y la descripción como valor
                                old('teatros_id'),
                                [
                                'class' => 'form-control',
                                'placeholder' => 'SELECCIONAR TEATRO',
                                'required' => 'required'
                                ]
                                ) !!}
                            </div>
                            <div class="form-group" style="display:none">
                                {!! Form::label('location_address_line_1', trans("Event.address_line_1"), array('class'=>'control-label')) !!}
                                {!! Form::text('location_address_line_1', old('location_address_line_1'), [
                                'class'=>'form-control location_field',
                                'placeholder'=>trans("Event.address_line_1_placeholder")
                                ]) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('location_address_line_2', trans("Event.address_line_2"), array('class'=>'control-label required')) !!}
                                {!! Form::select('location_address_line_2',
                                $categorias->pluck('descripcion', 'id'), // Crea un array con el ID como clave y la descripción como valor
                                old('categoria_id'), // Valor seleccionado previamente
                                [
                                'class' => 'form-control',
                                'placeholder' => trans("Event.address_line_2"),
                                'required' => 'required'
                                ]
                                ) !!}
                            </div>
                            <!--<div class="form-group">
                                {!! Form::label('location_address_line_2', trans("Event.address_line_2"), array('class'=>'control-label')) !!}
                                {!!  Form::text('location_address_line_2', old('location_address_line_2'), [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>trans("Event.address_line_2_placeholder")
                                        ])  !!}
                            </div>-->

                            <div class="row">
                                <div class="col-md-6">
                                    <!--<div class="form-group">
                                        {!! Form::label('location_state', 'DESTACADO', array('class'=>'control-label')) !!}
                                        {!!  Form::text('location_state', old('location_state'), [
                                                'class'=>'form-control location_field',
                                                'placeholder'=> 'OTRO'
                                                ])  !!}
                                    </div>-->
                                    <div class="form-group">
                                        {!! Form::label('location_state', 'DESTACADO', ['class' => 'control-label']) !!}
                                        {!! Form::select(
                                        'location_state',
                                        [
                                        'yes' => 'Sí', // Opción con valor 'yes'
                                        'no' => 'No' // Opción con valor 'no'
                                        ],
                                        old('location_state'), // Valor seleccionado por defecto
                                        [
                                        'class' => 'form-control location_field',
                                        'placeholder' => 'SELECCIONE', // Texto del placeholder
                                        'required' => 'required' // Atributo requerido si es necesario
                                        ]
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('location_post_code', trans("Event.post_code"), array('class'=>'control-label')) !!}
                                        {!! Form::text('location_post_code', old('location_post_code'), [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>trans("Event.post_code_placeholder")
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($organiser_id)
                        {!! Form::hidden('organiser_id', $organiser_id) !!}
                        @else
                        <div class="create_organiser" style="{{$organisers->isEmpty() ? '' : 'display:none;'}}">
                            <h5>
                                @lang("Organiser.organiser_details")
                            </h5>

                            <div class="form-group">
                                {!! Form::label('organiser_name', trans("Organiser.organiser_name"), array('class'=>'required control-label ')) !!}
                                {!! Form::text('organiser_name', old('organiser_name'),
                                array(
                                'class'=>'form-control',
                                'placeholder'=>trans("Organiser.organiser_name_placeholder")
                                )) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('organiser_email', trans("Organiser.organiser_email"), array('class'=>'control-label required')) !!}
                                {!! Form::text('organiser_email', old('organiser_email'),
                                array(
                                'class'=>'form-control ',
                                'placeholder'=>trans("Organiser.organiser_email_placeholder")
                                )) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('organiser_about', trans("Organiser.organiser_description"), array('class'=>'control-label ')) !!}
                                {!! Form::textarea('organiser_about', old('organiser_about'),
                                array(
                                'class'=>'form-control editable2',
                                'placeholder'=>trans("Organiser.organiser_description_placeholder"),
                                'rows' => 4
                                )) !!}
                            </div>
                            <div class="form-group more-options">
                                {!! Form::label('organiser_logo', trans("Organiser.organiser_logo"), array('class'=>'control-label ')) !!}
                                {!! Form::styledFile('organiser_logo') !!}
                            </div>
                            <div class="row more-options">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('organiser_facebook', trans("Organiser.organiser_facebook"), array('class'=>'control-label ')) !!}
                                        {!! Form::text('organiser_facebook', old('organiser_facebook'),
                                        array(
                                        'class'=>'form-control ',
                                        'placeholder'=>trans("Organiser.organiser_facebook_placeholder")
                                        )) !!}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('organiser_twitter', trans("Organiser.organiser_twitter"), array('class'=>'control-label ')) !!}
                                        {!! Form::text('organiser_twitter', old('organiser_twitter'),
                                        array(
                                        'class'=>'form-control ',
                                        'placeholder'=>trans("Organiser.organiser_twitter_placeholder")
                                        )) !!}

                                    </div>
                                </div>
                            </div>

                            <a data-show-less-text="@lang(" Organiser.hide_additional_organiser_options")" href="javascript:void(0);" class="in-form-link show-more-options">
                                @lang("Organiser.additional_organiser_options")
                            </a>
                        </div>

                        @if(!$organisers->isEmpty())
                        <div class="form-group select_organiser" style="{{$organisers ? '' : 'display:none;'}}">

                            {!! Form::label('organiser_id', trans("Organiser.select_organiser"), array('class'=>'control-label ')) !!}
                            {!! Form::select('organiser_id', $organisers, $organiser_id, ['class' => 'form-control']) !!}

                        </div>
                        <span class="">
                            @lang("Organiser.or") <a data-toggle-class=".select_organiser, .create_organiser" data-show-less-text="<b>@lang(" Organiser.select_an_organiser")</b>" href="javascript:void(0);"
                                class="in-form-link show-more-options">
                                <b>@lang("Organiser.create_an_organiser")</b>
                            </a>
                        </span>
                        @endif
                        @endif
                        <div>

                        </div>
                    </div>
                </div>
            </div>
            <script>
                // Función para cambiar el texto de la etiqueta <a>
                function cambiarNombre() {

                    // Selecciona el primer elemento con las clases especificadas
                    const boton = document.querySelector('.dtpicker-button.dtpicker-buttonSet');
                    const boton3 = document.querySelector('.dtpicker-title');
                    const boton2 = document.querySelector('.dtpicker-button.dtpicker-buttonClear');

                    const dateValueElement = document.querySelector('.dtpicker-value').style.display = 'none';


                    // Asegúrate de que el elemento existe
                    if (boton) {
                        // Cambia el texto de la etiqueta <a>
                        boton.textContent = 'Agregar';
                    }
                    if (boton2) {
                        // Cambia el texto de la etiqueta <a>
                        boton2.textContent = 'Cancelar';
                        boton2.style.backgroundColor = 'red';
                    }
                    if (boton3) {
                        // Cambia el texto de la etiqueta <a>
                        boton3.textContent = 'Establecer fecha y hora';

                    }
                }

                // Ejecuta la función cambiarNombre cada segundo (500 ms)
                setInterval(cambiarNombre, 10);

            </script>
            <div class="modal-footer">
                <span class="uploadProgress"></span>
                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit(trans("Event.create_event"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
