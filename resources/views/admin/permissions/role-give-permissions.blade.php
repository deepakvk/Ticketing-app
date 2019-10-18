@extends('Shared.Layouts.Master')
@section('title')
    @parent
    Dashboard
@stop

@section('page_title')
  {{ Auth::user()->Firstname }} Dashboard
@stop

@section('top_nav')
    @include('ManageEvents.Partials.TopNav')
@stop

@section('menu')
    @include('Partials.Sidebar')
@stop

@section('content')
<script type="text/javascript">
	$(document).ready(function() {
		$('#role').change(function () {
			var url = "{{ route('toggle-role-permissions') }}";
			var _string = '';
			var role_data = $('#role').val();
			$.ajax({
				url: url,
                type: 'POST',
                datatype: 'JSON',
				data: {role_name: role_data },
				success: function (resp) {
                    for(var key in resp['messages']){
						_string += resp['messages'][key]['name']+"<br>";
					}
					if(_string ==''){
						_string = 'No permissions';
					}	
					$('.disp_perm').html(_string);
                }
			});
		});
	});
</script>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Give Permission to Role</div>
                    <div class="panel-body">

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::open(['method' => 'POST', 'url' => ['/admin/give-role-permissions'], 'class' => 'form-horizontal']) !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
                            {!! Form::label('name', 'Role: ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <select class="roles form-control" id="role" name="role">
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->display_name }}</option>
                                    @endforeach()
                                </select>
                                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
                            {!! Form::label('current_permissions', 'Current permissions: ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6 disp_perm">                               
                                    @foreach($relation_permissions as $relations)
                                    {{ $relations['original']['name'] }}<br>
                                    @endforeach()                               
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
                            {!! Form::label('label', 'Edit Permissions: ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <select class="permissions form-control" id="permissions" name="permissions[]" multiple="multiple">
                                    @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->display_name }}</option>
                                    @endforeach()
                                </select>
                                {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-4">
                                {!! Form::submit('Grant', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
@endsection