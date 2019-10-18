<div role="dialog"  class="modal fade" style="display: none;">
   {!! Form::open(array('url' => route('postNewTicket', $event_id), 'class' => 'ajax gf')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    Create Ticket :: {{ $event_name }} Event</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
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
                                   {!! Form::label('release_date', 'Ticket Release Date', array('class'=>'control-label')) !!}
                                   {!!  Form::text('release_date', Input::old('release_date'),
                                                       [
                                                   'class'=>'form-control start hasDatepicker ',
                                                   'data-field'=>'date',
                                                   'data-startend'=>'start',
                                                   'data-startendelem'=>'.end',
                                                   'readonly'=>''

                                               ])  !!}
                               </div>
                           </div>

                           <div class="col-sm-6">
                               <div class="form-group">
                                   {!!  Form::label('expiry_date', 'Ticket Expiry Date',
                                               [
                                           'class'=>'control-label '
                                       ])  !!}

                                   {!!  Form::text('expiry_date', Input::old('expiry_date'),
                                               [
                                           'class'=>'form-control end hasDatepicker ',
                                           'data-field'=>'date',
                                           'data-startend'=>'end',
                                           'data-startendelem'=>'.start',
                                           'readonly'=> ''
                                       ])  !!}
                               </div>
                           </div>
                       </div>
                    </div>
                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit('Create Ticket', ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>
