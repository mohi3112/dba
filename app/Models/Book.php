<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'book_name',
        'book_author_name',
        'book_licence',
        'book_licence_valid_upto',
        'available',
        'deleted_by',
        'book_category_id',
        'book_volume',
        'publish_date',
        'price',
    ];

    // Define relationship with BookIssue model
    public function issuedBooks()
    {
        return $this->hasMany(IssuedBook::class);
    }

    public function category()
    {
        return $this->belongsTo(BooksCategory::class, 'book_category_id');
    }
}
