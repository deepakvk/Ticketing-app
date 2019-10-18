@extends('Shared.Layouts.Master')

@section('title')
    @parent
    Dashboard
@stop

@section('page_title')
  <i class="ico-ticket mr5"></i>
    <a href="{{route('showEventDashboard', ['event_id'=>$event_id])}}">{{$event_name}} :: {{str_replace('?','€',$ticket_name)}}</a> -> Event Tickets
@stop

@section('top_nav')
    @include('ManageEvents.Partials.TopNav')
@stop

@section('menu')
    @include('Partials.Sidebar')
@stop

@section('page_header')
<div class="col-md-9">
	<div class="btn-toolbar">
		<div class="btn-group btn-group-responsive">
			<a href="#" data-modal-id="NewAllocation" data-href="{{route('showNewAllocation', ['ticket_id'=>$ticket_id])}}" class="btn btn-success loadModal"><i class="ico-plus"></i> New Allocation</a>
		</div>
	</div>
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
    @if($tickets_alloc->count())

			    <div class="row">
			        <div class="col-md-12">

				    <!-- START panel -->
				        <div class="panel">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>
											   ID
											</th>
											<th>
											   Client Allocated To
											</th>
											<th>
											   Name
											</th>
											<th>
											   Available
											</th>
											<th>
											   Ordered
											</th>
											<th>
											   Last updated by
											</th>
											<th>
											   Last updated
											</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									  @foreach($tickets_alloc as $alloc)
										<tr>
											<td>{{ $alloc->alloc_id }}</td>
											<td>{{ $alloc->display_name }}</td>
											<td>{{ str_replace('?','€',$alloc->ticketPriceLabel) }}</td>
											<td>{{ $alloc->ticketTotalAvailable }}</td>
                      <?php $anchor_var = '<a data-modal-id="viewOrders" class="loadModal" data-href="'.route('ViewAllocationOrders', ['alloc_id'=>$alloc->alloc_id]) .'" style="color: #428bca !important; text-decoration: underline;" href="#">view orders</a>'; ?>
											<td id="{{ $alloc->roles_id }}">{!! $alloc->ordered ? $alloc->ordered .' '. $anchor_var : '0' !!}</td>
											<td>{{ $alloc->LastUpdatedBy }}</td>
											<td>{{ $alloc->updated_at->toDayDateTimeString() }}</td>
											<td>
											    <a
													data-modal-id="EditAllocation"
													href="#"
													data-href="{{route('EditAllocationShow', ['alloc_id'=>$alloc->alloc_id])}}"
													class="loadModal btn btn-xs btn-primary"
													> Edit</a>
												{!! Form::open([
                                                'method' => 'POST',
                                                'url'    => route('DeleteAllocation', ['alloc_id'=>$alloc->alloc_id]),
                                                'style'  => 'display:inline'
                                            ]) !!}
												<button	class="btn btn-xs btn-danger" onclick ='return confirm("Confirm delete?")'> Delete</button>
												{!! Form::hidden('ticket_id', $ticket_id) !!}
											{!! Form::close() !!}
											</td>
										</tr>
									  @endforeach
									</tbody>
								</table>
					        </div>
				        </div>
			        </div>
			    </div>

	@else
	@if($q)
		@include('Shared.Partials.NoSearchResults')
	@else
		@include('ManageEvents.Partials.TicketsAllocationBlankSlate')
	@endif
        @endif
@stop
