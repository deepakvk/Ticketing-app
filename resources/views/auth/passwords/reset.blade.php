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
					{!! Form::open(array('url' => route('password.request'))) !!}
                        <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('EMail', 'Email', ['class' => 'control-label']) !!}
                        {!! Form::text('email', old('email'), ['class' => 'form-control', 'autofocus' => true, 'required' => true]) !!}
                        @if ($errors->has('email'))
                                   <span class="help-block">
                                       <strong>{{ $errors->first('email') }}</strong>
                                   </span>
                               @endif
                    </div>  
					<div class="form-group">
						{!! Form::label('password', 'Password', ['class' => 'control-label']) !!}					   
						{!! Form::password('password',  ['class' => 'form-control']) !!}
						@if ($errors->has('password'))
									<span class="help-block">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
								@endif
					</div>
					<div class="form-group">
						{!! Form::label('Confirm Password', 'password-confirm', ['class' => 'control-label']) !!}					  
						{!! Form::password('password_confirmation',  ['class' => 'form-control', 'id' => 'password-confirm']) !!}
						@if ($errors->has('password_confirmation'))
									<span class="help-block">
										<strong>{{ $errors->first('password_confirmation') }}</strong>
									</span>
								@endif
					</div>					
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary">Reset Password</button>
                    </div>
					 {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
