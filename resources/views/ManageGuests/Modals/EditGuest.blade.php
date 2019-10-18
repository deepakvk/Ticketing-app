<div role="dialog"  class="modal fade" style="display: none;">



    {!! Form::open(array('url' => route('EditGuestPost', ['guest_id'=>$guest_id]), 'class' => 'ajax gf edit_guest')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-pencil2"></i>
                    Edit Guest</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('first_name', 'First Name', array('class'=>'control-label required')) !!}
                            {!!  Form::text('first_name', $guest->gstFirstName,array('class'=>'form-control'))  !!}
                        </div>

                        <div class="form-group custom-theme">
                            {!! Form::label('last_name', 'Last Name', array('class'=>'control-label required')) !!}
                            {!!  Form::text('last_name', $guest->gstLastName,array('class'=>'form-control'))  !!}
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('company', 'Company', array('class'=>'control-label required')) !!}
                                    {!!  Form::text('company', $guest->gstCompany,array('class'=>'form-control'))  !!}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('job_title', 'Job Title', array('class'=>'control-label')) !!}
                                    {!!  Form::text('job_title', $guest->gstPosition,array('class'=>'form-control'))  !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('special_requests', 'Special Requests', array('class'=>'control-label')) !!}
                            {!!  Form::textarea('special_requests', $guest->gstText1,
                                        array(
                                        'class'=>'form-control  editable',
                                        'rows' => 5
                                        ))  !!}

                        </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="uploadProgress"></span>
				{!! Form::hidden('alloc_id',$alloc_id) !!}
				{!! Form::hidden('guest_id',$guest_id) !!}
                {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit('Edit Guest', ['class'=>"btn btn-success"]) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
