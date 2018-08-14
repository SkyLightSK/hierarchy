<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

use App\User;

trait RelatedUser
{
    public function user()
    {
        return $this->morphOne(User::class , 'userable');
    }

    public static function userPosition()
    {
        return self::TYPE;
    }

}