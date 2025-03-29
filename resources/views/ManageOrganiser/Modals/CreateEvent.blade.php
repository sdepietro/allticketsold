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


                        <div class="form-group">
                            {!! Form::label('country_short', 'Fecha de la Funcion', array('class'=>'control-label required')) !!}
                            {!! Form::input('datetime-local', 'country_short', old('country_short'), array('class'=>'form-control', 'placeholder'=>'DD-MM-AAAA HH:MM')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('location_post_code', 'Duracion', array('class'=>'control-label required')) !!}
                            {!! Form::input('number', 'location_post_code', old('location_post_code'), array('class'=>'form-control', 'placeholder'=>'Min')) !!}
                        </div>
                        <div>
                            @if($organiser_id)
                            {!! Form::hidden('organiser_id', $organiser_id) !!}
                        @else
                            <div class="create_organiser" style="{{$organisers->isEmpty() ? '' : 'display:none;'}}">
                                <h5>
                                    @lang("Organiser.organiser_details")
                                </h5>

                                <div class="form-group">
                                    {!! Form::label('organiser_name', trans("Organiser.organiser_name"), array('class'=>'required control-label ')) !!}
                                    {!!  Form::text('organiser_name', old('organiser_name'),
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>trans("Organiser.organiser_name_placeholder")
                                                ))  !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('organiser_email', trans("Organiser.organiser_email"), array('class'=>'control-label required')) !!}
                                    {!!  Form::text('organiser_email', old('organiser_email'),
                                                array(
                                                'class'=>'form-control ',
                                                'placeholder'=>trans("Organiser.organiser_email_placeholder")
                                                ))  !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('organiser_about', trans("Organiser.organiser_description"), array('class'=>'control-label ')) !!}
                                    {!!  Form::textarea('organiser_about', old('organiser_about'),
                                                array(
                                                'class'=>'form-control editable2',
                                                'placeholder'=>trans("Organiser.organiser_description_placeholder"),
                                                'rows' => 4
                                                ))  !!}
                                </div>
                                <div class="form-group more-options">
                                    {!! Form::label('organiser_logo', trans("Organiser.organiser_logo"), array('class'=>'control-label ')) !!}
                                    {!! Form::styledFile('organiser_logo') !!}
                                </div>
                                <div class="row more-options">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('organiser_facebook', trans("Organiser.organiser_facebook"), array('class'=>'control-label ')) !!}
                                            {!!  Form::text('organiser_facebook', old('organiser_facebook'),
                                                array(
                                                'class'=>'form-control ',
                                                'placeholder'=>trans("Organiser.organiser_facebook_placeholder")
                                                ))  !!}

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('organiser_twitter', trans("Organiser.organiser_twitter"), array('class'=>'control-label ')) !!}
                                            {!!  Form::text('organiser_twitter', old('organiser_twitter'),
                                                array(
                                                'class'=>'form-control ',
                                                'placeholder'=>trans("Organiser.organiser_twitter_placeholder")
                                                ))  !!}

                                        </div>
                                    </div>
                                </div>

                                <a data-show-less-text="@lang("Organiser.hide_additional_organiser_options")" href="javascript:void(0);"
                                   class="in-form-link show-more-options">
                                    @lang("Organiser.additional_organiser_options")
                                </a>
                            </div>

                            @if(!$organisers->isEmpty())
                                <div class="form-group select_organiser" style="{{$organisers ? '' : 'display:none;'}}">

                                    {!! Form::label('organiser_id', trans("Organiser.select_organiser"), array('class'=>'control-label ')) !!}
                                    {!! Form::select('organiser_id', $organisers, $organiser_id, ['class' => 'form-control']) !!}

                                </div>
                                <span class="">
                                    @lang("Organiser.or") <a data-toggle-class=".select_organiser, .create_organiser"
                                       data-show-less-text="<b>@lang("Organiser.select_an_organiser")</b>" href="javascript:void(0);"
                                       class="in-form-link show-more-options">
                                        <b>@lang("Organiser.create_an_organiser")</b>
                                    </a>
                                </span>
                            @endif
                        @endif


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
                {!! Form::submit(trans("Crear Funcion"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
