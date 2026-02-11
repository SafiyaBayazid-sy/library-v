<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Author extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name','birth_date','country'];


    function books(){
        return $this->belongsToMany(Book::class );
    }
}
