<?php

namespace App\Traits;

use App\Models\ProfileView;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

trait HasProfileViews
{
    public function profileViews(): HasMany
    {
        return $this->hasMany(ProfileView::class, 'viewed_user_id');
    }

    public function viewedProfiles(): HasMany
    {
        return $this->hasMany(ProfileView::class, 'viewer_user_id');
    }

    public function recordProfileView(int $viewedUserId): void
    {
        // Don't record if user is viewing their own profile
        if ($this->id === $viewedUserId) {
            return;
        }

        // Check if there's a view record within the last hour
        $lastView = ProfileView::where('viewer_user_id', $this->id)
            ->where('viewed_user_id', $viewedUserId)
            ->where('viewed_at', '>=', Carbon::now()->subHour())
            ->first();

        // Only create a new record if there's no view within the last hour
        if (!$lastView) {
            ProfileView::create([
                'viewer_user_id' => $this->id,
                'viewer_full_name' => $this->full_name,
                'viewed_user_id' => $viewedUserId,
                'viewed_at' => Carbon::now(),
            ]);
        }
    }
}
