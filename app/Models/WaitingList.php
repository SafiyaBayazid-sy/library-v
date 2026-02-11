<?php
namespace App\Models;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitingList extends Model{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'book_id',
    ];

    function customer(){
        return $this->belongsTo(Customer::class);
    }

    function book(){
        return $this->belongsTo(Book::class);
    }


}
