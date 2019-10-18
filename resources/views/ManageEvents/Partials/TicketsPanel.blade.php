<div class="panel panel-success event">
    <div class="panel-heading">
        <p class="event-title">
            <h3><i class="ico-ticket ticket_icon mr5 ellipsis"></i>For the Event: {{$event->itemName}} - {{$event->item_date_formatted}}</h3>
        </p>
    </div>

	@forelse($ticketAllocations as $key => &$ticketAllocation)
        {!! Form::open(array('url' => route('postCreateTickets', $event->id), 'class' => '')) !!}
        <div class="panel-body">
            <div class="form-group col-md-4">
                <h5 class="nm text-muted">Allocation / Remaining tickets ({{ str_replace('?', '€', $ticketAllocations[$key]['ticketPriceLabel']) }})</h5>
                <p class="nm" style="font-size: 20px; padding-top:10px;">{{ $ticketAllocations[$key]['display_name'] }} - {{ $ticketAllocations[$key]['ticketTotalAvailable'] }} / {{ $ticketAllocations[$key]['remaining_tickets'] }}</p>
            </div>
            <div class="form-group col-md-4">
                <h5 class="nm text-muted">Quantity</h5>
                <p class="nm" style="font-size: 20px; padding-top:10px;">
                    {!! ($ticketAllocations[$key]['remaining_tickets'] != 0) ? Form::selectRange('quantity_'.$ticketAllocations[$key]['id'], 0, $ticketAllocations[$key]['remaining_tickets'], ['class' => 'form-control']) : 'Maximum allocated' !!}
                </p>
            </div>
            <div class="form-group col-md-4">
                <textarea class="form-control" cols="90" rows="4" name="special_req" placeholder="Special Requirements"></textarea>
            </div>
        </div>
	@empty
        <div class="panel-body">
            @include('ManageEvents.Partials.TicketsAllocationBlankSlate')
        </div>
    @endforelse

    <div class="panel-footer">
        <ul class="nav nav-section nav-justified">
            <li style="text-align:center">
                {!! Form::submit('Submit request', ['class'=>"btn btn-success"]) !!}
                {!! Form::hidden('event_id', $event->id) !!}
            </li>
        </ul>
    </div>
    {!! Form::close() !!}
</div>
