<div role="dialog"  class="modal fade" style="display: none;">
    <div class="modal-dialog large">
        <div class="modal-content">
		    <div class="modal-header text-center">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3 class="modal-title">
				<i class="ico-ticket"></i>
				View Orders</h3>
            </div>
    		<div class="modal-body">
                <div class="row">
				    <div class="col-md-9">						
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
											   OrderCode
											</th>
											<th>
											   User
											</th>
											<th>
											   Last updated
											</th>
											<th>
											   Total
											</th>
											
											
										</tr>
									</thead>
									<tbody>
									  @foreach($useritems as $useritem)
										<tr>
											<td>{{ $useritem->trans_id }}</td>
											<td>{{ $useritem->Firstname . ' ' . $useritem->Lastname }}</td>
											<td>{{ $useritem->transDate }}</td>
											<td>{{ $useritem->usritemNoReq }}</td>
											
											
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
