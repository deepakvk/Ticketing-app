<div role="dialog"  class="modal fade" style="display: none;">
    <div class="modal-dialog large">
        <div class="modal-content">
		    <div class="modal-header text-center">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3 class="modal-title">
				<i class="ico-ticket"></i>
				Your guest list for order no: 3Arena21117</h3>
            </div>  
    		<div class="modal-body">
                <div class="row">
				    <div class="col-md-9">
						<div class="btn-toolbar">
							<div class="btn-group btn-group-responsive">
								<a href="#" data-modal-id="CreateEvent" data-href="{{route('showCreateEvent')}}" class="btn btn-success loadModal"><i class="ico-plus"></i> Create Event</a>
							</div>
						</div>
					</div>
				</div>			
			    <div class="row">
			        <div class="col-md-12">

				    <!-- START panel -->
				        <div class="panel">
							<div class="table-responsive ">
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
											<td>{{ $alloc->ordered }}</td>
											<td>{{ $alloc->LastUpdatedBy }}</td>
											<td>{{ $alloc->updated_at->toDayDateTimeString() }}</td>
											<td>
											<a
													data-modal-id="EditAttendee"
													href="javascript:void(0);"
													data-href=""
													class="loadModal btn btn-xs btn-primary"
													> Edit</a>

												<a
													data-modal-id="CancelAttendee"
													href="javascript:void(0);"
													data-href=""
													class="loadModal btn btn-xs btn-danger"
													> Delete</a>
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
		</div>
	</div>
</div>