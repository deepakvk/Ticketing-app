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

@section('page_header')
<div class="col-md-9">
    
</div>
<div class="col-md-3">
    
</div>
@stop

@section('content')
<div class="container">
    <div class="row">
       

        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    Your application's dashboard.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
