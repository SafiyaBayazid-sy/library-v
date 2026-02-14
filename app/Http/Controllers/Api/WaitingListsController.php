<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WaitingListsRequest;
use App\Http\Resources\WaitingRequestResource;
use App\ResponseHelper;
use App\Models\Customer;
use App\Models\WaitingList;


class WaitingListsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $waitingList=WaitingList::with(['book','customer'])->get();
    return ResponseHelper::success('تم جلب جميع الكتب المطلوبة',
    WaitingRequestResource::collection($waitingList)
    );
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(WaitingListsRequest $request)
    {


        $waitingList=WaitingList::create([
            'customer_id'=>$request->customer_id,
            'book_id'=> $request->book_id
        ]);


        return ResponseHelper::success('تم طلب الكتاب بنجاح',$waitingList);
    }

    /**
     * Display the specified resource.
     */
    public function show(WaitingList $waitingList)
    {
        return ResponseHelper::success('تم جلب بيانات الكتاب المطلوب',new WaitingRequestResource($waitingList    ->load(['book','customer'])
));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(WaitingListsRequest $request, WaitingList $waitingList)
    {

       $waitingList->update($request->all());
        return ResponseHelper::success('تم تحديث بيانات الكتاب بنجاح',new WaitingRequestResource($waitingList    ->load(['book','customer'])
));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaitingList $waitingList)
    {
        $waitingList->delete();
        return ResponseHelper::success('تم حذف طلب الكتاب بنجاح',null);

    }

    public function getCustomerRequests(Customer $customer){
        $requests_books=WaitingList::where('customer_id',$customer->id)->get();
        return ResponseHelper::success('تم جلب جميع الكتب المطلوبة للعميل',WaitingRequestResource::collection($requests_books    ->load(['book','customer'])
));
    }
}
