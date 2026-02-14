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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

  $book_requests=BookCustomer::with(['book','customer'])->get();
    return ResponseHelper::success('تم جلب جميع تقييمات الكتب المطلوبة',RateResource::collection($book_requests));
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RateRequest $request)
    {


       $bookCustomer= BookCustomer::create($request->all());


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
        new RateResource($bookCustomer  ->load(['book','customer'])
)
        );
    }

    public function update(RateRequest $request)
{
    try {
        $affected = DB::table('book_customer')->updateOrInsert(
            ['customer_id' => $request->customer_id, 'book_id' => $request->book_id],
            ['rate' => $request->rate, 'updated_at' => now()]
        );

        $bookCustomer = BookCustomer::where('customer_id', $request->customer_id)
            ->where('book_id', $request->book_id)
            ->first();

        return ResponseHelper::success(
            $affected ? 'تم تحديث التقييم بنجاح' : 'تم اضافة التقييم بنجاح',
            new RateResource($bookCustomer    ->load(['book','customer'])
)
        );

    } catch (\Exception $e) {
        return ResponseHelper::failed('حدث خطأ: ' . $e->getMessage(), 500);
    }
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
        return ResponseHelper::success('تم جلب جميع تقييم الكتب المطلوبة للعميل',RateResource::collection($requests_books    ->load(['book','customer'])
));
    }
}
