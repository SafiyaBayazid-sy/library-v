<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\ResponseHelper;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::all();
        return ResponseHelper::success('جميع المؤلفين', $authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:70'
        ]);

        $author = new Author();
        $author->name = $request->name;
        $author->save();

        return ResponseHelper::success("تمت إضافة المؤلف", $author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {

      $validated=   $request->validate([
    'name' => 'required|string|max:70',
    'birth_date' => 'nullable|date|before:today',
    'country' => 'nullable|string|max:100'
]);

         $author->update($validated);

        return ResponseHelper::success("تم تعديل المؤلف", $author);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {

        $author->delete();

        return ResponseHelper::success("تم حذف المؤلف", $author);
    }


     public function show(Author $author)
    {
        return ResponseHelper::success("عرض بيانات المؤلف", $author);
    }
}
