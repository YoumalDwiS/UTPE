<?php

use App\Http\Controllers\Cms\PBEngine\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//API route for register new user
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
//API route for login user
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

Route::get('/get-memo-list', [APIController::class, 'getMemoList']);
Route::get('/get-memo-detail/{id}', [APIController::class, 'getMemoDetail']);
Route::post('/add-notification', [APIController::class, 'addNotification']);
