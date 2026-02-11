<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $customer = $this->route('customer');

        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|'.Rule::unique('users' , 'email')->ignore($customer?->user->id),
            'password' => 'sometimes|string|min:8',
            'gender' => 'sometimes|in:F,M',
            'DOB' => 'sometimes|date|before:today',
            'phone' => "sometimes|string|digits:10|unique:customers,phone," .$this->user()->customer->id,
            'avatar' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
