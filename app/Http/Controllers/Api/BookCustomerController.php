<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\RateResource;
use App\Models\Customer;
use App\Models\Book;
use App\ResponseHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookCustomerController extends Controller
{
    public function index()
    {

        $ratings = $this->ratingsQuery()
            ->get();


        return ResponseHelper::success(
            'تم جلب جميع تقييمات الكتب المطلوبة',
            // $ratings
            RateResource::collection($ratings)
            // CustomerResource::collection($ratings)
        );
    }

    public function store(RateRequest $request)
    {
        $customer = Customer::with('user')->findOrFail($request->customer_id);

        // Attach with rating using the relationship
        $customer->ratedBooks()->attach($request->book_id, [
            'rate' => $request->rate,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Get the created rating with relationships

        $rating = $this->ratingsQuery()
            ->where('book_customer.book_id', $request->book_id)
            ->where('book_customer.customer_id', $request->customer_id)
            ->first();

        return ResponseHelper::success(
            'تم تقييم الكتاب بنجاح',
            //    $rating
            new RateResource($rating)
        );
    }

    private function ratingsQuery()
    {
        return DB::table('book_customer')
            ->join('books', 'book_customer.book_id', '=', 'books.id')
            ->join('customers', 'book_customer.customer_id', '=', 'customers.id')
            ->join('users', 'customers.user_id', '=', 'users.id');
    }



    public function show(RateRequest $request)
    {
        $rating = $this->ratingsQuery()
            ->where('book_customer.book_id', $request->book_id)
            ->where('book_customer.customer_id', $request->customer_id)
            ->firstOrFail();

        return ResponseHelper::success(
            'تم جلب بيانات تقييم الكتاب المطلوب',
            new RateResource($rating)
        );
    }

    public function update(RateRequest $request)
    {
        try {
            $customer = Customer::findOrFail($request->customer_id);

            // Update the pivot record
            $customer->ratedBooks()->updateExistingPivot($request->book_id, [
                'rate' => $request->rate,
                'updated_at' => now()
            ]);


            $rating = $this->ratingsQuery()
                ->where('book_customer.book_id', $request->book_id)
                ->where('book_customer.customer_id', $request->customer_id)
                ->first();


            return ResponseHelper::success(
                'تم تحديث التقييم بنجاح',
                new RateResource($rating)
            );
        } catch (\Exception $e) {
            return ResponseHelper::failed('حدث خطأ: ' . $e->getMessage(), 500);
        }
    }

    public function destroyRate(RateRequest $request)
    {
        try {
            $customer = Customer::findOrFail($request->customer_id);

            // Detach the rating
            $customer->ratedBooks()->detach($request->book_id);

            return ResponseHelper::success('تم حذف التقييم بنجاح');
        } catch (\Exception $e) {
            return ResponseHelper::failed('حدث خطأ أثناء حذف التقييم', 500);
        }
    }

    public function getCustomerRate(Customer $customer)
    {

        $ratings = $this->ratingsQuery()
            ->where('book_customer.customer_id', $customer->id)
            ->get();
        return ResponseHelper::success(
            'تم جلب جميع تقييم الكتب المطلوبة للعميل',
            RateResource::collection($ratings)
        );
    }
}
