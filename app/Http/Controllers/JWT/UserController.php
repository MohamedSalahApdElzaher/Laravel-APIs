<?php

namespace App\Http\Controllers\JWT;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

class UserController extends Controller
{
    // REGISTER API | send verification code to mobile phone - POST
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required|unique:users',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($request->phone_number, "sms");

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->password = bcrypt($request->password); // hash password

        $user->save();

        return response()->json([
            'status' => 1,
            'message' => 'user created successfully | verification code sent to your phone number',
            'user' => $user
        ], 200);
    }

    // verify code sent to user
    protected function verify(Request $request)
    {
        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string'],
        ]);
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create($data['verification_code'], array('to' => $data['phone_number']));
        if ($verification->valid) {
            $user = tap(User::where('phone_number', $data['phone_number']))->update(['isVerified' => 1]);
            return response()->json([
                'status' => 1,
                'message' => 'phone number verified successfully',
                'user info' => $user
            ]) ;
        }
        return response()->json([
            'status' => 0,
            'message' => 'Invalid verification code entered!'
        ]);
    }

    // LOGIN API - POST
    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'password' => 'required'
        ]);

        if ($token = auth()->attempt(['phone_number' => $request->phone_number, 'password' => $request->password])) {
            return response()->json([
                'status' => 1,
                'message' => 'user logged in',
                'data' => [
                    'user' => auth()->user(),
                    'token' => $token
                ]
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'user invalid data'
            ], 404);
        }
    }


    // PROFILE API - GET
    public function profile()
    {
        return response()->json([
            'status' => 1,
            'message' => 'user profile',
            'user' => auth()->user()
        ]);
    }

    // UPDATE API - PUT
    public function update(Request $request)
    {
        $user = auth()->user();
        User::where('id', $user->id)->update([
            'name' => !empty($request->name) ? $request->name : $user->name,
            'phone_number' => !empty($request->phone_number) ? $request->phone_number : $user->phone_number,
        ]);
        return response()->json([
            'status' => 1,
            'message' => 'profile updated',
            'user' => $user
        ]);
    }


    // LOGOUT API - GET
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'status' => 1,
            'message' => 'user logout successfullay',
        ]);
    }
}
