<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'authors' => $this->authors->select(['first_name', 'name']),
            'cover_path' => $this->image_path ? config('app.url') . Storage::url($this->image_path) : null,
            'total_sales' => $this->sales_count,
            'monthly_sales' => $this->monthly_sales,
            'created_at' => $this->created_at
        ];
    }
}
