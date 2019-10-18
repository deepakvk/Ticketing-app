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

class OrderController extends Controller
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


    public function showOrders(Request $request)
    {
        $allowed_sorts = ['next_2_weeks','created_at'];

        $searchQuery = $request->get('q');
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'next_2_weeks');
        $sort_order = $request->get('sort_order') == 'asc' ? 'asc' : 'desc';
		
		$roles = Auth::user()->roles;


        $trans_obj = new Transaction();
        if ($searchQuery) {
			if($roles[0]->id==0){
            $transactions = $trans_obj->join('useritems', 'transactions.id', '=', 'useritems.transaction_id')
                                ->join('ticketsallocations', 'ticketsallocations.id', '=', 'useritems.ticketsallocation_id')
                                ->join('tickets', 'tickets.id', '=', 'ticketsallocations.ticket_id')
                                ->join('events', 'events.id', '=', 'tickets.event_id')
                                ->where(function ($query) use ($searchQuery) {
                                    $query->where('events.itemName', 'like', $searchQuery . '%')
                                        ->orWhere('tickets.ticketName', 'like', $searchQuery . '%')
                                        ->orWhere('transactions.id', 'like', $searchQuery . '%');
                                })
                                ->orderBy('events.created_at', $sort_order)
								->orderBy('ticketsallocations.id', $sort_order)
								->groupBy('ticketsallocations.id')
								->select('ticketsallocations.role_id', 'transactions.id', 'transactions.user_id', 'transactions.transDate', 'transactions.created_at', 'ticketsallocations.id as alloc_id', 'ticketsallocations.ticketTotalAvailable', 'events.itemName', 'events.itemDate', 'useritems.id as useritems_id', DB::raw("SUM(useritems.usritemNoReq) as total"), 'tickets.ticketName');//->paginate(25);
			}
			else {
				$transactions = $trans_obj->join('useritems', 'transactions.id', '=', 'useritems.transaction_id')
                                ->join('ticketsallocations', 'ticketsallocations.id', '=', 'useritems.ticketsallocation_id')
                                ->join('tickets', 'tickets.id', '=', 'ticketsallocations.ticket_id')
                                ->join('events', 'events.id', '=', 'tickets.event_id')                               
                                ->where(function ($query) use ($searchQuery) {
                                    $query->where('events.itemName', 'like', $searchQuery . '%')
                                        ->orWhere('tickets.ticketName', 'like', $searchQuery . '%')
                                        ->orWhere('transactions.id', 'like', $searchQuery . '%');
                                })
								->where('ticketsallocations.role_id', '=', Auth::user()->roles->first()->id)
                                ->orderBy('events.created_at', $sort_order)
								->orderBy('ticketsallocations.id', $sort_order)
								->groupBy('ticketsallocations.id')
								->select('ticketsallocations.role_id', 'transactions.id', 'transactions.user_id', 'transactions.transDate', 'transactions.created_at', 'ticketsallocations.id as alloc_id', 'ticketsallocations.ticketTotalAvailable', 'events.itemName', 'events.itemDate', 'useritems.id as useritems_id', DB::raw("SUM(useritems.usritemNoReq) as total"), 'tickets.ticketName');
								//->paginate(25);
			}	
        } else {
		
			if($roles[0]->id==0){
            $transactions = $trans_obj->join('useritems', 'transactions.id', '=', 'useritems.transaction_id')
                                  ->join('ticketsallocations', 'ticketsallocations.id', '=', 'useritems.ticketsallocation_id')
                                  ->join('tickets', 'tickets.id', '=', 'ticketsallocations.ticket_id')
                                  ->join('events', 'events.id', '=', 'tickets.event_id')
								  ->orderBy('events.created_at', $sort_order)
								  ->orderBy('ticketsallocations.id', $sort_order)
								  ->groupBy('ticketsallocations.id')
                                  ->select('ticketsallocations.role_id', 'transactions.id', 'transactions.user_id', 'transactions.transDate', 'transactions.created_at', 'ticketsallocations.id as alloc_id', 'ticketsallocations.ticketTotalAvailable', 'events.itemName', 'events.itemDate', 'useritems.id as useritems_id', DB::raw("SUM(useritems.usritemNoReq) as total"), 'tickets.ticketName');                                 
								  //->paginate(25);
			}
			else {
				$transactions = $trans_obj->join('useritems', 'transactions.id', '=', 'useritems.transaction_id')
                                  ->join('ticketsallocations', 'ticketsallocations.id', '=', 'useritems.ticketsallocation_id')
                                  ->join('tickets', 'tickets.id', '=', 'ticketsallocations.ticket_id')
                                  ->join('events', 'events.id', '=', 'tickets.event_id')                                 
								  ->where('ticketsallocations.role_id', '=', Auth::user()->roles->first()->id)
								  ->orderBy('events.created_at', $sort_order)
								  ->orderBy('ticketsallocations.id', $sort_order)
								  ->groupBy('ticketsallocations.id')
                                  ->select('ticketsallocations.role_id', 'transactions.id', 'transactions.user_id', 'transactions.transDate', 'transactions.created_at', 'ticketsallocations.id as alloc_id', 'ticketsallocations.ticketTotalAvailable', 'events.itemName', 'events.itemDate', 'useritems.id as useritems_id', DB::raw("SUM(useritems.usritemNoReq) as total"), 'tickets.ticketName');
                                  //->paginate(25);
			}		
        }
		
		if($sort_by == 'next_2_weeks'){
			$transactions = $transactions->whereBetween('events.itemDate', array(Carbon::now(), Carbon::now()->addWeeks(2)))->paginate(25);
		}
		else {
			$transactions = $transactions->paginate(25);
		}
		
		$total_orders = $transactions->total();
		$links = $transactions->appends(request()->query())->links();
		$trans_count = $transactions->count();
		
		$transactions = $transactions->groupBy('itemName','itemDate');
	
		$guests_obj = new Guestdetail();
        
		
        $data = [
          'transactions' => $transactions,
          'guests_obj'   => $guests_obj,
		  'role'		 => $roles[0]->id,
          'sort_by'      => $request->get('sort_by') ? $request->get('sort_by') : '',
          'sort_order'   => $sort_order,
          'q'            => $searchQuery ? $searchQuery : '',
		  'count'		 => 0,
		  'total_orders' => $total_orders,
		  'links'        => $links,
		  'trans_count'  => $trans_count

        ];
        return view('orders', $data);
    }

    /**
     * Exports order to popular file types
     *
     * @param $event_id
     * @param string $export_as Accepted: xls, xlsx, csv, pdf, html
     */
    public function showExportOrders($export_as = 'xls')
    {
        Excel::create('orders-as-of-' . date('d-m-Y-g.i.a'), function ($excel) {
            $excel->setTitle('Orders');

            // Chain the setters
            $excel->setCreator(config('Three Event Ticketing'))
                ->setCompany(config('Three'));

            $excel->sheet('orders_sheet_1', function ($sheet) {
                $trans_obj = new Transaction();
                $data = $trans_obj
                           ->join('useritems', 'transactions.id', '=', 'useritems.transaction_id')
                           ->join('ticketsallocations', 'ticketsallocations.id', '=', 'useritems.ticketsallocation_id')
                           ->join('tickets', 'tickets.id', '=', 'ticketsallocations.ticket_id')
                           ->join('events', 'events.id', '=', 'tickets.event_id')
                           ->where('transactions.user_id', '=', Auth::user()->id)
                           ->select([
                              'transactions.id',
                              'transactions.created_at',
                              'events.itemName',
                              'tickets.ticketName',
                           ])->get();
                //DB::raw("(CASE WHEN UNIX_TIMESTAMP(`attendees.arrival_time`) = 0 THEN '---' ELSE 'd' END) AS `attendees.arrival_time`"))

                $sheet->fromArray($data);

                // Add headings to first row
                $sheet->row(1, [
                    'Order Reference',
                    'Order Date',
                    'Event Name',
                    'Ticket Name',
                ]);

                // Set gray background on first row
                $sheet->row(1, function ($row) {
                    $row->setBackground('#f5f5f5');
                });
            });
        })->export($export_as);
    }
}
