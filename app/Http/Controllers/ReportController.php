<?php

namespace App\Http\Controllers;

use Auth;
use App\Event;
use App\Useritem;
use App\Transaction;
use DB;
use Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class ReportController extends Controller
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

	public function showCreateReports(Request $request)
	{
		return view('ManageReports.Modals.CreateReport');
	}

	public function postCreateReports(Request $request, $export_as = 'xls')
	{
		$this->validate($request, [
			'start_date' => 'required',
			'end_date' => 'required',
		], ['start_date.required' => 'Start date field is required', 'end_date.required' => 'End date field is required']);

		Excel::create('OTS Report-as-of-' . date('d-m-Y-g.i.a'), function ($excel) use ($request) {
			$excel->setTitle('OTS Report');

			// Chain the setters
			$excel->setCreator(config('Three Event Ticketing'))
				->setCompany(config('Three'));

			$excel->sheet('OTS_report_Sheet1', function ($sheet) use ($request) {

				$data = DB::table('events')
					->leftjoin('tickets', 'events.id', '=', 'tickets.event_id')
					->leftjoin('ticketsallocations', 'tickets.id', '=', 'ticketsallocations.ticket_id')
					->leftjoin('useritems', 'ticketsallocations.id', '=', 'useritems.ticketsallocation_id')
					->leftjoin('roles', 'ticketsallocations.role_id', '=', 'roles.id')
					->leftjoin('guestdetails', 'guestdetails.ticketsallocation_id', '=', 'ticketsallocations.id')
					->leftjoin('transactions', 'guestdetails.transaction_id', '=', 'transactions.id')
					->leftjoin('users', 'transactions.user_id', '=', 'users.id')
					->whereBetween('events.itemDate', [
						Carbon::createFromFormat('d-m-Y', $request->get('start_date'))->copy()->startOfDay(),
						Carbon::createFromFormat('d-m-Y', $request->get('end_date'))->copy()->endOfDay()
					])
					->orderby('guestdetails.transaction_id', 'ASC')
					->select('events.itemName AS Event', 'events.itemDate AS Eventdate',
						DB::raw("REPLACE(ticketsallocations.ticketPriceLabel,'?','€') AS Tickettype"), 'roles.display_name AS Businessunit',
						DB::raw("CONCAT(users.Firstname,' ',users.Lastname) AS Requester"),
						DB::raw("CONCAT(events.itemLocation,guestdetails.transaction_id) AS Orderno"),
						'guestdetails.id as GuestID', 'guestdetails.gstLastName', 'guestdetails.gstFirstName', 'guestdetails.gstCompany AS Company',
						'guestdetails.gstPosition As Job Title',
						'guestdetails.gstText1 As Special requests')
					->distinct()->get();

				$data = json_decode(json_encode($data), true);

				foreach ($data as $i => $data_item) {
					if ($data_item['Requester'] == null) {
						unset($data[$i]);
					}
				}

				//dd($data);

				$sheet->row(1, array(
					'Event Name',
					'Event Date',
					'Ticket type',
					'Business unit',
					'Requested By',
					'Order no.',
					'Guest ID',
					'Last name',
					'First name',
					'Company name',
					'Job title',
					'Special requests'
				));

				$sheet->fromArray($data, null, 'A2', false, false);

				// Set gray background on first row
				$sheet->row(1, function ($row) {
					$row->setBackground('#f5f5f5');
				});


				//dd($count);
			});

			$excel->sheet('OTS_report_Sheet2', function ($sheet) use ($request) {

				$data = DB::table('events')
					->leftjoin('tickets', 'events.id', '=', 'tickets.event_id')
					->leftjoin('ticketsallocations', 'tickets.id', '=', 'ticketsallocations.ticket_id')
					->leftjoin('useritems', 'ticketsallocations.id', '=', 'useritems.ticketsallocation_id')
					->leftjoin('roles', 'ticketsallocations.role_id', '=', 'roles.id')
					->leftjoin('guestdetails', 'guestdetails.ticketsallocation_id', '=', 'ticketsallocations.id')
					->whereBetween('events.itemDate', [Carbon::createFromFormat('d-m-Y', $request->get('start_date'))->copy()->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->get('end_date'))->copy()->endOfDay()])
					->select('events.itemName AS Event', 'events.itemDate AS Eventdate', 'ticketsallocations.ticketPriceLabel', 'roles.display_name AS Businessunit', 'ticketsallocations.ticketTotalAvailable', 'useritems.usritemNoReq', 'useritems.ticketsallocation_id')
					->distinct()
					->get()->toArray();

				/*foreach ($data as $order_item) {
					$order_item->guestNamesAdded = DB::table('guestdetails')->where('ticketsallocation_id', '=', $order_item->ticketsallocation_id)->count();
					unset($order_item->ticketsallocation_id);
				}*/
				$data = json_decode(json_encode($data),TRUE);

				$isExist = array();				
				foreach($data as $key => &$value){				
					if(in_array($value['ticketsallocation_id'], $isExist)){
						$getKey = array_search($value['ticketsallocation_id'], $isExist); 
						$data[$getKey]["usritemNoReq"] = $data[$getKey]["usritemNoReq"]+$value["usritemNoReq"];
						unset($data[$key]);
					}
					else{						
							$data[$key] = $value;
					}
					$isExist[$key] = $value['ticketsallocation_id'];	
					$value['guestNamesAdded'] = DB::table('guestdetails')->where('ticketsallocation_id', '=', $value['ticketsallocation_id'])->distinct()->count();
					
					unset($value['ticketsallocation_id']);
					
				}
								
				

				if (!empty($data)) {
					//$data_merged = call_user_func_array('array_merge', $data2);
					//dd($sheet_array);
					//$data = json_decode(json_encode($data), true);
					$sheet->fromArray($data, null, 'A2', false, false);
					$sheet->row(1, array(
						'Event Name',
						'Event Date',
						'Ticket type',
						'Business unit',
						'Total Allocation',
						'Tickets Ordered',
						'Guest Names Added'
					));

					$sheet->row(1, function ($row) {
						$row->setBackground('#f5f5f5');
					});
				}
			});
		})->download($export_as);

	}
}
