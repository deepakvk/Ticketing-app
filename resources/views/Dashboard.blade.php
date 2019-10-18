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
@permission('create_event')
    <div class="btn-toolbar">
        <div class="btn-group btn-group-responsive">
            <a href="#" data-modal-id="CreateEvent" data-href="{{route('showCreateEvent')}}" class="btn btn-success loadModal"><i class="ico-plus"></i> Create Event</a>
        </div>
    </div>
@endpermission
</div>

<div class="col-md-3">
    {!! Form::open(array('url' => '', 'method' => 'get')) !!}
    <div class="input-group">
        <input name="q" value="{{ $search['q'] or '' }}" placeholder="Search Events.." type="text" class="form-control">
    <span class="input-group-btn">
        <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
    </span>
    </div>
    <input type="hidden" name='sort_by' value="{{$search['sort_by']}}"/>
    {!! Form::close() !!}
</div>
@stop

@section('content')

    <div class="row">
        <div class="col-md-3 col-xs-6">
            <div class="order_options">
              <span class="event_count">
               <h4 style="margin-bottom: 25px;margin-top: 20px;">Event Calendar</h4>
              </span>
            </div>
        </div>
        <div class="col-md-2 col-xs-6 col-md-offset-7">
            <div class="order_options">
                {!!Form::label('Filter Events By :')  !!}
                {!!Form::select('sort_by_select', ['next_2_weeks' => 'Within 2 weeks','itemDate' => 'Start date','itemName' => 'Title'], $search['sort_by'], ['class' => 'form-control pull right'])!!}
            </div>
        </div>
    </div>

    <div class="row">
        @if($events->count() > 0)
            @foreach($events as $event)
                <div class="col-md-6 col-sm-6 col-xs-12">
                    @include('ManageEvents.Partials.EventPanel')
                </div>
            @endforeach
        @else
            <div class="alert alert-info" role="alert">
                No events are scheduled for the next two weeks.
            </div>
        @endif
    </div>

    {{ $events->appends(request()->input())->links() }}

@stop
