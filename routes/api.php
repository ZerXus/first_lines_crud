<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\JournalController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('journal', JournalController::class);

Route::apiResource('author', AuthorsController::class);
Route::get('author/{author}/journals', [AuthorsController::class, 'getJournals']);
