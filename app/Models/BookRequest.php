<?php
namespace App\Models;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model{
    use HasFactory;
    protected $fillable = [
        'book_title',
        'customer_id',
        'author_name',
        'admin_note',
        'status',
    ];

    function customer(){
        return $this->belongsTo(Customer::class);
    }

    function book(){
        return $this->belongsTo(Book::class);
    }


}
