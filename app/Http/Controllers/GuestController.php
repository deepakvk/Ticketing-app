<?php

namespace App\Http\Controllers;

use App\Event;
use Auth;
use App\Transaction;
use App\Useritem;
use App\Guestdetail;
use DB;
use Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Log;

class GuestController extends Controller
{
    /**
   * Create a new controller instance.
   *
   * @return void
   */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function showNewGuest(Request $request, $transaction_id, $alloc_id)
    {
        return view('ManageGuests.Modals.CreateGuest', compact('transaction_id', 'alloc_id'));
    }

    public function postNewGuest(Request $request)
    {
        $guest = Guestdetail::createNew();

        if (!$guest->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $guest->errors(),
            ]);
        }

        $guest->gstFirstName         = $request->get('first_name');
        $guest->gstLastName          = $request->get('last_name');
        $guest->gstCompany           = $request->get('company');
        $guest->gstPosition          = $request->get('job_title');
        $guest->gstText1             = $request->get('special_requests');
        $guest->transaction_id       = $request->get('transaction_id');
        $guest->ticketsallocation_id = $request->get('alloc_id');

        try {
            $guest->save();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status'   => 'error',
                'messages' => 'Whoops! There was a problem creating your guest. Please try again.',
            ]);
        }

        return response()->json([
            'status'      => 'success',
            'messages'    => 'Guest added succesfully!',
            'id'          => $guest->id,
			'guestName'   => $guest->gstFirstName.' '.$guest->gstLastName,
			'guestCompany'=> $guest->gstCompany,
			'jobTitle'    => $guest->gstPosition,
			'specRequest' => $guest->gstText1,
            'redirectUrl' => route('showOrders'),
        ]);
    }

    public function replicateGuest(Request $request, $transaction_id, $alloc_id)
    {
        /*find the id from guests table */
        $guest_query = new Guestdetail();
        $find_id = $guest_query->find($request->get('id'));
        $newGuest = $find_id->replicate();
        try {
            $newGuest->save();
            $insertedGuestId = $newGuest->id;
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status'   => 'error',
                'messages' => 'Whoops! There was a problem replicating the guest. Please try again.',
            ]);
        }
        return response()->json([
            'status'      => 'success',
            'guest_id'    => $insertedGuestId
        ]);
    }

    public function EditGuestShow(Request $request, $guest_id, $alloc_id)
    {
        $guest_obj = new Guestdetail();

        $guest = $guest_obj
                     ->where('id', '=', $guest_id)
                     ->select('gstFirstName', 'gstLastName', 'gstCompany', 'gstPosition', 'gstText1')
                     ->first();

        return view('ManageGuests.Modals.EditGuest', compact('guest', 'guest_id', 'alloc_id'));
    }

    public function EditGuestPost(Request $request, $guest_id)
    {
        $rules = [
      'first_name' => 'required',
      'last_name'  => 'required',
      'company'    => 'required'
    ];
        $messages = [
          'first_name.required' => 'Please enter the First name.',
         'last_name.required'   => 'Please enter the Last name.',
         'company.required'     => 'Please enter the company name.'
     ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
              'status'   => 'error',
              'messages' => $validator->messages()->toArray(),
          ]);
        }

        $guest_obj = new Guestdetail();
        $guest = $guest_obj->find($guest_id);
        $guest->gstFirstName = $request->get('first_name');
        $guest->gstLastName  = $request->get('last_name');
        $guest->gstCompany   = $request->get('company');
        $guest->gstPosition  = $request->get('job_title');
        $guest->gstText1     = $request->get('special_requests');


        try {
            $guest->save();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status'   => 'error',
                'messages' => 'Whoops! There was a problem Editing your guest. Please try again.',
            ]);
        }

        return response()->json([
            'status'      => 'success',
            'messages'    => 'Guest Edited succesfully!',
            'id'          => $guest->id,
			'guestName'   => $guest->gstFirstName.' '.$guest->gstLastName,
			'guestCompany'=> $guest->gstCompany,
			'jobTitle'    => $guest->gstPosition,
			'specRequest' => $guest->gstText1,
            'redirectUrl' => route('showOrders'),
        ]);
    }

    public function GuestDelete(Request $request, $guest_id)
    {
        Guestdetail::destroy($guest_id);
        return response()->json([
            'status'      => 'success'
        ]);
        //return redirect(route('showOrders'));
    }
}
