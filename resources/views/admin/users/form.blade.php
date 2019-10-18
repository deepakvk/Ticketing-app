<div class="form-group{{ $errors->has('title') ? ' has-error' : ''}}">
    {!! Form::label('Title', 'Title: ', ['class' => 'col-md-4 control-label required']) !!}
    <div class="col-md-6">
        {!! Form::select('Title', ['Mr'=>'Mr','Ms'=>'Ms'], null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('Title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('username') ? ' has-error' : ''}}">
    {!! Form::label('username', 'username: ', ['class' => 'col-md-4 control-label required']) !!}
    <div class="col-md-6">
        {!! Form::text('username', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('username', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('firstname') ? ' has-error' : ''}}">
    {!! Form::label('Firstname', 'Firstname: ', ['class' => 'col-md-4 control-label required']) !!}
    <div class="col-md-6">
        {!! Form::text('Firstname', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('Firstname', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('lastname') ? ' has-error' : ''}}">
    {!! Form::label('Lastname', 'Lastname: ', ['class' => 'col-md-4 control-label required']) !!}
    <div class="col-md-6">
        {!! Form::text('Lastname', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('Lastname', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
    {!! Form::label('email', 'email: ', ['class' => 'col-md-4 control-label required']) !!}
    <div class="col-md-6">
        {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<!--<div class="form-group{{ $errors->has('password') ? ' has-error' : ''}}">
    {!! Form::label('password', 'Password: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
	@if(!isset($id))
        {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
	@else
		{!! Form::password('password', ['class' => 'form-control']) !!}
	@endif	
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div>-->
<div class="form-group{{ $errors->has('roles') ? ' has-error' : ''}}">
    {!! Form::label('role', 'Role: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('roles[]', $roles, isset($user_roles_id) ? $user_roles_id : '', ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
		{!! Form::hidden('Status',1) !!}
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
