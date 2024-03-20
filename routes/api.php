<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/user/enter', [App\Http\Controllers\UserController::class, 'store']);

Route::post('/enter/validate', [\App\Http\Controllers\UserController::class, 'authenticate']);

Route::get('/data', [\App\Http\Controllers\UserController::class, 'user_data']);

Route::post('/note/register', [\App\Http\Controllers\NoteController::class, 'store']);

Route::get('/note/{id_user}', [\App\Http\Controllers\NoteController::class, 'show']);