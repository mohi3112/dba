<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BooksCategory;
use App\Models\Location;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ModificationRequest;
use App\Models\Payment;
use App\Models\Rent;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Voucher;
use App\Services\LawyerService;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $lawyerService;

    public function __construct(LawyerService $lawyerService)
    {
        $this->lawyerService = $lawyerService;
    }

    public function getActiveVendorsList($onlyActive = true)
    {
        $roles = [User::DESIGNATION_VENDOR];
        $vendors = User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn(
                'id',
                $roles
            );
        })->with('vendorInfo');

        if ($onlyActive) {
            $all_vendors = $vendors->get();
        } else {
            $all_vendors = $vendors->withTrashed()->get();
        }

        return $all_vendors->mapWithKeys(function ($user) {
            return [$user->id => ['full_name' => $user->full_name, 'location_id' => $user->vendorInfo->location_id ?? NULL]];
        })->toArray();
    }

    public function getCategoriesList()
    {
        return BooksCategory::pluck('category_name', 'id');
    }

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

    public static function getApprovalValue($key)
    {
        switch ($key) {
            case 'yes':
                return true;
            case 'no':
                return false;
            case 'pending':
                return null;
            default:
                return;
        }
    }

    public function allRequests(Request $request)
    {
        $requestsQuery = ModificationRequest::query();

        if ($request->filled('table')) {
            $requestsQuery->where('table_name', $request->table);
        }

        if ($request->filled('requestType')) {
            $requestsQuery->where('action', $request->requestType);
        }

        if ($request->filled('approvedBySecretary')) {
            $approvedBySecretaryValue = $this->getApprovalValue($request->approvedBySecretary);
            $requestsQuery->where('approved_by_secretary', $approvedBySecretaryValue);
        }

        if ($request->filled('approvedByPresident')) {
            $approvedByPresidentValue = $this->getApprovalValue($request->approvedByPresident);
            $requestsQuery->where('approved_by_president', $approvedByPresidentValue);
        }

        $allRequests = $requestsQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('requests.all-requests', compact('allRequests'));
    }

    public function viewRequest($id)
    {
        $request = ModificationRequest::findOrFail($id);

        $categories = [];
        if ($request->table_name == 'books') {
            $categories = $this->getCategoriesList();
        }

        $activeLawyers = [];
        if ($request->table_name == 'payments' || $request->table_name == 'subscriptions') {
            $activeLawyers = $this->lawyerService->getActiveLawyers();
        }

        $activeVendors = [];
        if ($request->table_name == 'rents') {
            $activeVendors = $this->getActiveVendorsList();
        }

        return view('requests.view-request', compact('request', 'categories', 'activeLawyers', 'activeVendors'));
    }

    public function actionOnRequest(Request $request)
    {
        try {
            $requestId = $request->input('request_id');
            $isApproved = $request->input('is_approved');
            // Find the ModificationRequest record by ID
            $requestRecord = ModificationRequest::find($requestId);

            if ($requestRecord) {
                if (auth()->user()->hasRole('president')) {

                    $requestRecord->approved_by_president = $isApproved;

                    if ($isApproved == 1) {
                        if ($requestRecord->action == ModificationRequest::REQUEST_TYPE_DELETE) {
                            // update the record
                            $this->deleteRecordAfterApproval($requestRecord->record_id, $requestRecord);
                        } else {
                            // update the record
                            $this->updateRecordAfterApproval($requestRecord->record_id, $requestRecord);
                        }
                    }
                } elseif (auth()->user()->hasRole('secretary') || auth()->user()->hasRole('finance_secretary')) {

                    $requestRecord->approved_by_secretary = $isApproved;
                }
                // update the record
                $requestRecord->save();

                if ($requestRecord->action == ModificationRequest::REQUEST_TYPE_DELETE) {
                    return response()->json(['success' => true, 'message' => 'Request approved successfully!', 'redirect_url' => route('requests.update-requests')]);
                }
                return response()->json(['message' => 'Request approved successfully.'], 200);
            } else {
                return response()->json(['message' => 'Record not found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }

    public function deleteRecordAfterApproval($recordId, $requestRecord)
    {
        if ($requestRecord->table_name == 'locations') {
            $record = Location::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'books_categories') {
            $record = BooksCategory::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'books') {
            $record = Book::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'payments') {
            $record = Payment::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'subscriptions') {
            $record = Subscription::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'vouchers') {
            $record = Voucher::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'rents') {
            $record = Rent::findOrFail($recordId);
        }

        // Set the deleted_by field with the authenticated user's ID
        $record->deleted_by = $requestRecord->requested_by;
        $record->save(); // Save the user to update the deleted_by field
        // Soft delete the user
        $record->delete();
    }

    public function updateRecordAfterApproval($recordId, $requestRecord)
    {
        if ($requestRecord->table_name == 'locations') {
            $record = Location::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'books_categories') {
            $record = BooksCategory::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'books') {
            $record = Book::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'payments') {
            $record = Payment::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'subscriptions') {
            $record = Subscription::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'vouchers') {
            $record = Voucher::findOrFail($recordId);
        }

        if ($requestRecord->table_name == 'rents') {
            $record = Rent::findOrFail($recordId);
        }

        if ($record) {
            // update the record
            $record->update($requestRecord->changes);
        }
    }
}
