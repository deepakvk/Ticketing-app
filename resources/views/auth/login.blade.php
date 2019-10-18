@extends('Shared.Layouts.MasterWithoutMenus')

@section('title', 'Login')

@section('content')
{!! Form::open(array('url' => 'login')) !!}
<div class="container">    
	<div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel">
                <div class="panel-body">
                    <div class="logo">
                        {!!HTML::image('assets/images/digital-logo.png')!!}
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('Username', 'Username', ['class' => 'control-label']) !!}
                        {!! Form::text('username', null, ['class' => 'form-control', 'autofocus' => true]) !!}
                        @if ($errors->has('username'))
                                   <span class="help-block">
                                       <strong>{{ $errors->first('username') }}</strong>
                                   </span>
                               @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('password', 'Password', ['class' => 'control-label']) !!}
                       (<a class="forgotPassword" href="{{route('password.request')}}" tabindex="-1">Forgot password?</a>)
                        {!! Form::password('password',  ['class' => 'form-control']) !!}
                        @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-success">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@stop
