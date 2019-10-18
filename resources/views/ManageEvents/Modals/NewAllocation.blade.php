<script type="text/javascript">
 $(document).ready(function() {
	
	var _array = {1:'', 7:'', 13:2, 15:'', 16:'', 17:8, 19:'', 20:'', 21:6, 22:'', 24:'', 25:'', 26:'', 27:2, 28:4, 29:'', 30:'', 31:'', 32:'', 33:'', 37:'', 38:'', 39:8, 40:'', 41:'', 42:20, 43:''};
	$(".client").change(function () {
		var _value = $("#client").val();
		
		for(var key in _array){			
			if(_value == key){
				if(_array[key]==''){
					var rem = 0;
				}
				else {
				var rem = _array[key]-parseInt($("#"+_value).text());				
				}
				
				if(rem == 0 || rem==''){
					$('.quantity').attr('placeholder','No tickets available');
					$('.quantity').attr('readonly');
				}
				else {
					if($("#"+_value).text() == ''){
						$('.quantity').attr('placeholder','Maximum '+_array[key]+' tickets');
						$('.quantity').attr('max',_array[key]);
					}
					else {
						$('.quantity').attr('placeholder',rem+' tickets available');
					}
					$('.quantity').attr('readonly',false);
				}
			}
		}		
	});
 });
</script>
<div role="dialog"  class="modal fade" style="display: none;">
   {!! Form::open(array('url' => route('postNewAllocation',array('ticket_id' =>$ticket_id)), 'class' => 'ajax gf')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    New Ticket Allocation</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
					    <div class="form-group">
							 {!! Form::label('Event', 'Event', array('class'=>' control-label')) !!}:
							 <span>{{ $event_name }} </span>
						</div>
						<div class="form-group">
							 {!! Form::label('Ticket', 'Ticket', array('class'=>' control-label')) !!}:
							 <span>{{ str_replace('?','€',$ticket_name) }} </span>
						</div>
						
						<div class="form-group">
							 {!! Form::label('client', 'Client', array('class'=>'client control-label required')) !!}
							 {!!  Form::select('client', $roles_ids, 17, ['class' => 'client form-control form-group']) !!}
						</div>
                         
                        <div class="form-group">
                            {!! Form::label('title', 'Ticket Title', array('class'=>'control-label required')) !!}
                            {!!  Form::text('title', Input::old('title'),
                                        array(
                                        'class'=>'form-control',
                                        'placeholder'=>'E.g: General Admission'
                                        ))  !!}
                        </div>

                        <div class="row">
                          <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('price', 'Ticket Price', array('class'=>'control-label')) !!}
                                    {!!  Form::text('price', 0.00,
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>'E.g: 25.99'
                                                ))  !!}


                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('quantity_available', 'Available', array('class'=>' control-label required')) !!}
                                    {!!  Form::text('quantity_available', Input::old('quantity_available'),
													array(
													'class'=>'quantity form-control'                                          
													)
                                                )  !!}
                                </div>
                            </div>
                        </div>
						
                        
                        

                    </div>
                </div>

            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit('Create Allocation', ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>
