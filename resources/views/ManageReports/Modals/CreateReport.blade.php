<script>
	$( function() {	
		var startDateTextBox = $('#start_date');
		var endDateTextBox = $('#end_date');
		$.timepicker.dateRange(
			startDateTextBox,
			endDateTextBox,
			{				
				changeYear: true,
				changeMonth: true,
				dateFormat: 'dd-mm-yy',
				controlType: 'select',
				start: {}, // start picker options
				end: {} // end picker options					
			}
		);
	});
</script>
<div role="dialog"  class="modal fade" style="display: none;">
    {!! Form::open(array('url' => route('postCreateReports', ['export_as'=>'xlsx']), 'class' => '')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-calendar"></i>
                    Select Date</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('start_date', 'Event Date From', array('class'=>'required control-label')) !!}
                                    {!!  Form::text('start_date', Input::old('start_date'),
                                                        [
                                                    'class'=>'form-control',
                                                    'id' => 'start_date',
                                                ])  !!}
                                </div>
								</div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!!  Form::label('end_date', 'Event Date To',
                                                [
                                            'class'=>'required control-label '
                                        ])  !!}

                                    {!!  Form::text('end_date', Input::old('end_date'),
                                                [
                                            'class'=>'form-control',
                                            'id'=>'end_date'
                                        ])  !!}
                                </div>
                            </div>
                        </div>




                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="uploadProgress"></span>
                {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit('Create Report', ['class'=>"btn btn-success"]) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
