@extends('Shared.Layouts.Master')

@section('title')
    @parent
    Dashboard
@stop

@section('page_title')
    {{ Auth::user()->Firstname }} Dashboard
@stop

@section('menu')
    @include('Partials.Sidebar')
@stop

@section('top_nav')
    @include('ManageEvents.Partials.TopNav')
@stop

@section('page_header')
    <script type="text/javascript">
		+function ($) {
			'use strict';
			$(document).on("click", ".guestButton", function (listId) {
				
				var trid = $(this).closest('tr').nextAll('.guestList').prop('id');
				$('#' + trid).toggle('medium');
				$(this).children('.ico-plus').toggleClass('ico-minus');
			});
			
			$(document).on("click", ".eventButton", function (listId) {
				var tr_id = $(this).prop('id');	
				var tr_open = $(this).closest('tr').next('.eventlist_' + tr_id).prop('class');	
				$('.' + tr_open).toggle('fast');				
				$(this).children('.ico-plus').toggleClass('ico-minus').promise().done(function(){ if(!$(this).hasClass('ico-minus')){
					if($(this).closest('tr').nextAll('.eventlist_' + tr_id).find('.guestButton').children('.ico-plus').hasClass('ico-minus')){
					
						var gst_close = $(this).closest('tr').nextAll('.guesteventlist_'+ tr_id).prop('class');					
						$.each($('.guesteventlist_' + tr_id), function (index, value) { 

								if($(value).is(":visible")){
									$(value).hide();
									$(value).prev('tr').children().last().find('.guestButton').children('.ico-plus').toggleClass('ico-minus');
								}
														
						});						
					}
				} });
			});

		}(jQuery);
    </script>

    <div class="col-md-offset-9 col-md-3 col-sm-6">
        {!! Form::open(array('url' => route('showOrders', ['sort_by'=>$sort_by]), 'method' => 'get')) !!}
        <div class="input-group">
            <input name='q' value="{{$q or ''}}" placeholder="Search Orders.." type="text" class="form-control">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
            </span>
        </div>
		<input type="hidden" name='sort_by' value="{{ $sort_by }}"/>
        {!! Form::close() !!}
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3 col-xs-6">
            <div class="order_options">
              <span class="event_count">
               <h4 style="margin-bottom: 25px;margin-top: 20px;">You have {{$total_orders}} Orders</h4>
              </span>
            </div>
        </div>
		<div class="col-md-2 col-xs-6 col-md-offset-7">
            <div class="order_options">
                {!!Form::label('Filter Orders By :')  !!}
                {!!Form::select('sort_by_select', ['next_2_weeks' => 'Within 2 weeks','created_at' => 'All'], $sort_by, ['class' => 'form-control pull right'])!!}
            </div>
        </div>
    </div>
    <div class="row">

        @if($trans_count)

            <div class="col-md-12">

                <!-- START panel -->
                <div class="panel">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{!! Html::sortable_link('Event Name.', $sort_by, 'order_reference', $sort_order, ['q' => $q , 'page' => '']) !!}</th>
                                    <th>{!! Html::sortable_link('Event Date', $sort_by, 'eventDate', $sort_order, ['q' => $q , 'page' => '']) !!}</th>
                                    <th>Business Unit</th>
                                    <th>Request summary</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
							@php $cnt = 1; @endphp
                            @foreach($transactions as $key => $item)
								<tr>
									<td colspan="6">
                                        <div class="col-md-12">
                                            <div class="btn-group btn-group-responsive">                                               
                                                <a href="javascript:void(0);" title="Click to see the orders of {{$key}}" id="{{$cnt}}" class="h3 eventButton">
                                                    <i class="ico-plus" id="ico-{{$key}}"></i> {{ $key }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
								</tr>
								@foreach($item as $transaction)
								
									<tr class="eventlist_{{$cnt}}" style="display:none;">
										<td><h4>{{$transaction->itemName}}</h4></td>
										<td><h4>{{ $transaction->itemDate ? $transaction->itemDate : ''  }}</h4></td>
										<td>
											<h4>
												@php
																																			
														$x = App\Role::where('id', $transaction->role_id)->first();
													
													if($x){
														echo $x->display_name;
													}else{
														echo "- None -";
													}
												@endphp
											</h4>
										</td>

										<td colspan="2">
											<div class="col-md-5">
												<div class="btn-group btn-group-responsive">
													<h4>{{ $transaction->total }} x {{ str_replace('?','€',$transaction->ticketName) }}</h4>
													<a href="javascript:void(0);" class="btn btn-success guestButton">
														<i class="ico-plus" id="ico-{{$transaction->useritems_id}}"></i> Manage your guest list
													</a>
												</div>
											</div>
										</td>
									</tr>

									<tr class="guestList guesteventlist_{{$cnt}}" id="list_{{$transaction->useritems_id}}" style="display:none;">
										<td colspan="4">
											{!! Form::open(['method' => 'POST', 'url' => '', 'style' => 'display:inline', 'id' => 'guestform_'.$transaction->alloc_id]) !!}
											{!! Form::hidden('transaction_id', $transaction->id) !!}
											<table class="table" id="table_{{$transaction->alloc_id}}">
												<thead>
													<tr>
														<th class="guestcheckth_{{$transaction->alloc_id}}">Copy</th>
														<th>ID</th>
														<th>Guest Name</th>
														<th>Company</th>
														<th>Job title</th>
														<th>Special requests</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
												@php

												if ($role == 0) {
													$guests = $guests_obj
														->join('transactions', 'transactions.id', '=', 'guestdetails.transaction_id')
														->where('guestdetails.ticketsallocation_id', '=', $transaction->alloc_id)
														->select('guestdetails.id as gst_id', 'guestdetails.transaction_id as trans_id', 'guestdetails.ticketsallocation_id as alloc_id', 'guestdetails.gstFirstName', 'guestdetails.gstLastName', 'guestdetails.gstCompany', 'guestdetails.gstPosition', 'guestdetails.gstText1')
														->get();
												} else {
													$guests = $guests_obj
														->join('transactions', 'transactions.id', '=', 'guestdetails.transaction_id')
														->where('guestdetails.ticketsallocation_id', '=', $transaction->alloc_id)
														->select('guestdetails.id as gst_id', 'guestdetails.transaction_id as trans_id', 'guestdetails.ticketsallocation_id as alloc_id', 'guestdetails.gstFirstName', 'guestdetails.gstLastName', 'guestdetails.gstCompany', 'guestdetails.gstPosition', 'guestdetails.gstText1')
														->get();
												}

												@endphp
												@if($guests->count())
													@php $count = 0;  @endphp
													@foreach($guests as $guest)
														@if($guest->alloc_id == $transaction->alloc_id)
															<tr id="klon{{$count}}" class="klon_{{$guest->gst_id}}">
																<td id="{{ $guest->gst_id }}" class="guestchecktd_{{$transaction->alloc_id}}">{!! Form::radio('guestcheck', $guest->gst_id, '', ['class' => 'guestcheck', 'id' => 'guestcheck_'.$transaction->alloc_id]) !!}</td>
																<td>{{ $guest->gst_id }}</td>
																<td>{{ $guest->gstFirstName . ' ' . $guest->gstLastName }}</td>
																<td>{{ $guest->gstCompany != '' ? $guest->gstCompany: '-'  }}</td>
																<td>{{ $guest->gstPosition!= '' ? $guest->gstPosition:'-'  }}</td>
																<td>{{ $guest->gstText1!= '' ? $guest->gstText1:'-' }}</td>
																<td>
																	<a data-modal-id="EditAllocation" href="#" data-href="{{route('EditGuestShow', ['guest_id'=>$guest->gst_id,'alloc_id'=>$transaction->alloc_id])}}" class="loadModal btn btn-xs btn-primary EditAllocation" data-alloc-id="{{$transaction->alloc_id}}"> Edit</a>
																	<button class="btn btn-xs btn-danger delete-guest" data-trans-id="{{$transaction->alloc_id}}" data-gstid="{{$guest->gst_id}}" data-href="{{route('GuestDelete', ['guest_id'=>$guest->gst_id])}}" data-baseurl="{{url('/')}}"> Delete</button>
																</td>
															</tr>
															@php $count = $count + 1; @endphp
														@endif
													@endforeach

													<div class="col-md-2" style="padding: 0;">
														<h4 class="alert-info" style="padding: 5px;"><span id="cnt_{{$transaction->alloc_id}}">{{ $count }}</span> out of <span id="total_{{$transaction->alloc_id}}">{{ $transaction->total }}</span> guest details have been added.</h4>
													</div>

													<br>
													<div class="clearfix"></div>

													@if($transaction->total-$count!=0 and $transaction->total>$count )
														<div class="btn-group btn-group-responsive add_{{$transaction->alloc_id}}">
															<a href="#" data-modal-id="CreateGuest"
															   data-href="{{route('showNewGuest', ['transaction_id'=>$transaction->id,'alloc_id'=>$transaction->alloc_id])}}"
															   class="btn btn-success loadModal CreateGuest" id="CreateGuest_{{$transaction->alloc_id}}"><i class="ico-plus"></i>Add Guest</a>
														</div>
														@if($count > 0)
															<div class="btn-group btn-group-responsive copy_{{$transaction->alloc_id}}" style="padding-left:1em;">
																<button class="btn btn-warning replicate" id="replicateGuest_{{$transaction->alloc_id}}" data-trans-id="{{$transaction->alloc_id}}" data-remaining="{{ $transaction->usritemNoReq-$count }}"
																		data-table="table_{{$transaction->alloc_id}}"
																		data-href="{{route('replicateGuest', ['transaction_id'=>$transaction->id,'alloc_id'=>$transaction->alloc_id])}}"> Copy
																</button>
															</div>
															<script type="text/javascript">													
																$('.guestcheckth_' +{{$transaction->alloc_id}}).show();
																$('.guestchecktd_' +{{$transaction->alloc_id}}).show();
															</script>
														@else
															<script type="text/javascript">														
																$('.guestcheckth_' +{{$transaction->alloc_id}}).hide();
																$('.guestchecktd_' +{{$transaction->alloc_id}}).hide();
															</script>
														@endif
													   <br><br>
													@elseif($transaction->total-$count == 0)												
														<div class="btn-group btn-group-responsive add_{{$transaction->alloc_id}}" style="display:none;">
															<a href="#" data-modal-id="CreateGuest"
															   data-href="{{route('showNewGuest', ['transaction_id'=>$transaction->id,'alloc_id'=>$transaction->alloc_id])}}"
															   class="btn btn-success loadModal CreateGuest" id="CreateGuest_{{$transaction->alloc_id}}"><i class="ico-plus"></i>Add Guest</a>
														</div>
														<div class="btn-group btn-group-responsive copy_{{$transaction->alloc_id}}" style="padding-left:1em; display:none;">
															<button class="btn btn-warning replicate" id="replicateGuest_{{$transaction->alloc_id}}" data-trans-id="{{$transaction->alloc_id}}" data-remaining="{{ $transaction->usritemNoReq-$count }}"
																	data-table="table_{{$transaction->alloc_id}}"
																	data-href="{{route('replicateGuest', ['transaction_id'=>$transaction->id,'alloc_id'=>$transaction->alloc_id])}}"> Copy
															</button>
														</div>
														<br><br>
														<span id="alert_{{$transaction->alloc_id}}" style="font-size:16px;">The minimum number of guests required have been completed. To add more guests please contact the Sponsorship Management team.</span>		
														<script>
															$('.guestcheckth_' +{{$transaction->alloc_id}}).hide();
															$('.guestchecktd_' +{{$transaction->alloc_id}}).hide();
														</script>
													@endif 
													<span id="alert_{{$transaction->alloc_id}}" style="display:none; font-size:16px;">The minimum number of guests required have been completed. To add more guests please contact the Sponsorship Management team.</span>
												@else
													<tr>
														<div class="col-md-2 rem_{{$transaction->alloc_id}}" style="padding: 0; display:none;">
															<h4 class="alert-info" style="padding: 5px;"><span id="cnt_{{$transaction->alloc_id}}">{{ $count }}</span> out of <span id="total_{{$transaction->alloc_id}}">{{ $transaction->total }}</span> guest details have been added.</h4>
														</div><br /><div class="clearfix"></div>
														<div class="btn-group btn-group-responsive add_{{$transaction->alloc_id}}">
															<a href="#" id="CreateGuest_{{$transaction->alloc_id}}" data-modal-id="CreateGuest"
															   data-href="{{route('showNewGuest', ['transaction_id'=>$transaction->id,'alloc_id'=>$transaction->alloc_id])}}"
															   class="btn btn-success loadModal CreateGuest"><i class="ico-plus"></i>Add Guest</a>
														</div>
														<div class="btn-group btn-group-responsive copy_{{$transaction->alloc_id}}" style="padding-left:1em; display:none;">
																<button class="btn btn-warning replicate" id="replicateGuest_{{$transaction->alloc_id}}" data-trans-id="{{$transaction->alloc_id}}" data-remaining=""
																		data-table="table_{{$transaction->alloc_id}}"
																		data-href="{{route('replicateGuest', ['transaction_id'=>$transaction->id,'alloc_id'=>$transaction->alloc_id])}}"> Copy
																</button>
															</div>
														<br><br>
														<td>There are no guests added.</td>
													</tr>
												@endif
												</tbody>
											</table>
										{!! Form::close() !!}
										<td>
									</tr>
								
								@endforeach
								@php $cnt = $cnt+1; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            @if($q)
                @include('Shared.Partials.NoSearchResults')
            @else
                @include('ManageEvents.Partials.OrdersBlankSlate')
            @endif
        @endif
    </div>
    {{ $links }}
@stop
