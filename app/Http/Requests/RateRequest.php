<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

public function rules(): array
{
    $isDelete = $this->isMethod('delete');

    $rules = [
        'customer_id' => 'required|exists:customers,id',
        'book_id' => 'required|exists:books,id'
    ];

    // For DELETE method: just check if record exists (no rate needed)
    if ($isDelete) {
        $rules['rate'] = 'nullable|sometimes';

        // Add validation to ensure the rating exists before deleting
        $rules['customer_id'] = [
            'required',
            'exists:customers,id',
            Rule::exists('book_customer')->where(function ($query) {
                $query->where('book_id', $this->book_id)
                      ->whereNotNull('rate'); // Ensure there's a rate to delete
            })
        ];

        return $rules;
    }

    // For POST/PUT: require rate and check uniqueness
    $rules['rate'] = 'required|integer|min:1|max:5';

    // For POST: ensure unique combination
    if ($this->isMethod('post')) {
        $rules['customer_id'] = [
            'required',
            'exists:customers,id',
            Rule::unique('book_customer')->where(function ($query) {
                $query->where('book_id', $this->book_id);
            })
        ];
    }
    // For PUT: ensure record exists to update
    else if ($this->isMethod('put') || $this->isMethod('patch')) {
        $rules['customer_id'] = [
            'required',
            'exists:customers,id',
            Rule::exists('book_customer')->where(function ($query) {
                $query->where('book_id', $this->book_id)
                      ->whereNotNull('rate'); // Ensure there's a rate to update
            })
        ];
    }

    return $rules;
}


}
