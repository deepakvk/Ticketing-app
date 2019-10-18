<?php

namespace App\Http\Controllers;

use App\Event;
use App\Ticket;
use App\Ticketsallocation;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Image;
use Log;
use Validator;

class EventController extends Controller
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
    /**
     * Show the 'Create Event' Modal
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showCreateEvent(Request $request)
    {
        return view('ManageEvents.Modals.CreateEvent');
    }

    /**
     * Create an event
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateEvent(Request $request)
    {
        $event = Event::createNew();

        if (!$event->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $event->errors(),
            ]);
        }

        /*Get latest id from events table */
        //$Max_query = new Event();
      //  $Max_id = $Max_query->max('id');

        //$event->id = $Max_id+1;
		$event->ordcatID = $request->get('category');
        $event->itemName = $request->get('title');
        $event->itemDescription = strip_tags($request->get('description'));
        $event->itemDate = $request->get('start_date') ? Carbon::createFromFormat('d-m-Y H:i',
            $request->get('start_date')) : null;
        $event->itemLocation = $request->get('location_venue_name');
        $event->itemAddress = $request->get('location_address_line');
        $event->countryID = '23';
        $event->itemShowInCalendar = '-1';
        $event->CreatedBy = Auth::user()->id;
        $event->LastUpdatedBy = Auth::user()->id;

        $event->itemEndDate = $request->get('end_date') ? Carbon::createFromFormat('d-m-Y H:i',
            $request->get('end_date')) : null;

        if ($request->hasFile('event_image')) {
            $path = public_path() . '/' . config('tickets.event_images_path');
            $filename = 'event_image-' . md5(time() . $event->id) . '.' . strtolower($request->file('event_image')->getClientOriginalExtension());

            $file_full_path = $path . '/' . $filename;
            $db_save_path = config('tickets.event_images_path'). '/' . $filename;
            $request->file('event_image')->move($path, $filename);

            $img = Image::make($file_full_path);

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($file_full_path);

            \Storage::put(config('tickets.event_images_path') . '/' . $filename, file_get_contents($file_full_path));
            $event->itemImage = $db_save_path;
        }

        try {
            $event->save();
            $insertedeventId = $event->id;
			$this->createTickets($insertedeventId,$request->get('category'));
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status'   => 'error',
                'messages' => 'Whoops! There was a problem creating your event. Please try again.',
            ]);
        }

        return response()->json([
            'status'      => 'success',
            'id'          => $event->id,
            'redirectUrl' => route('showDashboard'),
        ]);
    }
	
	
	
	private function createTickets($event_id,$order_category)
    {   
		if($order_category == 3 || $order_category == 8 || $order_category == 9){
			$ticket_types = array('€20 ticket allocation','Kaleidoscope Passes Only');
		}
		else if($order_category == 6) {
			$ticket_types = array('Box','General Admission','Hospitality lounge and Ticket');
		}
		foreach($ticket_types as $type) {
			$ticket = Ticket::createNew();
			$ticket->event_id = $event_id;
			$ticket->ticketName = $type;
			$ticket->ticketProductCode = '';
			$ticket->ticketReleaseDate = Carbon::now();
			$ticket->ticketExpiryDate =  Carbon::now();
			$ticket->CreatedBy = Auth::user()->id;
			$ticket->LastUpdatedBy = Auth::user()->id;
			try {
				$ticket->save();
				$insertedticketId = $ticket->id;
				$this->createNewAllocation($insertedticketId,$type,$order_category);
			} catch (\Exception $e) {
				Log::error($e);
			}
		}
        
    }

    // TODO: This function needs to be tidies up, the array is being created multiple times, its not necessary.
	 private function createNewAllocation($ticket_id,$type,$order_category)
    {
		if($order_category == 3 || $order_category == 8 || $order_category == 9) {
			if($type == '€20 ticket allocation') {
				$clients = array('21'=>'CEO','39'=>'CCO','17'=>'BusinessSales','42'=>'LoyaltyProgramme','28'=>'Sponsorship','27'=>'IntComms','13'=>'CorpAffairs');
				foreach($clients as $client=>$value){		
					switch($client){
						case 21:
							$quantity_available = 6;
							break;
						case 39:
							$quantity_available = 8;
							break;
						case 17:
							$quantity_available = 8;
							break;
						case 42:
							$quantity_available = 20;
							break;
						case 28:
							$quantity_available = 4;
							break;
						case 27:
							$quantity_available = 2;
							break;
						case 13:
							$quantity_available = 2;
						break;
					}
							
					$ticket_allocation = Ticketsallocation::createNew();
					$ticket_allocation->role_id = $client;
					$ticket_allocation->ticket_id = $ticket_id;
					$ticket_allocation->ticketPrice = 20;			
					$ticket_allocation->ticketPriceLabel = $type;
					$ticket_allocation->ticketTotalAvailable = $quantity_available;
					$ticket_allocation->CreatedBy = Auth::user()->id;
					$ticket_allocation->LastUpdatedBy = Auth::user()->id;
					try {
						$ticket_allocation->save();
					} catch (\Exception $e) {
						Log::error($e);
					}
				}
			} else if($type == 'Kaleidoscope Passes Only'){
				$clients = array('21'=>'CEO','39'=>'CCO','17'=>'BusinessSales','42'=>'LoyaltyProgramme','28'=>'Sponsorship','27'=>'IntComms','13'=>'CorpAffairs');
				foreach($clients as $client=>$value) {
					switch ($client) {
						case 21:
							$quantity_available = 6;
							break;
						case 39:
							$quantity_available = 8;
							break;
						case 17:
							$quantity_available = 8;
							break;
						case 42:
							$quantity_available = 20;
							break;
						case 28:
							$quantity_available = 4;
							break;
						case 27:
							$quantity_available = 2;
							break;
						case 13:
							$quantity_available = 2;
							break;
					}
					$ticket_allocation = Ticketsallocation::createNew();
					$ticket_allocation->role_id = $client;
					$ticket_allocation->ticket_id = $ticket_id;
					$ticket_allocation->ticketPrice = 0;
					$ticket_allocation->ticketPriceLabel = $type;
					$ticket_allocation->ticketTotalAvailable = $quantity_available;
					$ticket_allocation->CreatedBy = Auth::user()->id;
					$ticket_allocation->LastUpdatedBy = Auth::user()->id;
					try {
						$ticket_allocation->save();
					} catch (\Exception $e) {
						Log::error($e);
					}
				}
			} 
		}
		else if($order_category == 6){
			$clients = array('21'=>'CEO','39'=>'CCO','17'=>'BusinessSales','42'=>'LoyaltyProgramme','28'=>'Sponsorship','27'=>'IntComms','13'=>'CorpAffairs');
			if($type == 'Box') {			
				foreach($clients as $client=>$value){		
					switch($client){
						case 21:
							$quantity_available = 4;
							break;
						case 39:
							$quantity_available = 8;
							break;
						case 17:
							$quantity_available = 28;
							break;
						case 42:
							$quantity_available = 20;
							break;
						case 28:
							$quantity_available = 14;
							break;
						case 27:
							$quantity_available = 2;
							break;
						case 13:
							$quantity_available = 2;
						break;
					}
							
					$ticket_allocation = Ticketsallocation::createNew();
					$ticket_allocation->role_id = $client;
					$ticket_allocation->ticket_id = $ticket_id;
					$ticket_allocation->ticketPrice = 250;			
					$ticket_allocation->ticketPriceLabel = $type;
					$ticket_allocation->ticketTotalAvailable = $quantity_available;
					$ticket_allocation->CreatedBy = Auth::user()->id;
					$ticket_allocation->LastUpdatedBy = Auth::user()->id;
					try {
						$ticket_allocation->save();
					} catch (\Exception $e) {
						Log::error($e);
					}
				}
			} else if($type == 'General Admission'){
				foreach($clients as $client=>$value){		
					switch($client){
						case 21:
							$quantity_available = 20;
							break;
						case 39:
							$quantity_available = 20;
							break;
						case 17:
							$quantity_available = 20;
							break;
						case 42:
							$quantity_available = 20;
							break;
						case 28:
							$quantity_available = 20;
							break;
						case 27:
							$quantity_available = 20;
							break;
						case 13:
							$quantity_available = 20;
						break;
					}
							
					$ticket_allocation = Ticketsallocation::createNew();
					$ticket_allocation->role_id = $client;
					$ticket_allocation->ticket_id = $ticket_id;
					$ticket_allocation->ticketPrice = 0;			
					$ticket_allocation->ticketPriceLabel = $type;
					$ticket_allocation->ticketTotalAvailable = $quantity_available;
					$ticket_allocation->CreatedBy = Auth::user()->id;
					$ticket_allocation->LastUpdatedBy = Auth::user()->id;
					try {
						$ticket_allocation->save();
					} catch (\Exception $e) {
						Log::error($e);
					}
				}
			}
			else if($type == 'Hospitality lounge and Ticket'){
				foreach($clients as $client=>$value){		
					switch($client){
						case 21:
							$quantity_available = 20;
							break;
						case 39:
							$quantity_available = 20;
							break;
						case 17:
							$quantity_available = 20;
							break;
						case 42:
							$quantity_available = 20;
							break;
						case 28:
							$quantity_available = 20;
							break;
						case 27:
							$quantity_available = 20;
							break;
						case 13:
							$quantity_available = 20;
						break;
					}
							
					$ticket_allocation = Ticketsallocation::createNew();
					$ticket_allocation->role_id = $client;
					$ticket_allocation->ticket_id = $ticket_id;
					$ticket_allocation->ticketPrice = 150;			
					$ticket_allocation->ticketPriceLabel = $type;
					$ticket_allocation->ticketTotalAvailable = $quantity_available;
					$ticket_allocation->CreatedBy = Auth::user()->id;
					$ticket_allocation->LastUpdatedBy = Auth::user()->id;
					try {
						$ticket_allocation->save();
					} catch (\Exception $e) {
						Log::error($e);
					}
				}
			}
		}
    }


    public function showEventDashboard(Request $request, $event_id)
    {
        $allowed_sorts = [
            'created_at'    => 'Creation date',
            'title'         => 'Ticket title',
            'sort_order'  => 'Custom Sort Order',
        ];

        // Getting get parameters.
        $q = $request->get('q', '');
        $sort_by = $request->get('sort_by');
        if (isset($allowed_sorts[$sort_by]) === false) {
            $sort_by = 'sort_order';
        }
        $sort_order = $request->get('sort_order') == 'asc' ? 'asc' : 'desc';
        $event_obj = new Event();
        $event = $event_obj->select('itemName')->where('id', '=', $event_id)->get();
        $event_name = $event[0]->itemName;
        $event_tickets = $event_obj
                              ->join('tickets', 'tickets.event_id', '=', 'events.id') //find($event_id)->ticket;
                              ->join('users', 'tickets.LastUpdatedBy', '=', 'users.id')
                              ->where('tickets.event_id', '=', $event_id)
                              ->select('tickets.id as ticket_id', 'tickets.ticketName', 'users.Firstname', 'users.Lastname', 'tickets.updated_at')
                              ->get();



        return view('ManageEvents.EventDashboard', compact('event_name', 'event_id', 'event_tickets', 'sort_by', 'q', 'allowed_sorts', 'sort_order'));
    }
	
	
	/**
	* Delete Event
	**/
	public function deleteEvent(Request $request){
		try{
		Event::destroy($request->event_id);
		return response()->json([
                'status'   => 'success',
                'messages' => 'Event deleted',
        ]);
		}
		catch(\Exception $e){
			Log::error($e);
			return response()->json([
                'status'   => 'error',
                'messages' => 'Woops! something went wrong.',
        ]);
		}
	}
}
