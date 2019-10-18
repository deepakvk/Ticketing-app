<style>
	.close { color: #fff; opacity: 1;  filter: alpha(opacity=20); webkit-opacity: 1; -moz-opacity: 1; opacity: 1; }
	.close:hover { color: #fff; }
	.close:focus { color: #fff; border:none; outline:none; }
	.event-title { font-size: 16px !important; }
</style>
<script type="text/javascript">
	$(document).ready(function() {
		$('.delete_event').click(function(e) {			
			e.stopPropagation();
			e.stopImmediatePropagation();
			var url = "{{ route('deleteEvent') }}";
			var id = $(this).attr('id');
			if(confirm("Are you sure want to delete this event?") == true){
				$.ajax({ 
					url: url,
					type: 'POST',
					datatype: 'JSON',
					data: {event_id: id },
					success: function (resp) {
						location.reload();
					}
				});
			}
		});	 
	});
</script>
<div class="panel panel-success event">
    <div class="panel-heading">
        <div class="event-date">
          <div class="month">
                {{strtoupper($event->itemDate->format('Y'))}}
          </div>
            <div class="month">
                  {{strtoupper($event->itemDate->format('M'))}}
            </div>
            <div class="day">
                {{$event->itemDate->format('d')}}
            </div>
        </div>
        <ul class="event-meta">
		
            <li class="event-title col-md-11">
                {{{ strtoupper(str_limit($event->itemName, $limit = 75, $end = '...')) }}}

                @permission('edit_event')
                <a title="{{ $event->itemName }}" href="{{route('showEventDashboard', ['event_id'=>$event->id])}}" style="font-size: 16px; float: right" class="btn btn-primary">
                    
                    Edit Ticket Allocation
                    
                </a>
                @endpermission
            </li>
			
			@permission('delete_event')
			<li class="close col-md-1">
                <button type="button" class="close delete_event" id="{{ $event->id }}" title="Delete Event">×</button>
            </li>
			@endpermission
        </ul>
    </div>
    @if($event->itemImage && File::exists(asset($event->itemImage)))
    <div class="panel-body">
            <ul class="nav nav-section nav-justified mt5 mb5">
                <li>
                    <div class="section">

                       <img width="150" src="{{ File::exists(asset($event->itemImage)) ? asset($event->itemImage) : ' ' }}" class="img-responsive">
                    </div>
                </li>
            </ul>
      </div>
     @endif


    <div class="panel-footer">
        <ul class="nav nav-section nav-justified">
          <!--<li>
            <div class="btn-group btn-group-responsive">
             <a href="#" data-modal-id="CreateTicket" data-href="{{route('showNewTicket', ['event_id'=>$event->id])}}" class="btn btn-success loadModal"><i class="ico-plus"></i> Create Ticket</a>
            </div>
          </li>-->
            <li>
                <a href="{{route('requestTickets', ['event_id'=>$event->id])}}">
                    <i class="ico-edit"></i> Request Your Tickets
                </a>
            </li>

        </ul>
    </div>
</div>
<!--<div class="btn-group btn-group-responsive">
                <a href="#" data-modal-id="CreateEvent" data-href="http://attendize/events/create?organiser_id=2" class="btn btn-success loadModal"><i class="ico-plus"></i> Request for ticket</a>
            </div>-->
