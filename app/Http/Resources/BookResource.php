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
            "price" => $this->price ,
            "mortgage" => $this->mortgage ,
            "cover" =>  asset('storage/book-images/' . ($this->cover ?? 'no-image.jpeg')),

             "category" => [
                "name" => $this->category->name ?? null
            ],
            "authors" => $this->authors->map(function($author) {
                return [
                    "id" => $author->id,
                    "name" => $author->name
                ];
            }),

                          "avg_rating" =>round( $this->avg_rating) ?? 0,


        //    'category' => new CategoryResource($this->whenLoaded('category')),

        //     'authors'  => AuthorResource::collection(
        //         $this->whenLoaded('authors')
        //     ),
              "avg_rating" =>round( $this->avg_rating) ?? 0,



        ];
    }
}
