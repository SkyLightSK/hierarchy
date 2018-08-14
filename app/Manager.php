<?php

namespace App;

use App\Traits\RelatedUser;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{

    const TYPE = 'manager';

    use RelatedUser;

    public function obey()
    {
        return $this->belongsTo('App\Director');
    }

    public function subordinates()
    {
        return $this->hasMany('App\Team_Lead');
    }

}
