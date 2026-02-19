<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BooksRequest;
use App\Http\Resources\BookRequestResource;
use App\Models\Book;
use App\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\BookRequest;
use App\Models\Customer;

class BookRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $book_requests = BookRequest::with('customer')->get();
        return ResponseHelper::success(
            'تم جلب جميع الكتب المطلوبة',
            BookRequestResource::collection($book_requests)
        );
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(BooksRequest $request)
    {


        $bookRequest = BookRequest::create([
            'book_title' => $request->book_title,
            'customer_id' => $request->customer_id,
            'book_id' => $request->book_id,
            'author_name' => $request->author_name,
            'admin_note' => $request->admin_note,
            //status by default is new

        ]);


        return ResponseHelper::success('تم طلب الكتاب بنجاح', $bookRequest);
    }

    /**
     * Display the specified resource.
     */
    public function show(BooksRequest $request, BookRequest $bookRequest)
    {
        return ResponseHelper::success('تم جلب بيانات الكتاب المطلوب', new BookRequestResource(
            $bookRequest->load('customer')
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BooksRequest $request, BookRequest $bookRequest)
    {


        $request['book_id'] = $request->book_id ?? null;

        $bookRequest->update($request->all());
        return ResponseHelper::success('تم تحديث بيانات طلب الكتاب بنجاح', new BookRequestResource($bookRequest->load('customer')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BooksRequest $request, BookRequest $bookRequest)
    {
        $bookRequest->delete();
        return ResponseHelper::success('تم حذف طلب الكتاب بنجاح', null);
    }

    public function getCustomerRequests(BooksRequest $request, Customer $customer)
    {
        $requests_books = BookRequest::where('customer_id', $customer->id)->get();
        return ResponseHelper::success('تم جلب جميع الكتب المطلوبة للعميل', BookRequestResource::collection($requests_books->load('customer')));
    }
}
