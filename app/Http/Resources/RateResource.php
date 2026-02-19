<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            "book_id" => $this->book_id ,
            "customer_id" => $this->customer_id ,
            "rate" => $this->rate ,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            "ISBN"=>$this->ISBN,
            "title"=>$this->title,
            "customer_name"=>$this->name




        ];
    }
}
