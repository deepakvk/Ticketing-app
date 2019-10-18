<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});
*/
//Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', [
    'as'   => 'showDashboard',
    'uses' => 'DashboardController@showDashboard',
]);
Route::get('/', [
    'as'   => 'showDashboard',
    'uses' => 'DashboardController@showDashboard',
]);

Route::get('/orders', [
    'as'   => 'showOrders',
    'uses' => 'OrderController@showOrders',
]);
Route::get('orders/export/{export_as?}', [
            'as'   => 'showExportOrders',
            'uses' => 'OrderController@showExportOrders',
        ]);
Route::get('reports', [
            'as'   => 'showReports',
            'uses' => 'ReportController@showCreateReports',
        ]);
Route::post('reports/{export_as?}', [
            'as'   => 'postCreateReports',
            'uses' => 'ReportController@postCreateReports',
        ]);
Route::get('show_guests', [
            'as'   => 'showGuests',
            'uses' => 'GuestController@showGuests',
        ]);		

    /*
     * Event dashboard
     */
     Route::group(['middleware' => 'admin', 'prefix' => 'event'], function () {
        Route::get('/{id}/dashboard', [
           'as'   => 'showEventDashboard',
           'uses' => 'EventController@showEventDashboard',
        ]);
	   Route::post('delete_event', [
			'as'   => 'deleteEvent',
			'uses' => 'EventController@deleteEvent'
		]);
     });

    /*
     * Events
     */
    Route::group(['prefix' => 'events'], function () {
        /*
         * ----------
         * Create Event
         * ----------
         */
        Route::get('/create', [
            'as'   => 'showCreateEvent',
            'uses' => 'EventController@showCreateEvent',
        ]);

        Route::post('/create', [
            'as'   => 'postCreateEvent',
            'uses' => 'EventController@postCreateEvent',
        ]);

        /*
         * ----------
         * Tickets
         * ----------
         */
         Route::get('{id}/tickets', [
             'as'   => 'requestTickets',
             'uses' => 'TicketController@requestTickets',
         ]);
        Route::post('{id}/tickets', [
             'as'   => 'postCreateTickets',
             'uses' => 'TicketController@postCreateTickets',
         ]);
        Route::get('{id}/tickets/create', [
             'as'   => 'showNewTicket',
             'uses' => 'TicketController@showNewTicket',
         ]);
        Route::post('{id}/tickets/create', [
             'as'   => 'postNewTicket',
             'uses' => 'TicketController@postNewTicket',
         ]);
        Route::get('tickets/{ticket_id}/ticketsallocation', [
              'as'   => 'showEditAllocation',
              'uses' => 'TicketController@showEditAllocation',
          ]);
        Route::get('tickets/{ticket_id}/newallocation', [
              'as'   => 'showNewAllocation',
              'uses' => 'TicketController@showNewAllocation',
          ]);
        Route::post('tickets/{ticket_id}/newallocation', [
              'as'   => 'postNewAllocation',
              'uses' => 'TicketController@postNewAllocation',
          ]);
        Route::get('tickets/{ticket_id}/editallocation', [
                'as'   => 'EditAllocationShow',
                'uses' => 'TicketController@EditAllocationShow',
            ]);
        Route::post('tickets/{ticket_id}/editallocation', [
                'as'   => 'EditAllocationPost',
                'uses' => 'TicketController@EditAllocationPost',
            ]);
        Route::post('ticketsalloc/{alloc_id}/deleteallocation', [
                'as'   => 'DeleteAllocation',
                'uses' => 'TicketController@DeleteAllocation',
            ]);
        Route::get('tickets/{alloc_id}/viewallocationorders', [
                'as'   => 'ViewAllocationOrders',
                'uses' => 'TicketController@ViewAllocationOrders',
            ]);
    });
    /*
     * ----------
     * Guests
     * ----------
     */
Route::group(['prefix' => 'guests'], function () {
    Route::get('{transaction_id}/{alloc_id}/create', [
          'as'   => 'showNewGuest',
          'uses' => 'GuestController@showNewGuest',
      ]);
    Route::post('create', [
          'as'   => 'postNewGuest',
          'uses' => 'GuestController@postNewGuest',
      ]);
    Route::get('{guest_id}/{alloc_id}/edit', [
          'as'   => 'EditGuestShow',
          'uses' => 'GuestController@EditGuestShow',
      ]);
    Route::post('{guest_id}/edit', [
          'as'   => 'EditGuestPost',
          'uses' => 'GuestController@EditGuestPost',
      ]);
    Route::post('{guest_id}/delete', [
          'as'   => 'GuestDelete',
          'uses' => 'GuestController@GuestDelete',
      ]);
    Route::post('{transaction_id}/{alloc_id}/replicate', [
           'as'   => 'replicateGuest',
           'uses' => 'GuestController@replicateGuest',
      ]);
});
Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
    Route::resource('/', 'Admin\AdminController');
    Route::resource('users', 'Admin\UsersController');
    Route::resource('roles', 'Admin\RolesController');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::get('give-role-permissions', 'Admin\AdminController@getGiveRolePermissions');
    Route::post('give-role-permissions', 'Admin\AdminController@postGiveRolePermissions');
	Route::post('toggle-role-permissions', [
			'as'	=> 'toggle-role-permissions',
			'uses'	=> 'Admin\AdminController@getRolePermissionAjax',
	]);
	Route::resource('ticket-allowance', 'Admin\TicketAllowanceController');
	//Route::post('ticket-allowance','Admin\TicketAllowanceController@postAllowance');
});
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);