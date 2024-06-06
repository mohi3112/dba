<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BooksCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_name',
        'published_volumns',
        'published_total_volumns',
        'deleted_by',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'book_category_id', 'id');
    }
}
