<?php

namespace App;

use App\Traits\RelatedUser;
use Illuminate\Database\Eloquent\Model;

class Team_Lead extends Model
{

    const TYPE = 'team_lead';

    use RelatedUser;

    public function obey()
    {
        return $this->belongsTo('App\Manager');
    }

    public function subordinates()
    {
        return $this->hasMany('App\Staff');
    }
}
