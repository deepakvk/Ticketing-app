<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use URL;

class Event extends MyBaseModel
{
    use SoftDeletes;

    protected $appends = [
        'item_date_formatted'
    ];

    /**
     * The validation rules.
     *
     * @var array $rules
     */
    protected $rules = [
        'title'               => ['required'],
        //'description'         => ['required'],
        'location_venue_name' => ['required_without:venue_name_full'],
        'start_date'          => ['required'],
        'end_date'            => ['required'],
        'event_image'         => ['mimes:jpeg,jpg,png', 'max:3000', 'nullable'],
    ];

    /**
     * The validation error messages.
     *
     * @var array $messages
     */
    protected $messages = [
        'title.required'                       => 'You must at least give a title for your event.',
        'event_image.mimes'                    => 'Please ensure you are uploading an image (JPG, PNG, JPEG)',
        'event_image.max'                      => 'Please ensure the image is not larger then 3MB',
        'location_venue_name.required_without' => 'Please enter a venue for your event',
    ];

    protected $dates = ['itemDate', 'itemEndDate'];

	/**
     * Get the event id in tickets table.
     */
    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }

	/**
	 * Get the event date in correct format.
	 */
    public function getItemDateFormattedAttribute()
    {
		return Carbon::createFromFormat('Y-m-d H:i:s', $this->itemDate)->format('dS M Y');
    }
}
