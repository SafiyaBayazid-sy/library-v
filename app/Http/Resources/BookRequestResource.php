<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookRequestResource extends JsonResource
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
            "book_title" => $this->book_title ,
            "customer_id" => $this->customer_id ,
            "author_name"=>$this->author_name,
            "admin_note"=>$this->admin_note,
            "status"=>$this->status,
            'customer'=>new CustomerResource($this->whenLoaded('customer'))


        ];
    }
}
