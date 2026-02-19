<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\User;
use App\ResponseHelper;
use DragonCode\Support\Facades\Helpers\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{



public function me()
{
    if (Auth::check()) {
        $user = Auth::user();
        return response()->json($user);
    }

    return response()->json(['error' => 'Not authenticated'], 401);
}



function register(Request $request)
{
    // Common validation rules for all users
    $commonRules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users'],
        'password' => ['required', 'confirmed', 'min:8'],
        'type' => ['required', 'in:admin,customer'],
    ];

    // Additional validation rules for customers
    $customerRules = [
        'gender' => ['required_if:type,customer', 'in:M,F'],
        'phone' => ['required', 'string', 'max:10','unique:customers'],
        'DOB' => ['required', 'date', 'before:today'],
        'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    ];

    // Merge rules based on user type
    $rules = $request->type == 'admin' ? $commonRules : array_merge($commonRules, $customerRules);

    // Execute validation
    $validatedData = $request->validate($rules);

    // Create the user
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => Str::lower($validatedData['email']),
        'password' => $validatedData['password'],
        'type' => $validatedData['type'],
    ]);

    // If customer, create customer profile
    if ($request->type == 'customer') {
        $user->customer()->create([
            'gender' => $validatedData['gender'],
            'phone' => $validatedData['phone'],
            'DOB' => $validatedData['DOB'],
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $unique = uniqid();
            $filename = time() . '_' . $unique . '.' .$file->extension();

            $path = $file->storeAs('customers-avatar', $filename); // 'public' disk

            $user->customer()->update(['avatar' => $path]);
        }
    }

    $remember = $request->boolean('remember');
    Auth::login($user, $remember);
    $request->session()->regenerate();

    return ResponseHelper::success("تم تسجيل الحساب بنجاح");
}


    function login(Request $request){
          $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);
                $remember = $request->boolean('remember');

        if ( ! Auth::attempt($credentials ,  $remember  ))
            throw ValidationException::withMessages(['email' => 'معلومات التوثق غير صحيحة']);

        $request->session()->regenerate();

    $request->session()->save();

        return ResponseHelper::success("تم تسجيل الدخول بنجاح",[ 'user' => new AuthResource(Auth::user())]);

    }
    function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return ResponseHelper::success("تم تسجيل الخروج بنجاح");

    }


      public function updateCustomer(UpdateCustomerRequest $request,Customer $customer){
        $customer->update($request->all());

        $customer->user()->update([
            'name' => $request->name ?? $customer->user->name,
            'email' => $request->email ?? $customer->user->email,
        ]);

         if ($request->hasFile('avatar')) {
        // Delete old avatar
        if ($customer->avatar) {
            Storage::delete('customers-avatar/' . $customer->avatar);
        }

        // Save new avatar
         $file = $request->file('avatar');

           $unique = uniqid();
            $filename = time() . '_' . $unique . '.' .$file->extension();

         Storage::putFileAs('customers-avatar', $file ,$filename );
         $customer->avatar = $filename;
         $customer->save();
    }

        $customer->load('user');
        return ResponseHelper::success('تم تحديث البيانات',new AuthResource($customer->user));

    }



     public function update(Request $request,User $user){

     if($user->type == 'customer')
        return ResponseHelper::failed('Unauthenticated');


        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
        ]);

        return ResponseHelper::success('تم تحديث البيانات',new AuthResource($user));

    }
}
