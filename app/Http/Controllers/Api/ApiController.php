<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    //Register API- POST(name,email,password)
    public function register(Request $request)
    {

        // validation
        $request->validate([
            "name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|confirmed",
        ]);

        // USER model to save user in db
        User::create([
            //db column name
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
        ]);

        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "data" => []
        ]);
    }


    //Login API- POST(email,password)
    public function login(Request $request)
    {
        //validation
        $request->validate([

            "email" => "required|string|email",
            "password" => "required",
        ]);

        // Auth facade
        $token = Auth::attempt([
            "email" => $request->email,
            "password" => $request->password,

        ]);

        //using auth method
        // $token = auth()->attempt([
        //     "email" => $request->email,
        //     "password" => $request->password,

        // ]);


        if (!$token) {
            return response()->json([
                "status" => false,
                "message" => "Invalid login details",
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "User logged in",
            "token" => $token
        ]);
    }


    //Profile API-GET(JWT auth token)
    public function profile()
    {

        $userData = auth()->user();
        // OR
        // $userData = request()->user();


        return response()->json([
            "status" => true,
            "message" => "Profile Data",
            "user" => $userData,
            "user_id" => request()->user()->id,
            "email" => request()->user()->email,
            "exprires_in" => auth()->factory()->getTTL() * 60


        ]);
    }

    //Refresh Token API- GET(JWT Auth token)
    public function refreshToken()
    {

        $token = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "Refresh token",
            "token" => $token,
            "exprires_in" => auth()->factory()->getTTL() * 60
        ]);
    }


    //Logout API - GET(JWT Auth token)
    public function logout()
    {

        auth()->logout();
        return response()->json([
            "status" => true,
            "message" => "User loged out"
        ]);
    }
}
