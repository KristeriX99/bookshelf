<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use DB;

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

    public function getByIDExt($id)
    {
        return $this->where('id', $id)
                    ->withCount('sales')
                    ->withMonthlySales()
                    ->first(); 
    }

    public function scopeWithMonthlySales(Builder $query)
    {
        return $query->withCount([
            'sales as monthly_sales' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        ]);
    }

    public function getPaginated($query = null)
    {
        $books = $this->with('authors')
                    ->withCount('sales')
                    ->withMonthlySales();
        
        if ($query) {
            $books->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhereHas('authors', function($authorQuery) use ($query) {
                    // allows to search by firstname and name or by either
                    $authorQuery->whereRaw("first_name || ' ' || name LIKE ?", ["%{$query}%"]);
                                    // ->where('first_name', 'LIKE', "%{$query}%")
                                    // ->orWhere('name', 'LIKE', "%{$query}%");
                  });
            });
        }
        
        return $books->orderBy('monthly_sales', 'desc')
                    ->paginate($this->listingsInPage)
                    ->withQueryString();
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_book', 'book_id', 'author_id');
    }

    public function sales()
    {
        return $this->hasMany(BookSales::class, 'book_id', 'id');
    }

    public function getTopMonthlySales()
    {
        return $this->with('authors')
                    ->withCount('sales')
                    ->withMonthlySales()
                    ->orderBy('monthly_sales', 'desc')
                    ->limit(10)
                    ->get();
    }
}
