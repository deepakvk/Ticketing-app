<?php

namespace App;

class Ticketsallocation extends MyBaseModel
{
	protected $appends = [
		'remaining_tickets',
		'business_display_name'
	];

	protected $fillable = [
		'ticketPriceLabel',
		'ticketPrice',
		'ticketTotalAvailable'
	];

	public function useritem()
	{
		return $this->hasMany('App\Useritem');
	}

	public function getRemainingTicketsAttribute()
	{
		$countTickets = [];
		foreach($this->useritem as $userItem){
			$countTickets[] = $userItem->usritemNoReq;
		}

		$totalRequiredTickets = array_sum($countTickets);
		$remainingTickets = $this->ticketTotalAvailable - $totalRequiredTickets;

		return $remainingTickets;
	}

	public function role(){
		return $this->belongsTo(Role::class);
	}
}
