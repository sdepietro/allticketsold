@extends('Shared.Layouts.MasterWithoutMenus')

@section('title', trans("User.login"))

@section('content')
    {!! Form::open(['url' => route("login"), 'id' => 'login-form']) !!}
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel">
                <div class="panel-body">
                    <div class="logo">
                        {!!Html::image('assets/images/logo2.png')!!}
                    </div>

                    @if(Session::has('failed'))
                        <h4 class="text-danger mt0">@lang("basic.whoops")! </h4>
                        <ul class="list-group">
                            <li class="list-group-item">@lang("User.login_fail_msg")</li>
                        </ul>
                    @endif

                    <div class="form-group">
                        {!! Form::label('email', trans("User.email"), ['class' => 'control-label']) !!}
                        {!! Form::text('email', null, ['class' => 'form-control', 'autofocus' => true]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password', trans("User.password"), ['class' => 'control-label']) !!}
                        (<a class="forgotPassword" href="{{route('forgotPassword')}}" tabindex="-1">@lang("User.forgot_password?")</a>)
                        {!! Form::password('password',  ['class' => 'form-control']) !!}
                    </div>

                    @include('Public.LoginAndRegister.Partials.CaptchaSection')

                    <div class="form-group">
                        <p><input class="btn btn-block btn-success" type="submit" value="@lang('User.login')"></p>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop
