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
                    <div class="panel-heading">Edit Allowance</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/ticket-allowance') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($venue, [
                            'method' => 'PATCH',
                            
                            'class' => 'form-horizontal'
                        ]) !!}

                        @include ('admin.ticket-allowance.form', ['submitButtonText' => 'Update'])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
@endsection