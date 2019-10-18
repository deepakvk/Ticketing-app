@extends('Shared.Layouts.Master')

@section('title')
    @parent
    Dashboard
@stop

@section('page_title')
  <i class="ico-ticket mr5"></i>
    {{ Str::upper($event_name) }}:: Event Tickets
@stop

@section('top_nav')
    @include('ManageEvents.Partials.TopNav')
@stop

@section('menu')
    @include('Partials.Sidebar')
@stop
<!--Script-->
	@include('Shared.Layouts.ViewJavascript')
	<!--/Script-->
@section('page_header')
<div class="col-md-9">
@permission('create_ticket')
  <div class="btn-group btn-group-responsive">
   <a href="#" data-modal-id="CreateTicket" data-href="{{route('showNewTicket', ['event_id'=>$event_id])}}" class="btn btn-success loadModal"><i class="ico-plus"></i> Create Ticket</a>
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
    <input type="hidden" name='sort_by' value=""/>
    {!! Form::close() !!}
</div>
@stop

@section('content')
  @if($event_tickets->count())
      <div class="row">
          <div class="col-md-3 col-xs-6">
              <div class='order_options'>
                  <span class="event_count">{{$event_tickets->count()}} tickets</span>
              </div>
          </div>
          <div class="col-md-2 col-xs-6 col-md-offset-7">

          </div>
      </div>
  @endif


        @if($event_tickets->count())

          <div class="col-md-12">
            <div class="panel">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                             <th>ID</th>
                             <th>Name</th>
                             <th>Last updated by</th>
                             <th>Last updated</th>
                             <th>Action</th>

                            </tr>
                          </thead>
                          <tbody>
                           @foreach($event_tickets as $ticket)
                            <tr>
                              <td>{{ $ticket->ticket_id }}</td>
                              <td>{{ str_replace('?','€',$ticket->ticketName) }}</td>
                              <td>{{ $ticket->Firstname . ' ' . $ticket->Lastname }}</td>
                              <td>{{ $ticket->updated_at->toDayDateTimeString() }}</td>
                              <td>
                                <!--<a data-modal-id="EditAllocation" href="javascript:void(0);" data-href="//route('showEditAllocation', ['ticket_id'=>$ticket->ticket_id])}}" class="loadModal btn btn-xs btn-primary">Edit Allocation</a>-->
                                <a href="{{route('showEditAllocation', ['ticket_id'=>$ticket->ticket_id])}}" class="btn btn-xs btn-primary">Edit Allocation</a>
                              </td>
                            </tr>
                           @endforeach
                         </tbody>
                      </table>
                  </div>
                </div>
              </div>




        @else
            @if($q)
                @include('Shared.Partials.NoSearchResults')
            @else
                @include('ManageEvents.Partials.TicketsBlankSlate')
            @endif
        @endif

@stop
