<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    {!! Form::label('venue', 'Venue: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('venue', $venue, '', ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('venue', '<p class="help-block">:message</p>') !!}
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