<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileView extends Model
{
    protected $fillable = [
        'viewer_user_id',
        'viewer_full_name',
        'viewed_user_id',
        'viewed_at',
        'notification_viewed'
    ];

    protected $casts = [
        'viewed_at' => 'datetime'
    ];

    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewer_user_id');
    }

    public function viewedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewed_user_id');
    }
}
