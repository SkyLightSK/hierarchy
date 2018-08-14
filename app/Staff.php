<?php

namespace App;

use App\Traits\RelatedUser;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{

    const TYPE = 'staff';

    use RelatedUser;

    public function obey()
    {
        return $this->belongsTo('App\Team_Lead');
    }

}
