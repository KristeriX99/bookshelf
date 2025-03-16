<?php

namespace App\Http\Controllers;

use App\Models\Book,
    App\Models\Author;

use App\Http\Requests\SaveBookRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Http\Resources\BookResource;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bookModel = new Book();
        $query = $request->input('search');

        return view('book.index')->with([
            'books' => $bookModel->getPaginated($query),
            'search' => $query
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
        $book->load('authors')
            ->load('sales');

        return view('book.create')->with([
            'authors' => Author::all(),
            'book' => $book
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveBookRequest $request, Book $book)
    {
        $fields = $request->validated();

        if ($request->hasFile('image'))
        {
            $path = Storage::disk('public')->put('images', $request->image);
            $fields['image_path'] = Storage::url($path);
        }

        $book->updateBook($fields);
        
        $book->authors()->sync($fields['authors'] ?? []);
        
        return redirect()->route('books.index');
    }

    public function buy(Book $book)
    {
        $book->sales()->create();
        
        // Get updated counts
        $book = $book->getByIDExt($book->id);

        return response()->json([
            'sales_count' => $book->sales_count,
            'monthly_sales' => $book->monthly_sales
        ]);
    }

    public function getMontlyBooksApi()
    {
        $bookModel = new Book();
        $books = $bookModel->getTopMonthlySales();

        return BookResource::collection($books);
    }
}
