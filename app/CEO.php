<?php

namespace App;

use App\Traits\RelatedUser;
use Illuminate\Database\Eloquent\Model;

class CEO extends Model
{

    const TYPE = 'ceo';

    use RelatedUser;

    public function subordinates()
    {
        return $this->hasMany('App\Director');
    }
}
