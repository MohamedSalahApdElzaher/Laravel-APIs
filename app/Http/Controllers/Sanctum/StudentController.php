<?php

namespace App\Http\Controllers\Sanctum;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * No need to authenticate
     */

    // REGISTER API
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'password' => 'required|confirmed',
        ]);

         $student = new Student();

         $student->name = $request->name;
         $student->email = $request->email;
         $student->password = Hash::make($request->password);
         $student->phone = isset($request->phone) ? $request->phone : "";

         $student->save();

         return response()->json([
            'status' => 1,
            'message' => 'student registered successfully'
         ]);
    }

    // LOGIN API
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $student = Student::where('email', '=', $request->email)->first();

        if(isset($student->id)){

            if(Hash::check($request->password, $student->password)){

                $token = $student->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => 1,
                    'message' => 'Student logged successfully',
                    'token' => $token
                ]);

            }else{
                return response()->json([
                    'status' => 0,
                    'message' => 'password not match'
                ], 404);
            }

        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Student not found'
            ], 404);
        }
    }

    /**
     * need to authenticate
     */

    // PROFILE API
    public function profile()
    {
        return response()->json([
            'status' => 1,
            'message' => 'Student profile information',
            'data' => auth()->user()
        ]);
    }

    // LOGOUT API
    public function logout()
    {
         auth()->user()->tokens()->delete();
         return response()->json([
            'status' => 1,
            'message' => 'Student logout successfullay',
        ]);
    }
}
