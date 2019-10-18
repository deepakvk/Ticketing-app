<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    {!! Form::label('name', 'Name: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
	@if(isset($permission->id))
		<?php $title = 'Cannot edit name'; ?>
		@else
		<?php $title = ''; ?>
		@endif
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'title' => $title, isset($permission->id) ? 'disabled' : '']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
    {!! Form::label('label', 'Label: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('display_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>