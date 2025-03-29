{{-- @todo Rewrite the JS for choosing event bg images and colours. --}}
@extends('Shared.Layouts.Master')

@section('title')
@parent
@lang("Event.customize_event")
@stop

{{-- @section('top_nav')
    @include('ManageEvent.Partials.TopNav')
@stop --}}


@section('menu')
@include('ManageOrganiser.Partials.Sidebar')
@stop

@section('page_title')
<i class="ico-cog mr5"></i>
Editar Evento
@stop

@section('page_header')
<style>
    .page-header {
        display: none;
    }

</style>
@stop

@section('head')
{!! Html::script('https://maps.googleapis.com/maps/api/js?libraries=places&key='.config("attendize.google_maps_geocoding_key")) !!}
{!! Html::script('vendor/geocomplete/jquery.geocomplete.min.js') !!}


<style type="text/css">
    .bootstrap-touchspin-postfix {
        background-color: #ffffff;
        color: #333;
        border-left: none;
    }

    .bgImage {
        cursor: pointer;
    }

    .bgImage.selected {
        outline: 4px solid #0099ff;
    }

</style>


@stop


@section('content')

<div class="row">
    @include('ManageOrganiser.Partials.EventCreateAndEditJS');

    {!! Form::open(array('url' => route('postEditGlobalEvent',['event_id' => $event->id]), 'class' => 'ajax gf', 'files' => true)) !!}
    <div>
        <div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('categorias','Evento de la Funcion', array('class'=>'control-label required')) !!}
                            <select name="categorias[]" class="form-control" multiple required>

                                @foreach ($categorias as $g )
                                <option value="{{ $g->id }}" @if($event->categorias->contains($g->id)) selected @endif>{{ $g->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            {!! Form::label('teatro_id','Teatro de la funcion', array('class'=>'control-label required')) !!}
                            <select name="teatro_id" class="form-control" required>

                                @foreach ($teatros as $t )
                                <option value="{{ $t->id }}" @if($event->teatro_id == $t->id) selected @endif>{{ $t->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            {!! Form::label('title', 'Titulo del Evento', array('class'=>'control-label required')) !!}
                            {!! Form::text('title', $event->title,array('class'=>'form-control','placeholder'=>trans("Event.event_title_placeholder", ["name"=>Auth::user()->first_name]) )) !!}
                        </div>
                        <div class="form-group custom-theme">
                            {!! Form::label('description', 'Descripcion', array('class'=>'control-label required')) !!}
                            {!! Form::textarea('description', $event->description,
                            array(
                            'class'=>'form-control editable',
                            'rows' => 5
                            )) !!}
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('start_date', trans("Event.event_start_date"), array('class'=>'required control-label')) !!}

                                    {!!  Form::text('start_date', $event->start_date ,
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

                                    {!!  Form::text('end_date',  $event->end_date ,
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
                            {!! Form::label('destacadolabel','Destacado', array('class'=>'control-label required')) !!}
                            <select name="destacado" class="form-control" required>
                                <option value="1" @if($event->destacado == '1') selected @endif>SI</option>
                                <option value="0" @if($event->destacado == '0') selected @endif>NO</option>
                            </select>
                        </div>
                        <center>
                            <div class="form-group">
                                {!! Form::label('event_image', trans("Event.imagenminiatura"), array('class'=>'control-label ')) !!}
                                <input type="file" name="img_mini" id="img_mini" style="display: none">
                                <br>
                                <img id="img_mini_show" onclick="load_mini()" src="{{ asset($event->img_mini) }}" alt="Imagen Miniatura" style="width: 150px;height: 100px;cursor: pointer;">
                            </div>
                            <div class="form-group">
                                {!! Form::label('imagengrande', trans("Event.imagengrande"), array('class'=>'control-label ')) !!}
                                <br>
                                <input type="file" name="img_main" id="img_main" style="display: none">
                                <img onclick="load_main()" id="img_main_show" src="{{ $event->img_main }}" alt="Imagen Grande" style="width: 150px;height: 100px;cursor: pointer;">
                            </div>
                            <div class="form-group">
                                {!! Form::label('imgsinopsi', 'Imagen de la sinopsi', array('class'=>'control-label ')) !!}
                                <br>
                                <input type="file" name="img_sinopsi" id="img_sinopsi" style="display: none">
                                <img onclick="load_sinopsi()" id="img_sinopsi_show" src="{{ $event->img_sinopsi }}" alt="Imagen Grande" style="width: 150px;height: 100px;cursor: pointer;">
                            </div>
                            <script>
                                function load_sinopsi() {
                                    img_input = document.getElementById("img_sinopsi");
                                    img_input.click();
                                    img_input.addEventListener('change', () => {
                                        document.getElementById('img_sinopsi_show').src = URL.createObjectURL(img_input.files[0]);
                                    });
                                }

                                function load_mini() {
                                    img_input = document.getElementById("img_mini");
                                    img_input.click();
                                    img_input.addEventListener('change', () => {

                                        document.getElementById('img_mini_show').src = URL.createObjectURL(img_input.files[0]);
                                    });
                                }



                                function load_main() {
                                    img_input = document.getElementById("img_main");
                                    img_input.click();
                                    img_input.addEventListener('change', () => {

                                        document.getElementById('img_main_show').src = URL.createObjectURL(img_input.files[0]);
                                    });
                                }

                            </script>
                        </center>

                        @if($organiser_id)
                        {!! Form::hidden('organiser_id', $organiser_id) !!}
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

            <div class="modal-footer">
                <span class="uploadProgress"></span>
                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit('Editar', ['class'=>"btn btn-success"]) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>



@stop
