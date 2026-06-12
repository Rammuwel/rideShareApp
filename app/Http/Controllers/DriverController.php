<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    //

    public function show(Request $request)
    {
        $user = $request->user();
        $user->load('driver');

        return response()->json($user->load('driver'), 200);
    }

    public function store(Request $request)
    {
      

        $request->validate([
            'name' => 'required|string',
            'license_number' => 'required|unique:drivers,license_number,' . ($request->user()->driver->id ?? 'NULL'),
            'vehicle_model'  =>  'required',
            'vahical_plate'  =>  'required',
            'profile_photo_path' => 'nullable|string',
            'status' => 'nullable|string',
            'rating' => 'nullable|numeric|between:0,5',
        ]);



        // store driver
        $user = $request->user();

        $user->update([
            'name' => $request->name,
        ]);

        $user->load('driver');
         

        $user->driver()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'license_number',
                'vehicle_model',
                'vahical_plate',
                'status',
                'profile_photo_path',
                'rating'

            ])

        );

        return response()->json($user->load('driver'), 200);
    }
}
