<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use URL;

class Ticket extends MyBaseModel
{
/**
     * The validation rules.
     *
     * @var array $rules
     */
    protected $rules = [
        'title'               => ['required']     
    ];

    /**
     * The validation error messages.
     *
     * @var array $messages
     */
    protected $messages = [
        'title.required'                       => 'You must at least give a title for your Ticket.'   
    ];
   /**
     * Get the ticket id in ticket allocation.
     */
    public function ticketsallocation()
    {
        return $this->hasMany('App\Ticketsallocation');
    }
}
