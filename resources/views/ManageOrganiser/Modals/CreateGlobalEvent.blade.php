<div role="dialog" class="modal fade" style="display: none;">

    @include('ManageOrganiser.Partials.EventCreateAndEditJS');

    {!! Form::open(array('url' => route('postCreateGlobalEvent'), 'class' => 'ajax gf', 'files' => true)) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="text-center modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-event"></i>
                    Crear Evento
                </h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('categorias','Categorias del Evento',  array('class'=>'control-label required')) !!}
                          <select name="categorias[]" class="form-control" multiple  required>

                            @foreach ($categorias as $g )
                                <option value="{{ $g->id }}">{{ $g->descripcion }}</option>
                            @endforeach
                          </select>
                        </div>


                        <div class="form-group">
                            {!! Form::label('teatro_id','Teatro',  array('class'=>'control-label required')) !!}
                          <select name="teatro_id" class="form-control" required>

                            @foreach ($teatros as $t )
                                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group">
                            {!! Form::label('title', 'Titulo del Evento', array('class'=>'control-label required')) !!}
                            {!! Form::text('title', old('title'),array('class'=>'form-control','placeholder'=>trans("Event.event_title_placeholder", ["name"=>Auth::user()->first_name]) )) !!}
                        </div>
                        <div class="form-group custom-theme">
                            {!! Form::label('description', 'Descripcion', array('class'=>'control-label required')) !!}
                            {!! Form::textarea('description', old('description'),
                            array(
                            'class'=>'form-control editable',
                            'rows' => 5
                            )) !!}
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('start_date', trans("Event.event_start_date"), array('class'=>'required control-label')) !!}
                                    {!!  Form::text('start_date', old('start_date'),
                                                        [
                                                    'class'=>'form-control start hasDatepicker ',
                                                    'data-field'=>'datetime',
                                                    'data-startend'=>'start',
                                                    'data-startendelem'=>'.end',
                                                    'readonly'=>''

                                                ])  !!}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!!  Form::label('end_date', trans("Event.event_end_date"),
                                                [
                                            'class'=>'required control-label '
                                        ])  !!}

                                    {!!  Form::text('end_date', old('end_date'),
                                                [
                                            'class'=>'form-control end hasDatepicker ',
                                            'data-field'=>'datetime',
                                            'data-startend'=>'end',
                                            'data-startendelem'=>'.start',
                                            'readonly'=> ''
                                        ])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('event_image', trans("Event.imagenminiatura"), array('class'=>'control-label ')) !!}
                            {!! Form::styledFile('img_mini') !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('imagengrande', trans("Event.imagengrande"), array('class'=>'control-label ')) !!}
                            {!! Form::styledFile('img_main') !!}
                        </div>


                        <div class="form-group">
                            {!! Form::label('img_sinopsi', 'Imagen Sinopsi', array('class'=>'control-label ')) !!}
                            {!! Form::styledFile('img_sinopsi') !!}
                        </div>

                        @if($organiser_id)
                        {!! Form::hidden('organiser_id', $organiser_id) !!}
                        @endif

                        <div class="form-group">
                            {!! Form::label('destacado', 'DESTACADO', ['class' => 'control-label']) !!}
                            {!! Form::select(
                                'destacado',
                                [
                                '1' => 'Sí', // Opción con valor 'yes'
                                '0' => 'No'   // Opción con valor 'no'
                                ],
                                old('destacado'), // Valor seleccionado por defecto
                                [
                                'class' => 'form-control location_field',
                                'placeholder' => 'SELECCIONE', // Texto del placeholder
                                'required' => 'required' // Atributo requerido si es necesario
                                ]
                            ) !!}
                            </div>

                            <div class="form-group">
                            {!! Form::label('estado', 'Mostrar en la web', ['class' => 'control-label']) !!}
                            {!! Form::select(
                                'estado',
                                [
                                'Activo' => 'Activo', // Opción con valor 'yes'
                                'Desactivado' => 'Desactivado'   // Opción con valor 'no'
                                ],
                                old('estado'), // Valor seleccionado por defecto
                                [
                                'class' => 'form-control location_field',
                                'placeholder' => 'SELECCIONE', // Texto del placeholder
                                'required' => 'required' // Atributo requerido si es necesario
                                ]
                            ) !!}
                            </div>
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
                {!! Form::submit('Crear Evento', ['class'=>"btn btn-success"]) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
