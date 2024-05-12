<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookIssue extends Model
{
    protected $fillable = ['book_id', 'user_id', 'issue_date', 'return_date'];

    // Define relationship with Book model
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Define relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
