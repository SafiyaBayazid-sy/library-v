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
     function waitingList(){
        return $this->belongsToMany(WaitingList::class);
    }


    public function ratedBooks()
{
    return $this->belongsToMany(Book::class, 'book_customer') // Explicit table name
                ->withPivot('rate')
                ->withTimestamps();
}

}
