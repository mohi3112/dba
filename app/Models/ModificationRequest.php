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
        return $this->belongsTo(Location::class, 'record_id')->where('table_name', 'locations')->where('approved_by_secretary', true)->whereNull('approved_by_president');
    }
}
