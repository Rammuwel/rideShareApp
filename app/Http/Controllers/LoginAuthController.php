<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SentOtpNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginAuthController extends Controller
{
    //

    public function store(Request $request)
    {

        $request->validate([
            'number' => 'required|numeric|min:10',
            'country' => 'required|max:3'
        ]);

        $mobile = $request->country . $request->number;
        $name = "User_" . rand(1000, 9999);

        $user = User::firstOrCreate(
            ["mobile" => $mobile],
            ["name" => $name]
        );

        $login_token =  rand(100000, 999999);
        $user->notify(new SentOtpNotification($login_token));

        if (!$user) {
            return response()->json(['message' => "User not found try after some time"], 401);
        }

        return response()->json(["mobile"=>$mobile,'message' => "OTP send on  your mobile number $mobile"], 200);
    }



    public function verify(Request $request)
    {
      
        $request->validate([
            'mobile' => 'required|min:10',
            'login_code' => 'required|numeric|digits:6|between:100000,999999',
        ]); //(422)

        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            return response()->json(["message" => "User not found try again",], 404);
        }

        if ($user->login_code != $request->login_code) {
            return response()->json(["message" => "OTP  not match please resend!",], 401);
        }

        Auth::login($user, true); // this for web user in my case web route

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->update(['login_code' => null]);
        return response()->json(['token' => $token], 200);
    }
}
