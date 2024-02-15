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

Route::post('/user/register', [App\Http\Controllers\UserController::class, 'store']);

Route::post('/login', [\App\Http\Controllers\UserController::class, 'authenticate']);

Route::post('/guitar/register', [\App\Http\Controllers\GuitarController::class, 'store']);

Route::get('/guitar/{id_user}', [\App\Http\Controllers\GuitarController::class, 'show']);