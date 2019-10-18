<?php

namespace App;

class Transaction extends MyBaseModel
{
    public function useritem()
    {
        return $this->hasMany('App\Useritem');
    }
}
