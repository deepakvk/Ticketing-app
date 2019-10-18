<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use URL;

class Guestdetail extends MyBaseModel
{

    /**
     * The validation rules.
     *
     * @var array $rules
     */
    protected $rules = [
        'first_name'          => ['required'],
        'last_name'           => ['required'],
        'company'             => ['required']
    ];

    /**
     * The validation error messages.
     *
     * @var array $messages
     */
    protected $messages = [
        'first_name.required'                   => 'Please enter the First name.',
        'last_name.required'                    => 'Please enter the Last name.',
        'company.required'                      => 'Please enter the company name.'
    ];
}
