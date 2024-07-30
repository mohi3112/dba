<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherDocument extends Model
{
    use SoftDeletes;

    protected $fillable = ['doc_type', 'document', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
