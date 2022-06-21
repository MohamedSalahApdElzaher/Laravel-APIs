<?php

namespace App\Http\Controllers\JWT;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // REGISTER API - POST
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_no' => 'required',
            'password' => 'required|confirmed'
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;
        $user->password = bcrypt($request->password); // hash password

        $user->save();

        return response()->json([
            'status' => 1,
            'message' => 'user created successfully',
            'user' => $user
        ], 200);
    }

    // LOGIN API - POST
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($token = auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
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
            'phone_no' => !empty($request->phone_no) ? $request->phone_no : $user->phone_no,
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
