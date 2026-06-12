<?php

namespace App\Http\Controllers;

use App\Events\AcceptTripEvent;
use App\Events\CreateTripEvent;
use App\Events\EndTripEvent;
use App\Events\StartTripEvent;
use App\Models\Trip;
use Illuminate\Http\Request;
use Nette\Utils\Json;

class TripController extends Controller
{
    //

    public  function index(Request $request)
    {
        $trips = $request->user()->load('trips');
        return $trips;
    }

    public  function show(Request $request, Trip $trip)
    {
        
         $user = $request->user();

        if ($trip->user_id === $request->user()->id) {
            return response()->json($trip, 200);
        }

        if ($trip->driver_id && $user->driver) {
            if ($trip->driver_id === $user->driver->user_id) {
                return response()->json($trip, 200);
            }
        }
        return response()->json(["massage" => "This trip not found"], 404);
    }


    public function create(Request $request)
    {

        $request->validate([
            'origin_name' => 'required|string',
            'destination_name' => 'required|string',
            'origin' => 'required',
            'destination' => 'required'
        ]);


        

        $trip = $request->user()->trips()->create($request->only([
            'origin_name',
            'destination_name',
            'origin',
            'destination'
        ]));

       broadcast(new CreateTripEvent($trip));

        return $trip;
    }

    public function accept(Request $request, Trip $trip){
        
        $request->validate([
            'driver_location' => 'required|array'
        ]);
          
      
        // return $trip->load('driver');
        $trip->update([
              'driver_id' => $request->user()->driver->user_id,
              'driver_location' => $request->driver_location
         ]);

         $trip->load('driver');
         $trip->driver->load('user');
        broadcast(new AcceptTripEvent($trip));
         return  $trip;

    }
    public function start(Request $request, Trip $trip){
         $trip->update([
            'is_started' => true,
         ]);
         $trip->load('driver.user');

         broadcast(new StartTripEvent($trip));
         
         return $trip;
    }
    public function end(Request $request, Trip $trip){
          $trip->update([
            'is_completed' => true,
         ]);

         $trip->load('driver.user');
          
          broadcast(new EndTripEvent($trip));

          return $trip;
    }
    public function location(Request $request, Trip $trip){
         
       $request->validate([
        'driver_location' => 'required|array'
       ]);

        $trip->update([
        'driver_location' => $request->driver_location
        ]);
       
         $trip->load('driver.user');
         
         return $trip;
    }
}
