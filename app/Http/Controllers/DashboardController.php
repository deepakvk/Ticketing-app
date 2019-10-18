<?php

namespace App\Http\Controllers;

use Auth;
use App\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
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


    public function showDashboard(Request $request)
    {
		$roles = Auth::user()->roles;
        $events_obj = new Event();
        $allowed_sorts = ['next_2_weeks', 'itemDate', 'itemName'];
        $searchQuery = $request->get('q');

        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'next_2_weeks');
        $current_date = date("d/m/Y");

		if($sort_by != 'next_2_weeks'){
			$events = $searchQuery ? $events_obj
				->where([['itemName', 'like', '%' . $searchQuery . '%']])
				->orderBy($sort_by, 'desc')
				->paginate(10)
                : $events_obj
					->where('itemDate', '>=' ,Carbon::now())
					->orderBy($sort_by, 'asc')
					->paginate(10);
		} else {
			$events = $searchQuery ? $events_obj->where([['itemName', 'like', '%' . $searchQuery . '%']])
				->whereBetween('itemEndDate', array(Carbon::now(), Carbon::now()->addWeeks(2)))
				->orderBy('itemName', 'desc')
				->paginate(10)
				: $events_obj
					->whereBetween('itemDate', array(Carbon::now(), Carbon::now()->addWeeks(2)))
					->orderBy('itemDate', 'asc')
					->paginate(10);
		}

        $data = [
            'events'   => $events,
		    'sort_by'  => $request->get('sort_by') ? $request->get('sort_by') : '',
            'search'   => [
            	'q'        => $searchQuery ? $searchQuery : '',
                'sort_by'  => $request->get('sort_by') ? $request->get('sort_by') : '',
                'showPast' => $request->get('past'),
            ],
        ];
        return view('Dashboard', $data);
    }
}
