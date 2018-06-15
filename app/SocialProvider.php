<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class SocialProvider extends Model
{
    public function Users()
    {
    	return $this->belongsTo(User::class);
    }
}
