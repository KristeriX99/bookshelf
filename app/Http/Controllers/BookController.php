<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\SaveBookRequest;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookModel = new Book();

        return view('book.index')->with([
            'books' => $bookModel->getPaginated()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('book.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveBookRequest $request)
    {
        $fields = $request->validated();

        if ($request->hasFile('image'))
        {
            $fields['image_path'] = Storage::disk('public')->put('images', $request->image);
        }

        $bookModel = new Book();
        $bookModel->createBook($fields);
       
        return redirect()->route('books.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
