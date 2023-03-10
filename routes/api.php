<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// user controller ---old
// Route::get('user', [UserController::class,'index']);
// Route::post('user', [UserController::class,'store']);
// Route::get('user/{id?}', [UserController::class,'show']);
// Route::put('user/{user}', [UserController::class,'update']);
// Route::post('user/{post}', [UserController::class,'destroy']);
// ------------------------------
//API route for register new user
Route::post('/register', [AuthController::class, 'register']);
Route::post('/update/{id}', [AuthController::class, 'update']);
//API route for login user
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function ()
{
    Route::get('/profile', function(Request $request)
    {
        return auth()->user();
    });
     // API route for logout user
     Route::post('/logout', [AuthController::class, 'logout']);
});


//
// Route::resource('user', UserController::class);
// Route::post('user/{user}', [UserController::class,'update']);
// Route::post('user/{user}', [UserController::class,'destroy']);