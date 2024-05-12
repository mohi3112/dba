<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['book_name', 'book_author_name', 'book_licence', 'book_licence_valid_upto'];

    // Define relationship with BookIssue model
    public function issues()
    {
        return $this->hasMany(BookIssue::class);
    }
}
