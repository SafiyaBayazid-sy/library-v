<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BooksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        // Admin can do anything
        if ($user->type === 'admin') {
            return true;
        }

        // Customer type user
        if ($user->type === 'customer') {

            if ($this->isMethod('get')) {
                $bookRequest = $this->route('book_request');
                $customer = $this->route('customer');

                if ($customer && $customer->id != Auth::user()->customer->id) {
                    return false;
                }

                if ($bookRequest && $bookRequest->customer?->id != $user->customer->id) {
                    return false;
                }

                return true;
            }
            // For POST requests (creating new request)
            if ($this->isMethod('post')) {
                // Check if customer_id in request matches user's customer_id
                $customerId = $this->input('customer_id');

                if ($customerId != $user->customer->id) {
                    return false;
                }

                return true;
            }

            // For PUT/PATCH requests (updating)
            if ($this->isMethod('put') || $this->isMethod('patch') || $this->isMethod('delete')) {
                // Get the BookRequest from route
                $bookRequest = $this->route('book_request');


                // Check if the book request belongs to the user's customer
                if (!$bookRequest && $bookRequest?->customer || $bookRequest->customer->id != $user->customer->id) {
                    return false;
                }

                return true;
            }
        }

        return false;
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
            'admin_note' => ['sometimes', 'string'],
            'status' => ['sometimes', 'in:new,read,processed,rejected'],
        ];
    }
}
