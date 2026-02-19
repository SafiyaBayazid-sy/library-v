<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model{
    use HasFactory;
    protected $fillable = ['gender','DOB','phone'];

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    function bookRequest(){
        return $this->belongsToMany(BookRequest::class);
    }
  

     public function waitingBooks()
    {
        return $this->belongsToMany(Book::class, 'waiting_lists')
                    ->withTimestamps(); // if you have timestamps
    }



 public function ratedBooks()
    {
        return $this->belongsToMany(Book::class, 'book_customer')
                    ->withPivot('rate', 'created_at', 'updated_at') // Include all pivot fields
                    ->withTimestamps();
    }

}
