<script>
	$( function() {	
	var startDateTextBox = $('#start_date');
	var endDateTextBox = $('#end_date');
	$.timepicker.datetimeRange(
	startDateTextBox,
	endDateTextBox,
	{
		minInterval: (1000*60*60), // 1hr
		changeYear: true,
		changeMonth: true,
		dateFormat: 'dd-mm-yy',
		minDate: 0,
		controlType: 'select',
		oneLine: true,
		timeInput: true,
		start: {}, // start picker options
		end: {} // end picker options					
	}
	);
		/*$( "#pickdate" ).datetimepicker({
			controlType: 'select',
			oneLine: true,
			timeInput: true,
			minDate: 0,
			changeYear: true,
			changeMonth: true,
			dateFormat: 'dd-mm-yy'
		});*/
	});
</script>
<div role="dialog"  class="modal fade" style="display: none;">
    {!! Form::open(array('url' => route('postCreateEvent'), 'class' => 'ajax gf')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-calendar"></i>
                    Create Event</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="form-group">
                            {!! Form::label('category', 'Event Category', array('class'=>'control-label required')) !!}
                            {!!  Form::select('category', [3=>'Events',8=>'Music',9=>'Other',6=>'Sport'], '', array('class'=>'form-control'))  !!}
                        </div>
						
                        <div class="form-group">
                            {!! Form::label('title', 'Event Title', array('class'=>'control-label required')) !!}
                            {!!  Form::text('title', Input::old('title'),array('class'=>'form-control','placeholder'=>'E.g: '.Auth::user()->Firstname.'\'s International Conference' ))  !!}
                        </div>

                        <div class="form-group custom-theme">
                            {!! Form::label('description', 'Event Description', array('class'=>'control-label')) !!}
                            {!!  Form::textarea('description', Input::old('description'),
                                        array(
                                        'class'=>'form-control  editable',
                                        'rows' => 5
                                        ))  !!}
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('start_date', 'Event Start Date', array('class'=>'required control-label')) !!}
                                    {!!  Form::text('start_date', Input::old('start_date'),
                                                        [
                                                    'class'=>'form-control',
                                                    'id' => 'start_date',
                                                    

                                                ])  !!}									
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!!  Form::label('end_date', 'Event End Date',
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
                        <div class="form-group">
                            {!! Form::label('event_image', 'Event Image (Flyer or Graphic etc.)', array('class'=>'control-label ')) !!}
                            {!! Form::styledFile('event_image') !!}

                        </div>

                        <div class="address-manual">
                            <h5>
                                Address Details
                            </h5>

                            <div class="form-group">
                                {!! Form::label('location_venue_name', 'Venue Name', array('class'=>'control-label required ')) !!}
                                {!!  Form::select('location_venue_name', ['3Arena'=>'3Arena', 'Aviva Stadium'=>'Aviva Stadium'], '', [
                                        'class'=>'form-control location_field'                                     
                                        ])  !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('location_address_line', 'Address Line', array('class'=>'control-label')) !!}
                                {!!  Form::text('location_address_line', Input::old('location_address_line'), [
                                        'class'=>'form-control location_field',
                                        'placeholder'=>''
                                        ])  !!}
                            </div>

                        </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="uploadProgress"></span>
                {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit('Create Event', ['class'=>"btn btn-success"]) !!}
            </div>
        </div>      
    </div>
    {!! Form::close() !!}
</div>
