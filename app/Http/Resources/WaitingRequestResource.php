<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WaitingRequestResource extends JsonResource
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
            "book_id" => $this->book_id ,
            "customer_id" => $this->customer_id ,
            "created_at"=>$this->created_at,
            "ISBN"=>$this->ISBN,
            "title"=>$this->title,
            "customer_name"=>$this->name

        ];
    }
}
