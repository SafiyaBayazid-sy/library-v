<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BooksRequest extends FormRequest
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
    $isCreate = request()->isMethod('post');

    return [
        'book_title' => [$isCreate ? 'required' : 'sometimes', 'string', 'max:70'],
        'customer_id' => [$isCreate ? 'required' : 'sometimes', 'exists:customers,id'],
        'author_name' => [$isCreate ? 'required' : 'sometimes', 'string'],
        'admin_note' => [$isCreate ? 'required' : 'sometimes', 'string'],
        'status' => ['sometimes', 'in:new,read,processed,rejected'],
    ];
}


}
