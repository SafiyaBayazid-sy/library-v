<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WaitingListsRequest;
use App\Http\Resources\WaitingRequestResource;
use App\Models\Customer;
use App\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WaitingListsController extends Controller
{
    private function waitingListQuery()
    {
        return DB::table('waiting_lists')
            ->join('books', 'waiting_lists.book_id', '=', 'books.id')
            ->join('customers', 'waiting_lists.customer_id', '=', 'customers.id')
            ->join('users', 'customers.user_id', '=', 'users.id');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $waitingList = $this->waitingListQuery()->get();

        return ResponseHelper::success(
            'تم جلب جميع الكتب المطلوبة',
            WaitingRequestResource::collection($waitingList)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WaitingListsRequest $request)
    {
        // DB facade
        $id = DB::table('waiting_lists')->insertGetId([
            'customer_id' => $request->customer_id,
            'book_id' => $request->book_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $waitingList = DB::table('waiting_lists')->find($id);

        return ResponseHelper::success('تم طلب الكتاب بنجاح', $waitingList);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        

        $waitingList = $this->waitingListQuery()->where('waiting_lists.id', $id)->first();

         $permissionCheck = $this->getPermission($waitingList->customer_id);
    if ($permissionCheck) {
        return $permissionCheck; // This will return the failed response
    }
        

        if (! $waitingList) {
            return ResponseHelper::failed('الطلب غير موجود', null, 404);
        }

        return ResponseHelper::success(
            'تم جلب بيانات الكتاب المطلوب',
            new WaitingRequestResource($waitingList)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WaitingListsRequest $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaitingListsRequest $request, $id)
    {
        $deleted = DB::table('waiting_lists')->where('id', $id)->delete();

        if (! $deleted) {
            return ResponseHelper::failed('الطلب غير موجود', null, 404);
        }

        return ResponseHelper::success('تم حذف طلب الكتاب بنجاح', null);
    }

    /**
     * Get customer requests
     */
    public function getCustomerRequests(WaitingListsRequest $request, Customer $customer)
    {

         $permissionCheck = $this->getPermission($customer->id);
    if ($permissionCheck) {
        return $permissionCheck; // This will return the failed response
    }

        $requests_books = $this->waitingListQuery()
            ->where('customer_id', $customer->id)
            ->get();

        return ResponseHelper::success(
            'تم جلب جميع الكتب المطلوبة للعميل',
            WaitingRequestResource::collection($requests_books)
        );
    }

    public function getPermission($id){
         if ($id != Auth::user()->customer->id) {
            return ResponseHelper::failed('Access denied. Insufficient permissions.');
        }
        
    }
}
