<?php

namespace App;
use App\{User};

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];
    public $timestapms = false;

    public function doctor()
    {
        return $this->belongsTo(User::class);
    }
     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
