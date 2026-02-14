<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Resources\BookListResource;
use App\Models\Book;
use App\Http\Resources\BookResource;
use App\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->title;
        $category_name=$request->category_name;
        $author_name=$request->author_name;

        $books = Book::with(['category', 'authors'])
        ->withAvg('ratings as avg_rating', 'rate')
        ->search($title, $category_name, $author_name)
        ->whereHas('authors')
        ->whereHas('category')
        ->orderBy('id')
        ->get();


        /** Using resource */
        return ResponseHelper::success(' جميع الكتب', BookResource::collection($books));
    }

    public function bookList(){
        $books=Book::with(['category', 'authors'])->get();
        return ResponseHelper::success(' جميع الكتب', BookListResource::collection($books));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = "$request->ISBN." . $file->extension();
            Storage::putFileAs('book-images', $file, $filename);
            $validated['cover'] = $filename;
        }
        $book = Book::create($validated);

        // ربط المؤلفين بالكتاب
        $book->authors()->attach($validated['authors'] ?? []);

        // تحميل العلاقات لإرجاعها في الاستجابة
        $book->load(['category', 'authors']);

        return ResponseHelper::success("تمت إضافة الكتاب", $book);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book = $book->load(['authors', 'category'])  ->loadAvg('ratings as avg_rating', 'rate');

        return ResponseHelper::success("تم إعادة الكتاب بنجاح", new BookResource($book));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = "$request->ISBN." . $file->extension();
            if ($book->cover) {
                Storage::delete("book-images/$book->cover");
            }

            Storage::putFileAs('book-images', $file, $filename);
            $validated['cover'] = $filename;
        }
        $book->update($validated);


        $book->authors()->sync($validated['authors'] ?? []);

        $book->load(['category', 'authors']);

        return ResponseHelper::success("تمت تعديل الكتاب", $book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->cover) {
            Storage::delete("book-images/$book->cover");
        }
        $book->delete();
        return ResponseHelper::success("تم حذف الكتاب");
    }
}
