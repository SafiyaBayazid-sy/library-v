<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
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
    $book = $this->route('book'); // Book model

    return [
        'ISBN' => ['required', 'digits:13', Rule::unique('books', 'ISBN')->ignore($book?->id)],
        'title' => 'required|string|max:70',
        'price' => 'required|decimal:0,2|min:0|max:99.99',
        'mortgage' => 'required|decimal:0,2|min:0|max:9999.99',
        'authorship_date' => 'nullable|date',
        'cover' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048', // 2MB

        'pages' => 'required|integer|min:1|max:10000',
        'borrow_duration' => 'required|integer|min:1|max:365',
        'total_copies' => 'required|integer|min:0|max:99999',
        'remaining_copies' => 'required|integer|min:0|max:99999',

        'category_id' => 'required|exists:categories,id',
        'authors' => 'nullable|array',
        'authors.*' => 'exists:authors,id',
    ];
}
}
