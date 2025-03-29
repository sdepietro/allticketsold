@extends('Shared.Layouts.Master')

@section('title')
    @parent
    {{ trans('Organiser.productora') }}
@endsection

@section('top_nav')
    @include('ManageOrganiser.Partials.TopNav')
@stop
@section('page_title')
    {{ trans('Organiser.organiser_name_prod', ['name'=>$organiser->name])}}
@stop

@section('menu')
    @include('ManageOrganiser.Partials.Sidebar')
@stop

@section('head')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" integrity="sha256-szHusaozbQctTn4FX+3l5E0A5zoxz7+ne4fr8NgWJlw=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.4/raphael-min.js" integrity="sha256-Gk+dzc4kV2rqAZMkyy3gcfW6Xd66BhGYjVWa/FjPu+s=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js" integrity="sha256-0rg2VtfJo3VUij/UY9X0HJP7NET6tgAY98aMOfwP0P8=" crossorigin="anonymous"></script>

    {!! Html::script('https://maps.googleapis.com/maps/api/js?libraries=places&key='.config("attendize.google_maps_geocoding_key")) !!}
    {!! Html::script('vendor/geocomplete/jquery.geocomplete.min.js')!!}
    {!! Html::script('vendor/moment/moment.js')!!}
    {!! Html::script('vendor/fullcalendar/dist/fullcalendar.min.js')!!}
    <?php
    if(Lang::locale()!="en")
        echo Html::script('vendor/fullcalendar/dist/lang/'.Lang::locale().'.js');
    ?>
    {!! Html::style('vendor/fullcalendar/dist/fullcalendar.css')!!}

    
@stop
@section('page_header')
    <div class="col-md-9">
        <div class="btn-toolbar">
            <div class="btn-group btn-group-responsive">
                <a href="{{ route('showOrganiserRpublicas', ['organiser_id' => $organiser_id]) }}"  class="btn btn-success"><i class="ico-arrow-left"></i> Volver a la lista</a>
            </div>
        </div>
    </div>
@stop
@section('content')


{!! Form::model($vendedor, ['url' => route('vendedor.update', ['organiser_id' => $organiser->id, 'id' => $vendedor->id]), 'method' => 'PUT', 'class' => 'panel', 'id' => 'edit-form']) !!}
        <div class="panel-body">
            <h2>Editar Vendedor</h2>
            <br>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <!-- Nombres -->
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('nombres') ? 'has-error' : '' }}">
                        {!! Form::label('nombres', 'Nombres', ['class' => 'control-label required']) !!}
                        {!! Form::text('nombres', null, ['class' => 'form-control']) !!}
                        @if($errors->has('nombres'))
                            <p class="help-block">{{ $errors->first('nombres') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Apellidos -->
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('apellidos') ? 'has-error' : '' }}">
                        {!! Form::label('apellidos', 'Apellidos', ['class' => 'control-label required']) !!}
                        {!! Form::text('apellidos', null, ['class' => 'form-control']) !!}
                        @if($errors->has('apellidos'))
                            <p class="help-block">{{ $errors->first('apellidos') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Correo -->
            <div class="form-group {{ $errors->has('correo') ? 'has-error' : '' }}">
                {!! Form::label('correo', 'Correo', ['class' => 'control-label required']) !!}
                {!! Form::email('correo', null, ['class' => 'form-control']) !!}
                @if($errors->has('correo'))
                    <p class="help-block">{{ $errors->first('correo') }}</p>
                @endif
            </div>

            <!-- URL -->
            <div class="form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                {!! Form::label('url', 'DNI', ['class' => 'control-label']) !!}
                {!! Form::text('url', null, ['class' => 'form-control']) !!}
                @if($errors->has('url'))
                    <p class="help-block">{{ $errors->first('url') }}</p>
                @endif
            </div>

            <!-- Otros campos -->
            <div class="form-group {{ $errors->has('otros1') ? 'has-error' : '' }}" style="display:none">
                {!! Form::label('otros1', 'Otros 1', ['class' => 'control-label']) !!}
                {!! Form::text('otros1', null, ['class' => 'form-control']) !!}
                @if($errors->has('otros1'))
                    <p class="help-block">{{ $errors->first('otros1') }}</p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('otros2') ? 'has-error' : '' }}" >
                {!! Form::label('otros2', 'Telefono', ['class' => 'control-label']) !!}
                {!! Form::text('otros2', null, ['class' => 'form-control']) !!}
                @if($errors->has('otros2'))
                    <p class="help-block">{{ $errors->first('otros2') }}</p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('otros3') ? 'has-error' : '' }}" >
                {!! Form::label('otros3', 'Dirección', ['class' => 'control-label']) !!}
                {!! Form::text('otros3', null, ['class' => 'form-control']) !!}
                @if($errors->has('otros3'))
                    <p class="help-block">{{ $errors->first('otros3') }}</p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('otros4') ? 'has-error' : '' }}" >
                {!! Form::label('otros4', 'Fecha de Nacimiento', ['class' => 'control-label']) !!}
                {!! Form::text('otros4', null, ['class' => 'form-control']) !!}
                @if($errors->has('otros4'))
                    <p class="help-block">{{ $errors->first('otros4') }}</p>
                @endif
            </div>

            <!-- Botón de guardar -->
            <div class="form-group">
                <p><input class="btn btn-block btn-success" type="submit" value="Guardar cambios"></p>
            </div>
        </div>
    {!! Form::close() !!}
    
@stop