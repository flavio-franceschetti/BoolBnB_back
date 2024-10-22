<?php

use App\Http\Controllers\Api\PageController;
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

Route::get('/apartments', [PageController::class, 'index']);
Route::get('/apartmentById/{id}', [PageController::class, 'apartmentById']);
Route::get('/apartmentBySlug/{slug}', [PageController::class, 'apartmentBySlug']);
Route::get('/apartmentsByAddress/{address}', [PageController::class, 'apartmentsByAddress']);
Route::get('/apartmentsWithSponsorship', [PageController::class, 'apartmentsWithSponsorship']);
