<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Trip extends Model
{
    
  public function user(){
    return $this->belongsTo(User::class);
  }

  public function driver(){
    return $this->belongsTo(Driver::class, 'driver_id','user_id');
  }
   
   protected $guarded = [];



   protected function casts()
   {
    return [
        'origin' => 'array',
        'destination' => 'array',
        'driver_location' => 'array'
    ];
   }
 
}
