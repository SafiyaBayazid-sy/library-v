<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WaitingListsRequest extends FormRequest
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

            // For POST requests (creating new request)
            if ($this->isMethod('post')) {

                // Check if customer_id in request matches user's customer_id
                $customerId = $this->input('customer_id');

                if ($customerId != $user->customer->id) {
                    return false;
                }

                return true;
            }

            // For delete requests 
            if ($this->isMethod('delete')) {
                // Get the waitingList id from route 
                $waitingList = $this->route('waiting_list');

                $data = DB::table('waiting_lists')->where('id', $waitingList)->first();
                $customer = $data->customer_id;



                if ($waitingList && $customer && $customer != $user->customer->id) {
                    return false;
                }


                return true;
            }
        }

        return true;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [

            'customer_id' => [$this->isMethod('post') ? 'required' : 'sometimes', 'exists:customers,id'],
            'book_id' => [$this->isMethod('post') ? 'required' : 'sometimes', 'exists:books,id']
        ];
    }
}
