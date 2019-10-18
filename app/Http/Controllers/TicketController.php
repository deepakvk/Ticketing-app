<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Role;
use App\Event;
use App\Ticket;
use App\Ticketsallocation;
use App\Transaction;
use App\Useritem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Log;

class TicketController extends Controller
{
    private $businessUnitId;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function requestTickets($id)
    {
	    // Get User details, i.e. Business Unit ID.
	    $roleId = Auth::user()->roles->first()->id;
	    $this->businessUnitId = $roleId;

	    // Get event details.
	    $event = Event::where('id', '=', $id)->first();

	    // Get tickets associated with event and relevant ticket allocation.
	    $ticketAllocations = [];
	    foreach ($event->ticket as $ticket){
		    if($ticket->ticketsallocation){
				if($this->businessUnitId == 0) { // i.e. Administrator
					foreach($ticket->ticketsallocation as $item){
						$ticketAllocations[] = $item;
					}
			    }else{
				    $ticketAllocations[] = $ticket->ticketsallocation->filter(function ($item) {
				    	return $item->role_id == $this->businessUnitId;
				    })->first();
			    }
		    }
	    }
	    // Get Display Name from role_id.	
		 $ticketAllocations = array_filter($ticketAllocations);
	    foreach ($ticketAllocations as $key => &$ticketAllocation) {
			    //$newValues[$key] = $ticketAllocation;
				$ticketAllocations[$key] = $ticketAllocation;
				if($ticketAllocations[$key]['role_id']!='') {
					$role = Role::where('id', $ticketAllocations[$key]['role_id'])->first();				
					$ticketAllocations[$key]['display_name'] = $role->display_name;	
				}
				else {
					$ticketAllocations[$key]['display_name'] = 'Administrator';
				}				
				
	    }
		

        $data = [
			'ticketAllocations' => $ticketAllocations,
            'event'             => $event,
        ];

        return view('requestTickets', $data);
    }

    public function postCreateTickets(Request $request, $id)
    {
    	// Create a Transactions Record, they only information here of any benefit is the NOTES, other than this there is nothing of value on the Transaction table.
	    // TODO: Consider getting rid of this table.
        $transaction = Transaction::createNew();
        $transaction->user_id = Auth::user()->id;
        $transaction->transDate = Carbon::now();
        $transaction->transNotes = $request->get('special_req');

        try {
            $transaction->save();
            $insertedTransId = $transaction->id;
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status'   => 'error',
                'messages' => 'Whoops! There was a problem creating your ticket. Please try again.',
            ]);
        }

		$roles = Auth::user()->roles;
        $role_arr = array($roles[0]->id,0);

	    // Retrieve the type of tickets associated with the event.
		$tickets = Ticket::where('event_id', $id)->get();

		// Get the allocation allowed per ticket.
		foreach ($tickets as $ticket){
			$ticket_allocation = Ticketsallocation::where('ticket_id', $ticket->id)->get();
			foreach($ticket_allocation as $item) {
				if($request->get('quantity_'.$item->id)!=0) {
					$useritem = Useritem::createNew();
					$useritem->ticketsallocation_id = $item->id;
					$useritem->transaction_id = $insertedTransId;
					$useritem->usritemNoReq = $request->get('quantity_'.$item->id);

					try {
						$useritem->save();
					} catch (\Exception $e) {
						Log::error($e);
						$message = 'Whoops! There was a problem ordering your ticket. Please try again.';
						return redirect(route('showDashboard'))->with('danger', $message);
					}
				}
			}
		}

        $message = 'You have ordered your tickets succesfully!';
        return redirect(route('showDashboard'))->with('success', $message);
    }

    public function showNewTicket(Request $request, $event_id)
    {
        $event_obj = new Event();
        $event = $event_obj->select('itemName')->where('id', '=', $event_id)->first();
        $event_name = $event->itemName;
        $data = [
          'event_id'   => $event_id,
          'event_name' => $event_name
      ];
        return view('ManageEvents.Modals.CreateTicket', $data);
    }

    public function postNewTicket(Request $request, $event_id)
    {
        $ticket = Ticket::createNew();
        if (!$ticket->validate($request->all())) {
            return response()->json([
              'status'   => 'error',
              'messages' => $ticket->errors(),
          ]);
        }


        $ticket->event_id = $event_id;
        $ticket->ticketName = $request->get('title');
        $ticket->ticketProductCode = '';
        $ticket->ticketReleaseDate = $request->get('release_date') ? Carbon::createFromFormat('d-m-Y', $request->get('release_date')) : Carbon::now();
        $ticket->ticketExpiryDate = $request->get('expiry_date') ? Carbon::createFromFormat('d-m-Y', $request->get('expiry_date')) : Carbon::now();


        $ticket->CreatedBy = Auth::user()->id;
        $ticket->LastUpdatedBy = Auth::user()->id;
        try {
            $ticket->save();
            $insertedticketId = $ticket->id;
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
              'status'   => 'error',
              'messages' => 'Whoops! There was a problem creating your ticket. Please try again.',
          ]);
        }

        return response()->json([
            'status'      => 'success',
            'id'          => $event_id,
            'redirectUrl' => route('showEditAllocation', $insertedticketId)
        ]);
    }

    public function showEditAllocation(Request $request, $ticket_id)
    {
        $q = $request->get('q', '');
        $tickets_alloc_obj = new Ticketsallocation();
        $tickets_alloc = $tickets_alloc_obj
                                  ->join('roles', 'ticketsallocations.role_id', '=', 'roles.id')
                                  ->leftjoin('useritems', 'ticketsallocations.id', '=', 'useritems.ticketsallocation_id')
                                  ->where('ticketsallocations.ticket_id', '=', $ticket_id)
                                  ->select('ticketsallocations.id as alloc_id', 'useritems.transaction_id as trans_id', 'roles.id as roles_id', 'roles.display_name', 'ticketsallocations.ticketPriceLabel', 'ticketsallocations.ticketTotalAvailable', 'ticketsallocations.LastUpdatedBy', 'ticketsallocations.updated_at')
                                  ->selectRaw('useritems.usritemNoReq, sum(useritems.usritemNoReq) as ordered')
                                  ->groupBy('ticketsallocations.id')
                                  ->orderBy('alloc_id', 'asc')->get();



        $ticket_obj = new Ticket();
        $event = $ticket_obj
                        ->join('events', 'tickets.event_id', '=', 'events.id')
                        ->where('tickets.id', '=', $ticket_id)
                        ->select('events.id as event_id', 'events.itemName', 'tickets.ticketName')
                        ->first();
        $event_id = $event->event_id;
        $event_name = $event->itemName;
        $ticket_name = $event->ticketName;


        return view('ManageEvents.TicketsAllocation', compact('tickets_alloc', 'event_name', 'ticket_name', 'event_id', 'q', 'ticket_id'));
    }

    public function showNewAllocation(Request $request, $ticket_id)
    {
        $roles_obj = new Role();
        $roles = $roles_obj->select(['id','display_name'])->orderBy('ID', 'ASC')->get();
        foreach ($roles as $value) {
			if($value->id!=0){
            $role_ids[$value->id] = $value->display_name;
			}
        }
        $ticket_obj = new Ticket();
        $event = $ticket_obj
                        ->join('events', 'tickets.event_id', '=', 'events.id')
                        ->where('tickets.id', '=', $ticket_id)
                        ->select('events.id as event_id', 'events.itemName', 'tickets.ticketName')
                        ->first();
        $event_id = $event->event_id;
        $event_name = $event->itemName;
        $ticket_name = $event->ticketName;
		
		
		
		
		$tickets_alloc_obj = new Ticketsallocation();
        $tickets_alloc = $tickets_alloc_obj
                                  ->join('roles', 'ticketsallocations.role_id', '=', 'roles.id')
                                  ->leftjoin('useritems', 'ticketsallocations.id', '=', 'useritems.ticketsallocation_id')
                                  ->where('ticketsallocations.ticket_id', '=', $ticket_id)
                                  ->select('ticketsallocations.id as alloc_id', 'useritems.transaction_id as trans_id', 'roles.display_name', 'ticketsallocations.ticketPriceLabel', 'ticketsallocations.ticketTotalAvailable', 'ticketsallocations.LastUpdatedBy', 'ticketsallocations.updated_at')
                                  ->selectRaw('useritems.usritemNoReq, sum(useritems.usritemNoReq) as ordered')
                                  ->groupBy('ticketsallocations.id')
                                  ->orderBy('alloc_id', 'asc')->get();

        $data = [
        'event_id'    => $event_id,
        'ticket_id'   => $ticket_id,
        'roles_ids'   => $role_ids,
        'event_name'  => $event_name,
        'ticket_name' => $ticket_name,
		'tickets_alloc' => $tickets_alloc
    ];
        return view('ManageEvents.Modals.NewAllocation', $data);
    }

    public function postNewAllocation(Request $request, $ticket_id)
    {
        $ticket_allocation = Ticketsallocation::createNew();
        $ticket_allocation->role_id = $request->get('client');
        $ticket_allocation->ticket_id = $ticket_id;
        if ($request->get('price')!='') {
            $ticket_allocation->ticketPrice = $request->get('price');
        } else {
            $ticket_allocation->ticketPrice = 0;
        }
        $ticket_allocation->ticketPriceLabel = $request->get('title');
        $ticket_allocation->ticketTotalAvailable = $request->get('quantity_available');
        $ticket_allocation->CreatedBy = Auth::user()->id;
        $ticket_allocation->LastUpdatedBy = Auth::user()->id;
        try {
            $ticket_allocation->save();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
              'status'   => 'error',
              'messages' => 'Whoops! There was a problem creating your ticket allocation. Please try again.',
          ]);
        }
        return response()->json([
          'status'      => 'success',
          'redirectUrl' => route('showEditAllocation', $ticket_id)
      ]);
    }

    public function EditAllocationShow(Request $request, $alloc_id)
    {
        $ticket_alloc_obj = new TicketsAllocation();
        $ticket_alloc = $ticket_alloc_obj
                                ->join('roles', 'roles.id', '=', 'ticketsallocations.role_id')
                                ->join('tickets', 'ticketsallocations.ticket_id', '=', 'tickets.id')
                                ->join('events', 'events.id', '=', 'tickets.event_id')
                                ->where('ticketsallocations.id', '=', $alloc_id)
                                ->select('roles.name as name', 'events.itemName', 'tickets.ticketName', 'ticketsallocations.ticketPriceLabel', 'ticketsallocations.ticketPrice', 'ticketsallocations.ticketTotalAvailable')
                                ->first();

        $role_name   = $ticket_alloc->name;
        $event_name  = $ticket_alloc->itemName;
        $ticket_name = $ticket_alloc->ticketName;
        $alloc_name  = $ticket_alloc->ticketPriceLabel;
        $alloc_price = $ticket_alloc->ticketPrice;
        $alloc_total = $ticket_alloc->ticketTotalAvailable;

        return view('ManageEvents.Modals.EditTicketAllocation', compact('alloc_id', 'role_name', 'event_name', 'ticket_name', 'alloc_name', 'alloc_price', 'alloc_total'));
    }

    public function EditAllocationPost(Request $request)
    {
        $rules = [
        'ticketPriceLabel'     => 'required',
        'ticketPrice'          => 'required',
        'ticketTotalAvailable' => 'required'
      ];
        $messages = [
           'ticketPriceLabel.required'     => 'The ticket label is required.',
           'ticketPrice.required'          => 'The ticket price is required. ',
           'ticketTotalAvailable.required' => 'The ticket quantity is required.'
       ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $allocation_obj = new TicketsAllocation();
        $allocation = $allocation_obj->findOrFail($request->get('id'));
        $allocation->update($request->all());

        session()->flash('message', 'Successfully Updated Tickets Allocation');

        return response()->json([
            'status'      => 'success',
            'id'          => $allocation->id,
            'redirectUrl' => '',
        ]);
    }

    public function DeleteAllocation(Request $request, $alloc_id)
    {
        TicketsAllocation::destroy($alloc_id);
        return redirect(route('showEditAllocation', $request->get('ticket_id')))->with('success', 'Allocation with ID: '. $alloc_id .' is succesfully deleted');
    }

    public function ViewAllocationOrders(Request $request, $alloc_id)
    {
        $useritems_obj = new Useritem();
        $useritems = $useritems_obj
                               ->join('transactions', 'useritems.transaction_id', '=', 'transactions.id')
                               ->leftjoin('users', 'transactions.user_id', '=', 'users.id')
                               ->where('useritems.ticketsallocation_id', '=', $alloc_id)
                               ->select('useritems.transaction_id as trans_id', 'transactions.transDate', 'users.Firstname', 'users.Lastname', 'useritems.usritemNoReq')
                               ->get();
        return view('ManageEvents.Modals.viewOrders', compact('useritems'));
    }
}
