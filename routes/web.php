<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeTravelController;

Route::get('/', [HomeTravelController::class, 'index'])->name('home');
Route::get('/searchstops', [HomeTravelController::class, 'searchStops'])->name('searchstops');
Route::get('/seatsearch', [HomeTravelController::class, 'seatSearch'])->name('seatsearch');
Route::get('/search', [HomeTravelController::class, 'search'])->name('search');

// Route::get('/', function () {
//     return 'Hello World';
// });