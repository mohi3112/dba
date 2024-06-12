<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ModificationRequest;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function submitChangeRequest($payload = [])
    {
        if (empty($payload)) return;

        $checkExistingRecord = ModificationRequest::where('table_name', $payload['table_name'])
            ->where('record_id', $payload['record_id'])
            ->whereNull('approved_by_secretary')
            ->whereNull('approved_by_president')
            ->first();

        if ($checkExistingRecord) {
            return $checkExistingRecord->update($payload);
        } else {
            return ModificationRequest::create($payload);
        }
    }
}
