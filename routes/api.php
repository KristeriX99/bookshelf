<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// unauthenticated route!
Route::get('montly-books', [BookController::class, 'getMontlyBooksApi'])->name('get_monthly_books');
