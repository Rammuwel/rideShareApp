<?php

use App\Models\Trip;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('drivers', function ($user) {
    return $user->driver !== null;
});

// Broadcast::channel("trip.{id}", function ($user) {
//     return true; 
// });

Broadcast::channel('trip.{id}', function ($user, $id) {
    
    $trip = Trip::find($id);

    if (!$trip) {
        return false;
    }
    return (int) $user->id === (int) $trip->user_id || 
           (int) $user->id === (int) $trip->driver_id;
});