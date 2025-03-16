<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookController,
    App\Http\Controllers\AuthorController;

Route::get('/', function () {
    return redirect('/books');
});

Route::controller(BookController::class)->prefix('books')->group(function ()
{
    Route::get('', 'index')->name('books.index');
    Route::get('create', 'create')->name('books.create');
    Route::post('', 'store')->name('books.store');
    Route::get('{book}', 'show')->name('books.show');
    Route::put('{book}', 'update')->name('books.update');
    Route::get('{book}/edit', 'edit')->name('books.edit');
    Route::post('{book}/buy', 'buy')->name('books.buy');
});
