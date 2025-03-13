<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'description',
        'published',
        'image_path'
    ];

    private $listingsInPage = 20;
    
    public function createBook($param)
    {
        return $this->create($param);
    }

    public function updateBook($param)
    {
        return $this->update($param);
    }

    public function deleteBook()
    {
        return $this->delete();
    }

    public function getPaginated()
    {
        return $this->latest()
                    ->paginate($this->listingsInPage);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_book', 'book_id', 'author_id');
    }
}
