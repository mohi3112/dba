<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModificationRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'table_name',
        'record_id',
        'changes',
        'action',
        'requested_by',
        'approved_by_secretary',
        'approved_by_president',
        'deleted_by'
    ];

    const REQUEST_TYPE_UPDATE = 1;
    const REQUEST_TYPE_DELETE = 2;

    const PENDING_REQUEST = "Pending";
    const APPROVED_REQUEST = "Approved";
    const REJECTED_REQUEST = "Rejected";

    public static $tableNames = [
        'locations' => 'Location',
        'books' => 'Books',
        'books_categories' => 'Books Category',
        'payments' => 'Payment',
        'subscriptions' => 'Subscription',
    ];

    public static $reuqestType = [
        self::REQUEST_TYPE_UPDATE => 'Update',
        self::REQUEST_TYPE_DELETE => 'Delete'
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function related()
    {
        return $this->morphTo(null, 'table_name', 'record_id');
    }

    // Relationship to the Location model
    public function location()
    {
        return $this->belongsTo(Location::class, 'record_id');
    }
    // Relationship to the Book categories model
    public function bookCategory()
    {
        return $this->belongsTo(BooksCategory::class, 'record_id');
    }
}
