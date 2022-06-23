<?php

use App\Models\Authors;
use App\Models\Journal;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/authors', function () {
    $authors = Authors::orderBy('last_name', 'asc')->paginate(5);
    return view('authors', compact('authors'));
});
Route::get('/journals', function () {
    $journals = Journal::orderBy('release_date', 'desc')->paginate(5);
    return view('journals', compact('journals'));
});
