<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\Venue;
use Illuminate\Http\Request;
use Session;

class TicketAllowanceController extends Controller
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
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
		$roles = Role::where('id', '<>', '0')->where('Status', '=', '1')->select('id', 'name', 'display_name')->get();
		
        return view('admin.ticket-allowance.index', compact('roles'));
    }
	
	public function create(){
		
		
	}
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
	}
	/**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
	public function show($id)
    {
	}
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
		$venue = Venue::select('venue_name')->get();
		$venue = $venue->pluck('venue_name');
		return view('admin.ticket-allowance.edit', compact('venue'));
	}
	 /**
     * Update the specified resource in storage.
     *
     * @param  int      $id
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {
	}
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
	}
}
