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
        <div class="row">           
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Ticket Allowance</div>
                    <div class="panel-body">
                      

                        {!! Form::open(['method' => 'GET', 'url' => '/admin/users', 'class' => 'navbar-form navbar-right', 'role' => 'search'])  !!}
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="ico-search"></i>
                                </button>
                            </span>
                        </div>
                        {!! Form::close() !!}

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th><th>Client</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>										
										<td>{{ $item->display_name }}</td>										                                      
                                        <td>                                         
                                            <a href="{{ url('/admin/ticket-allowance/' . $item->id . '/edit') }}" title="Edit Allowance"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Allowance</button></a>                                            
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
@endsection
