<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Http\Resources\RateResource;
use App\Models\BookCustomer;
use App\Models\Customer;
use App\ResponseHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

  $book_requests=BookCustomer::all();
    return ResponseHelper::success('تم جلب جميع تقييمات الكتب المطلوبة',RateResource::collection($book_requests));
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RateRequest $request)
    {
        $bookCustomer=BookCustomer::updateOrCreate(
            [
                'customer_id'=>$request->customer_id,
                'book_id'=>$request->book_id
            ],
            [
                'rate'=>$request->rate,
            ]
        );


        return ResponseHelper::success('تم تقييم الكتاب بنجاح',new RateResource($bookCustomer));    }

    /**
     * Display the specified resource.
     */
        public function show(Request $request)

    {
    $bookCustomer=BookCustomer::
    where('book_id',$request->book_id)
    ->where('customer_id',$request->customer_id)
    ->firstOrFail();


        return ResponseHelper::success('تم جلب بيانات تقييم الكتاب المطلوب',
        new RateResource($bookCustomer)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RateRequest $request)
{
    $bookCustomer = BookCustomer::where('book_id', $request->book_id)
        ->where('customer_id', $request->customer_id)
        ->firstOrFail();

    if (!$bookCustomer) {
        return ResponseHelper::failed('لم يتم العثور على تقييم لهذا الكتاب من قبل هذا العميل', null, 404);
    }

    $bookCustomer->update($request->only('rate'));

    return ResponseHelper::success('تم تحديث بيانات تقييم الكتاب بنجاح', new RateResource($bookCustomer));
}
    public function update2(RateRequest $request)
    {

    $bookCustomer=BookCustomer::where('book_id',$request->book_id)
    ->where('customer_id',$request->customer_id)->firstOrFail();

        $bookCustomer->update($request->all());
        return ResponseHelper::success('تم تحديث بيانات تقييم الكتاب بنجاح',new RateResource($bookCustomer));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request){

    }

    public function destroyRate(RateRequest $request)
{
    try {

        $deletedCount = BookCustomer::where('customer_id', $request->customer_id)
            ->where('book_id', $request->book_id)
            ->delete();

        return ResponseHelper::success('تم حذف التقييم بنجاح');

    } catch (\Exception $e) {

        return ResponseHelper::failed('حدث خطأ أثناء حذف التقييم', 500);
    }
}



    public function getCustomerRate(Customer $customer){
        $requests_books=BookCustomer::where('customer_id',$customer->id)->get();
        return ResponseHelper::success('تم جلب جميع تقييم الكتب المطلوبة للعميل',RateResource::collection($requests_books));
    }
}
