<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'customer_id' => $this->id,
            'user_id' => $this->user_id,
            'full_name' => $this->user->name ?? null,
            'email' => $this->user->email ?? null,
            'phone' => $this->phone,
            'gender'=>$this->gender,
            'DOB'=>$this->DOB,
            'avatar' => $this->avatar ? url('storage/' . $this->avatar) : null,
        ];
    }
}
