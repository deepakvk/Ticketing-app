<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use URL;

class Useritem extends MyBaseModel
{
    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
