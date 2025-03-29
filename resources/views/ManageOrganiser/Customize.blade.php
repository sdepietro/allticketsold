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
    <style>
        .page-header {
            display: none;
        }
    </style>
    <script>
        $(function () {
            $('.colorpicker').minicolors({
                changeDelay: 500,
                change: function () {
                    var replaced = replaceUrlParam('{{route('showOrganiserHome', ['organiser_id'=>$organiser->id])}}', 'preview_styles', encodeURIComponent($('#OrganiserPageDesign form').serialize()));
                    document.getElementById('previewIframe').src = replaced;
                }
            });

        });

    </script>
    @include('ManageOrganiser.Partials.OrganiserCreateAndEditJS')
@stop

@section('menu')
    @include('ManageOrganiser.Partials.Sidebar')
@stop

@section('page_header')

@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#organiserSettings" data-toggle="tab">@lang("Organiser.organiser_settings")</a>
                </li>
                <li style="display:none">
                    <a href="#OrganiserPageDesign" data-toggle="tab">@lang("Organiser.organiser_page_design")</a>
                </li>
            </ul>
            <div class="tab-content panel">
                <div class="tab-pane active" id="organiserSettings">
					@if($organiser->id == 1)
                    {!! Form::model($organiser, array('url' => route('postEditOrganiser', ['organiser_id' => $organiser->id]), 'class' => 'ajax')) !!}
					@else
						{!! Form::model($organiser, array('url' => "", 'class' => 'ajax')) !!}
					@endif
                    <div class="form-group" style="display:none">
                        {!! Form::label('enable_organiser_page', trans("Organiser.enable_public_organiser_page"), array('class'=>'control-label required')) !!}
                        {!!  Form::select('enable_organiser_page', [
                        '1' => trans("Organiser.make_organiser_public"),
                        '0' => trans("Organiser.make_organiser_hidden")],old('enable_organiser_page'),
                                                    array(
                                                    'class'=>'form-control'
                                                    ))  !!}
                        <div class="help-block">
                            @lang("Organiser.organiser_page_visibility_text")
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('name', trans("Organiser.organiser_name"), array('class'=>'required control-label ')) !!}
                        {!!  Form::text('name', old('name'),
                                                array(
                                                'class'=>'form-control'
                                                ))  !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', trans("Organiser.organiser_email"), array('class'=>'control-label required')) !!}
                        {!!  Form::text('email', old('email'),
                                                array(
                                                'class'=>'form-control ',
                                                'placeholder'=>trans("Organiser.organiser_email_placeholder")
                                                ))  !!}
                    </div>
			@if($organiser->id == 1)
                    <div class="form-group">
                        {!! Form::label('tax_id', 'Políticas de privacidad', array('class'=>'control-label')) !!}
                 
						{!!  Form::textarea('tax_id', old('tax_id'),
                                                array(
                                                'class'=>'form-control editable',
                                                'placeholder'=>'Políticas de privacidad',
                                                'rows' => 4
                                                ))  !!}
                    </div>
                         
                    <div class="form-group">
                        {!! Form::label('about', 'TERMINOS Y CONDICIONES', array('class'=>'control-label')) !!}
                        {!!  Form::textarea('about', old('about'),
                                                array(
                                                'class'=>'form-control editable',
                                                'placeholder'=>trans("Organiser.organiser_description_placeholder"),
                                                'rows' => 4
                                                ))  !!}
                    </div>
                    <div class="row" style="display:none">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p class="control-label">{!! trans("Organiser.organiser_tax_prompt") !!}</p>
                                <label for="Yes" class="control-label" id="charge_yes">{!! trans("Organiser.yes") !!}</label>
                                <input id="charge_yes" name="charge_tax" type="radio" value="1" {{ $organiser->charge_tax == 1 ? 'checked' : '' }}>
                                <label for="No" class="control-label" id="charge_no">{!! trans("Organiser.no") !!}</label>
                                <input id="charge_yes" name="charge_tax" type="radio" value="0" {{ $organiser->charge_tax == 0 ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div id="tax_fields">
                            

                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('tax_name', trans("Organiser.organiser_tax_name"), array('class'=>'control-label')) !!}
                                    {!! Form::text('tax_name', old('tax_name'), array('class'=>'form-control', 'placeholder'=>'Tax name')) !!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('tax_value', trans("Organiser.organiser_tax_value"), array('class'=>'control-label')) !!}
                                    {!! Form::text('tax_value', old('tax_value'), array('class'=>'form-control', 'placeholder'=>'Tax Value')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
<hr>
                    <h4>Redes Sociales <span style="font-size: 13px;">(Introduzca url para activar o vacio para ocultar)</span></h4>
<br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('facebook', trans("Organiser.organiser_facebook"), array('class'=>'control-label ')) !!}

                                <div class="input-group">
                                    <span style="background-color: #eee;" class="input-group-addon">URL</span>
                                    {!!  Form::text('facebook', old('facebook'),
                                                    array(
                                                    'class'=>'form-control ',
                                                    'placeholder'=> trans("Organiser.organiser_username_facebook_placeholder")
                                                    ))  !!}
                                </div>
                            </div>
                        </div>


			<div class="col-md-6">
		    <div class="form-group">
		   
                        {!! Form::label('google_analytics_code', 'INSTAGRAM', array('class'=>'control-label')) !!}
			<div class="input-group">
			<span style="background-color: #eee;" class="input-group-addon">URL</span>
                        {!!  Form::text('google_analytics_code', old('google_analytics_code'),
                                                array(
                                                'class'=>'form-control',
                                                'placeholder' => '',
                                                ))
                        !!}
			</div>
                    </div>
		</div>
		<div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('tax_name', 'YOUTUBE', array('class'=>'control-label')) !!}
			<div class="input-group">
			<span style="background-color: #eee;" class="input-group-addon">URL</span>
                         {!! Form::text('tax_name', old('tax_name'), array('class'=>'form-control', 'placeholder'=>'Tax name')) !!}
       
                    	</div>
 		 	 </div>
  		</div>
		

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('twitter', trans("Organiser.organiser_twitter"), array('class'=>'control-label ')) !!}

                                <div class="input-group">
                                    <span style="background-color: #eee;" class="input-group-addon">URL</span>
                                    {!!  Form::text('twitter', old('twitter'),
                                             array(
                                             'class'=>'form-control ',
                                                    'placeholder'=> trans("Organiser.organiser_username_twitter_placeholder")
                                             ))  !!}
                                </div>
                            </div>
                        </div>

<div class="col-md-6" style="display:none">
                    <div class="form-group">
                        {!! Form::label('google_tag_manager_code', 'GOOGLE TAG MANAGER', ['class'=>'control-label']) !!}
			<div class="input-group">
			<span style="background-color: #eee;" class="input-group-addon">TAG</span>
                        {!!  Form::text('google_tag_manager_code', old('google_tag_manager_code'), [
                                'class'=>'form-control',
                                'placeholder' => '',
                            ])
                        !!}
                    	</div>
 		 	 </div>
  		</div>
			
			
                    </div>
                    @if(is_file($organiser->logo_path))
                        <div class="form-group" style="display:none">
                            {!! Form::label('current_logo', trans("Organiser.current_logo"), array('class'=>'control-label ')) !!}

                            <div class="thumbnail">
                                {!!Html::image($organiser->logo_path)!!}
                            
                             
                            </div>
                        </div>
                    @endif
                    <div class="form-group" style="display:none">
                        {!!  Form::labelWithHelp('organiser_logo', trans("Organiser.organiser_logo"), array('class'=>'control-label '),
                            trans("Organiser.organiser_logo_help"))  !!}
                        {!!Form::styledFile('organiser_logo')!!}
                    </div>

		    <div class="form-group">
		   <hr>
                    <h4>Sobre Nosotros <span style="font-size: 13px;">(Contenido pagina Sobre Nosotros)</span></h4>
<br>
                        {!! Form::label('phone', 'SOBRE NOSOTROS', array('class'=>'control-label')) !!}
			{!!  Form::textarea('phone', old('phone'),
                                                array(
                                                'class'=>'form-control editable',
                                                'placeholder'=>'',
                                                'rows' => 4
                                                ))  !!}
                     
			</div>
                
                    <div class="modal-footer">
                        {!! Form::submit(trans("Organiser.save_organiser"), ['class'=>"btn btn-success"]) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="tab-pane scale_iframe" id="OrganiserPageDesign">
                    {!! Form::model($organiser, array('url' => route('postEditOrganiserPageDesign', ['organiser_id' => $organiser->id]), 'class' => 'ajax ')) !!}

                    <div class="row">

                        <div class="col-md-6">
                            <h4>@lang("Organiser.organiser_design")</h4>

                            
                            <div class="form-group">
                                {!! Form::label('page_bg_color', trans("Organiser.background_color"), ['class'=>'control-label required ']) !!}
                                {!!  Form::input('text', 'page_bg_color', old('page_bg_color'),
                                                            [
                                                            'class'=>'form-control colorpicker',
                                                            'placeholder'=>'#EEEEEE'
                                                            ])  !!}
                            </div>
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>@lang("Organiser.organiser_page_preview")</h4>
                            <div class="preview iframe_wrap"
                                 style="overflow:hidden; height: 500px; border: 1px solid #ccc; overflow: hidden;">
                                <iframe id="previewIframe"
                                        src="{{ route('showOrganiserHome', ['organiser_id' => $organiser->id]) }}"
                                        frameborder="0" style="overflow:hidden;height:100%;width:100%" width="100%"
                                        height="100%"></iframe>
                            </div>
                        </div>
						@endif


                    </div>
					@if($organiser->id == 1)
                    <div class="panel-footer mt15 text-right">
                        {!! Form::submit(trans("basic.save_changes"), ['class'=>"btn btn-success"]) !!}
                    </div>

                    
					@endif
					{!! Form::close() !!}
                </div>
            </div>
        </div>
 
@stop
