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

Route::controller(AuthorController::class)->prefix('authors')->group(function ()
{
    Route::get('', 'index')->name('authors.index');
    Route::get('create', 'create')->name('authors.create');
    Route::post('', 'store')->name('authors.store');
    Route::get('{author}', 'show')->name('authors.show');
    Route::get('{author}/edit', 'edit')->name('authors.edit');
    Route::match(['get', 'post'], '{author}', 'update')->name('authors.update');
    Route::delete('', 'destroy')->name('authors.destroy');
});
