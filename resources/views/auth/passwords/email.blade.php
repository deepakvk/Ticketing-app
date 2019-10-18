@extends('Shared.Layouts.MasterWithoutMenus')
@section('title', 'Reset Password')
@section('content')
<div class="container">
    <div class="row">
		<div class="col-md-4 col-md-offset-4">
            <div class="panel">
                <div class="panel-body">
                    <div class="logo">
                        {!!HTML::image('assets/images/digital-logo.png')!!}
                    </div>
					@if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
					{!! Form::open(array('url' => route('password.email'))) !!}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
                        {!! Form::text('email', old('email'), ['class' => 'form-control', 'id' => 'email', 'autofocus' => true, 'required' => true]) !!}
                        @if ($errors->has('email'))
                                   <span class="help-block">
                                       <strong>{{ $errors->first('email') }}</strong>
                                   </span>
                               @endif
                    </div>                   
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary">Send Password Reset Link</button>
                    </div>
					 {!! Form::close() !!}
                </div>
            </div>
        </div>		       
    </div>
</div>
@endsection
