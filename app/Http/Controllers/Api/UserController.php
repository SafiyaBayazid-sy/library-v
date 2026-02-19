<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\User;
use App\ResponseHelper;
use DragonCode\Support\Facades\Helpers\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
   
public function index(){
$user=Customer::all();
return ResponseHelper::success('جميع المستخدمين',CustomerResource::collection($user));

}

public function show(Customer $customer){
    $customer->load(['user' => function ($query) {
        $query->without('customer');
    }]);
    return  ResponseHelper::success('بيانات المستخدم',new CustomerResource($customer));
}


}
