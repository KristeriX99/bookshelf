<?php

namespace App\Http\Controllers;

use App\Models\Book,
    App\Models\Author;

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
        return view('book.create')->with([
            'authors' => Author::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveBookRequest $request)
    {
        $fields = $request->validated();

        if ($request->hasFile('image'))
        {
            $path = Storage::disk('public')->put('images', $request->image);
            $fields['image_path'] = Storage::url($path);
        }

        $bookModel = new Book();
        $book = $bookModel->createBook($fields);
        
        if (!empty($fields['authors']))
        {
            $book->authors()->attach($fields['authors']);
        }
       
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
