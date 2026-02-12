<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

 protected $fillable = ['ISBN' , 'title' , 'price' , 'mortgage', 'category_id','pages',
    'borrow_duration','total_copies','remaining_copies','authorship_date'];
        //  protected $guraded = [];


    function category(){
        return $this->belongsTo(Category::class);
    }
    function authors(){
        return $this->belongsToMany(Author::class);
    }
  function waitingList(){
        return $this->belongsToMany(WaitingList::class);
    }

      public function ratings()
    {
        return $this->hasMany(BookCustomer::class, 'book_id');
    }






    public function scopeSearch($query,$title,$category_name,$author_name){
    return $query->when(
        $title,function($q) use ($title){
           $q->where('title','like',"%$title%");
        }
    )
    ->when($category_name,function($q) use ($category_name){
        return $q->whereHas('category',function($subquery) use($category_name){
             $subquery->where('name','like',"%$category_name%");
        });
    })
    ->when($author_name,function($q) use ($author_name){
        return $q->whereHas('authors',function($subquery) use ($author_name){
             $subquery->where('name','like',"%$author_name%");
        });
    });


}
}
