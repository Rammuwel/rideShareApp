<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class WebController extends Controller
{
    //
    public function index(Request $request)
    {

        $user = $request->user();
        $user->load('driver');
        $userName = $user->name;
        $isDriver = $user->driver !== null;

        return view('home', compact(['isDriver', 'userName']));
    }
    public function login_driver()
    {

        return view('driver-login');
    }
    public function driver(Request $request)
    {
        $user = $request->user()->load('driver');

        return view('driver', compact('user'));
    }


    public function tripDriver(Request $request, Trip $trip)
    {


        $user = $request->user();

        $trip->load('driver.user');



        if ($trip->driver_id && $user->driver) {
            if ($trip->driver_id === $user->driver->user_id) {
                return view('driver-trip', ["trip" => $trip]);
            }
        }



        return response()->json(["massage" => "This trip not found"], 404);
    }
    public function tripUser(Request $request, Trip $trip)
    {


        $user = $request->user();

        $trip->load('driver.user');



        if ($trip->user_id === $request->user()->id) {
            return view('trip', ["trip" => $trip]);
        }

        return response()->json(["massage" => "This trip not found"], 404);
    }
}
