<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class SocialProvider extends Model
{
	protected $fillable = [
        'provider_id', 'provider'
    ];

    public function User()
    {
    	return $this->belongsTo(User::class);
    }
}
