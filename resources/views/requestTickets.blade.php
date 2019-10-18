@extends('Shared.Layouts.Master')

@section('title')
    @parent
    Dashboard
@stop

@section('page_title')
  {{ Auth::user()->Firstname }} Dashboard
@stop

@section('menu')
    @include('Partials.Sidebar')
@stop

@section('content')
    <div class="row">
        <div class="col-md-3 col-xs-6">
            <div class="order_options">
                <span class="event_count">
                    <h4 style="margin-bottom: 25px;margin-top: 20px;">Request your ticket allocation for {{ $event->itemLocation }}</h4>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9 col-sm-9 col-xs-12 additional_info">
            <span>Additional information</span>
            <p>Whilst we cannot guarantee that we will be able to accommodate every request, if you have any special requirements relating to your visit to {{ $event->itemLocation }}, please let us know.</p>
            @include('ManageEvents.Partials.TicketsPanel')
        </div>
    </div>
@stop
