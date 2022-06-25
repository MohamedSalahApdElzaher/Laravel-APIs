<?php

namespace App\Http\Controllers\PASSPORT;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    // REGISTER - POST
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:authors',
            'phone_no' => 'required',
            'password' => 'required|confirmed'
        ]);

        $author = new Author();

        $author->name = $request->name;
        $author->email = $request->email;
        $author->phone_no = $request->phone_no;
        $author->password = bcrypt($request->password);

        $author->save();

        return response(
            [
                'status' => 1,
                'message' => 'author created',
                'author' => $author,
            ]
        );
    }

    // LOGIN - POST
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken');

        return response(
            [
                'status' => 1,
                'message'=> 'logged in',
                'author' => auth()->user(),
                'access_token' => $accessToken
            ]
        );
    }

    // PROFILE - GET
    public function profile()
    {
        $author_data = auth()->user();

        return response()->json([
            "status" => 1,
            "message" => "Author data",
            "data" => $author_data
        ]);
    }

    // LOGOUT - POST
    public function logout(Request $request)
    {
        // get token value
        $token = $request->user()->token();

        // revoke this token value
        $token->revoke();

        return response()->json([
            "status" => 1,
            "message" => "Author logged out successfully"
        ]);
    }

}
