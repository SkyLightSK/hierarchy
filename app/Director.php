<?php

namespace App;

use App\Traits\RelatedUser;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{

    const TYPE = 'director';

    use RelatedUser;

    public function obey()
    {
        return $this->belongsTo('App\CEO');
    }

    public function subordinates()
    {
        return $this->hasMany('App\Manager');
    }
}
