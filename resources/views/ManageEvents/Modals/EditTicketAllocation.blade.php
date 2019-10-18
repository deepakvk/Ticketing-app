<div role="dialog"  class="modal fade" style="display: none;">
   {!! Form::open(array('url' => route('EditAllocationPost',array('alloc_id' =>$alloc_id)), 'class' => 'ajax gf')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-edit"></i>
                    Edit Ticket Allocation</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
					    <div class="form-group">
							 {!! Form::label('Client', 'Client', array('class'=>' control-label')) !!}:
							 <span>{{ $role_name }} </span>
						</div>
					    <div class="form-group">
							 {!! Form::label('Event', 'Event', array('class'=>' control-label')) !!}:
							 <span>{{ $event_name }} </span>
						</div>
						<div class="form-group">
							 {!! Form::label('Ticket', 'Ticket', array('class'=>' control-label')) !!}:
							 <span>{{ str_replace('?','€',$ticket_name) }} </span>
						</div>
						<div class="form-group">
							 {!! Form::label('ID', 'ID', array('class'=>' control-label')) !!}:
							 <span>{{ $alloc_id }} </span>
						</div>
											                        
                        <div class="form-group">
                            {!! Form::label('title', 'Allocation Title', array('class'=>'control-label required')) !!}
                            {!!  Form::text('ticketPriceLabel', str_replace('?','€',$alloc_name),
                                        array(
                                        'class'=>'form-control',
                                        'placeholder'=>'E.g: General Admission'
                                        ))  !!}
                        </div>

                        <div class="row">
                          <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('price', 'Ticket Price', array('class'=>'control-label')) !!}
                                    {!!  Form::text('ticketPrice', $alloc_price,
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>'E.g: 25.99'
                                                ))  !!}


                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('quantity_available', 'Total Available', array('class'=>' control-label required')) !!}
                                    {!!  Form::text('ticketTotalAvailable', $alloc_total,
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>'E.g: 100 (Leave blank for unlimited)'
                                                )
                                                )  !!}
                                </div>
                            </div>
                        </div>
                        
                        

                    </div>
                </div>

            </div> <!-- /end modal body-->
            <div class="modal-footer">
			   {!! Form::hidden('id', $alloc_id) !!}
               {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit('Edit Allocation', ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>
