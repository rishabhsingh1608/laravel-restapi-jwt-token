<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//open routes
Route::post("register", [ApiController::class, "register"]);
Route::post("login", [ApiController::class, "login"]);


//protected route

Route::group([
  "middleware" => ["auth:api"]
], function () {
  Route::get("profile", [ApiController::class, "profile"]);
  Route::get("refresh", [ApiController::class, "refreshToken"]);
  Route::get("logout", [ApiController::class, "logout"]);
});
