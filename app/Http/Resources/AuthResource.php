<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
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
            "name"=>$this->name,
            "email"=>$this->email,
            "type"=>$this->type,


        //    'customer' => new CustomerResource($this->whenLoaded('customer')),

             // Include customer data only if loaded and user has customer
            "customer" => $this->whenLoaded('customer', function () {
                return $this->customer ? [
                    'avatar' => asset('storage/'. ($this->customer->avatar !=null ? 'customers-avatar/'.$this->customer->avatar:  'no-image.png')),
                    'phone'=>$this->customer->phone,
                    'gender'=>$this->customer->gender,
                    'DOB'=>$this->customer->DOB,
                    'id'=>$this->customer->id


                ] : null;
            }),



            ];
    }
}
