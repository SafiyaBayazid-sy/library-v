<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCustomer extends Model
{

  use HasFactory;
  protected $table='book_customer';

    // protected $primaryKey = null;


 protected $fillable = [
        'rate',
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
