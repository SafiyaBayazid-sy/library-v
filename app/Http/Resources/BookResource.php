<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            "id" => $this->id ,
            "ISBN" => $this->ISBN ,
            "title" => $this->title ,
            "category_name" => $this->whenLoaded('category', $this->category?->name),
            'createdAt' => $this->created_at->format('Y-m-d'),
            "price" => $this->price ,
            "mortgage" => $this->mortgage ,
            "authorship_date"=>$this->authorship_date,
            "pages"=>$this->pages,
            "total_copies"=>$this->total_copies,
            "remaining_copies"=>$this->remaining_copies,
            "borrow_duration"=>$this->borrow_duration,
            "status"=> $this->remaining_copies > 0 ? "available" : "borrowed",
            "cover" =>asset('storage/'. ($this->cover !=null ? 'book-images/'.$this->cover:  'no-image.png')),
            "avg_rating" => $this->avg_rating ?? 0,
            'category' => new CategoryResource($this->whenLoaded('category')),
            "authors" => $this->whenLoaded('authors', function() {
             return $this->authors->map(function($author) {
                return [
                    "id" => $author->id,
                    "name" => $author->name
                ];});
            }),




        ];
    }
}
