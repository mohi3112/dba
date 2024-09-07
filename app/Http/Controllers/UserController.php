<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AddressProof;
use App\Models\DegreeImage;
use App\Models\Family;
use App\Models\OtherDocument;
use App\Models\UserUpdateRequest;
use App\Models\Vendor;
use App\Services\LocationService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $validationMessages;
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;

        $this->validationMessages = [
            'first_name.required' => 'The first name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'mobile1.required' => 'The mobile number field is required.',
            'mobile1.numeric' => 'The mobile number must be numeric.',
            'aadhaar_no.required' => 'The Aadhaar number field is required.',
            'aadhaar_no.numeric' => 'The Aadhaar number must be numeric.',
            'image.image' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
        ];
    }

    // Display a listing of the resource.
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $roles = ['president', 'superadmin'];
        if ($authUser->hasRole('superadmin')) {
            $roles = ['superadmin'];
        }

        $usersQuery = User::query();

        $usersQuery->whereDoesntHave('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        });

        $usersQuery->where('designation', '<>', User::DESIGNATION_VENDOR);

        if ($request->filled('name')) {
            $usersQuery->where('first_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('l_name')) {
            $usersQuery->where('last_name', 'like', '%' . $request->l_name . '%');
        }

        if ($request->filled('licenceNo')) {
            $usersQuery->where('licence_no', 'like', '%' . $request->licenceNo . '%');
        }

        if ($request->filled('designation')) {
            $usersQuery->where('designation', $request->designation);
        }

        if ($request->filled('is_active') && $request->is_active == 'N') {
            $usersQuery->where('status', User::STATUS_IN_ACTIVE);
        } else {
            $usersQuery->statusActive();
        }

        if ($request->filled('age')) {
            $usersQuery->age($request->age, $request->ageOperator);
        }

        if ($request->filled('gender')) {
            $usersQuery->where('gender', $request->gender);
        }

        if ($request->filled('is_deceased')) {
            $usersQuery->where('is_deceased', true);
        }

        if ($request->filled('is_physically_disabled')) {
            $usersQuery->where('is_physically_disabled', true);
        }

        $users = $usersQuery->orderBy('id', 'desc')->paginate(10);

        return view('users.index', compact('users'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $activeLocations = $this->locationService->getActiveLocations();
        return view('users.create', compact('activeLocations'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email', // Ensure email is unique
            'mobile1' => 'required|nullable|numeric',
            'aadhaar_no' => 'required|numeric|unique:users,aadhaar_no', // Ensure Aadhaar number is unique
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address_proofs.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'degree_pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $this->validationMessages);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Handle the profile image
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $base64Picture = base64_encode(file_get_contents($file->getPathname()));
                $request->merge(['picture' => $base64Picture]);
            }

            if (!$request->has('status')) {
                $request->merge(['status' => User::STATUS_IN_ACTIVE]);
            } else {
                $request->merge(['status' => User::STATUS_ACTIVE]);
            }

            if (!$request->has('is_deceased')) {
                $request->merge(['is_deceased' => false]);
            }

            if (!$request->has('is_physically_disabled')) {
                $request->merge(['is_physically_disabled' => false]);
            }

            if ($request->has('password')) {
                $request->merge(['password' => Hash::make($request->input('password'))]);
            }

            // Handle user role
            $userRole = $request->input('designation') ?? User::DESIGNATION_LAWYER; //Assign default user/lawyer role (role_id 3)

            $accountApproved = false;
            if (auth()->user()->hasRole('president')) {
                $accountApproved = true;
            }
            $request->merge(['account_approved' => $accountApproved]);

            // Create the user
            $user = User::create($request->all());

            if ($user->designation == User::DESIGNATION_VENDOR) {
                $payload = $request->all();
                $payload['user_id'] = $user->id;
                $payload['business_name'] = $request->input('business_name');
                $payload['employees'] = $request->input('employees');
                $payload['location_id'] = $request->input('location_id');
                $payload['security_deposit'] = $request->input('security_deposit');
                Vendor::create($payload);
            }

            // Assign default role (role_id 8)
            $user->roles()->attach($userRole);

            // submit for approval
            if (!$accountApproved) {
                $payload = $request->all();
                // user submitted for approval
                $payload['user_id'] = $user->id;
                $payload['changes_requested_by'] = Auth::id();
                $payload['change_type'] = UserUpdateRequest::CHANGE_TYPE_EDIT;

                // user submitted for approval
                UserUpdateRequest::create($payload);
            }

            // Handle address proofs
            if ($request->hasFile('address_proofs')) {
                $addressProofsData = [];

                foreach ($request->file('address_proofs') as $addressImage) {
                    $imageData = base64_encode(file_get_contents($addressImage->getPathname()));
                    $addressProofsData[] = [
                        'user_id' => $user->id,
                        'image' => $imageData,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                AddressProof::insert($addressProofsData);
            }

            // Handle degree pictures
            if ($request->hasFile('degree_pictures')) {
                $degreeImagesData = [];

                foreach ($request->file('degree_pictures') as $degreeImage) {
                    $imageData = base64_encode(file_get_contents($degreeImage->getPathname()));
                    $degreeImagesData[] = [
                        'user_id' => $user->id,
                        'image' => $imageData,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                DegreeImage::insert($degreeImagesData);
            }

            // handle other docuements
            if ($request->hasFile('document')) {
                $otherDocuments = [];
                foreach ($request->file('document') as $otherDoc_key => $otherDocumentFile) {
                    $file = base64_encode(file_get_contents($otherDocumentFile->getPathname()));
                    $otherDocuments[] = [
                        'doc_type' => !empty($request->doc_type) ? $request->doc_type[$otherDoc_key] : null,
                        'document' => $file,
                        'user_id' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                OtherDocument::insert($otherDocuments);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('users')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to add user. Please try again.');
        }
    }

    // Display the specified resource.
    public function show($id)
    {
        $user = User::with([
            'other_documents',
            'roles',
            'subscriptions' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'payments' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'issuedBooks' => function ($qry) {
                $qry->with('book');
                $qry->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        return view('users.show', compact('user'));
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $user = User::find($id);
        if ($user->designation == User::DESIGNATION_VENDOR) {
            $user = User::with('vendorInfo')->find($id);
        }
        $activeLocations = $this->locationService->getActiveLocations();

        return view('users.edit', compact('user', 'activeLocations'));
    }

    // Show the form for editing the specified resource.
    public function myAccount()
    {
        $userId = Auth::id();

        $user = User::leftJoin('user_update_requests', function ($join) {
            $join->on('users.id', '=', 'user_update_requests.user_id')
                ->where(function ($query) {
                    $query->whereNull('user_update_requests.approved_by_president')
                        ->whereIn('user_update_requests.approved_by_secretary', [NULL, TRUE])
                        ->whereNull('user_update_requests.deleted_at');
                });
        })->leftJoin('vendors', 'users.id', '=', 'vendors.user_id')
            ->select('users.*', 'vendors.business_name', 'vendors.employees', 'vendors.location_id', 'user_update_requests.approved_by_secretary', 'user_update_requests.approved_by_president')
            ->where('users.id', $userId)
            ->first();

        $activeLocations = $this->locationService->getActiveLocations();

        return view('users.myAccount', compact('user', 'activeLocations'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        // Validate form data
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id, // Ensure email is unique except for the current user
            'mobile1' => 'nullable|numeric',
            'aadhaar_no' => 'required|numeric', // Ensure Aadhaar number is unique except for the current user
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address_proofs.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'degree_pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $this->validationMessages);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Find the user by ID
            $user = User::findOrFail($id);

            // Handle the profile image
            $base64Picture = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $base64Picture = base64_encode(file_get_contents($file->getPathname()));
                $request->merge(['picture' => $base64Picture]);
            }

            if (!$request->has('status') || $request->status == User::STATUS_IN_ACTIVE) {
                $request->merge(['status' => User::STATUS_IN_ACTIVE]);
            } else {
                $request->merge(['status' => User::STATUS_ACTIVE]);
            }

            if (!$request->has('is_deceased') || $request->is_deceased == false) {
                $request->merge(['is_deceased' => false]);
            } else {
                $request->merge(['is_deceased' => true]);
            }

            if (!$request->has('is_physically_disabled') || $request->is_physically_disabled == false) {
                $request->merge(['is_physically_disabled' => false]);
            } else {
                $request->merge(['is_physically_disabled' => true]);
            }

            // Update user data if user is president
            if (auth()->user()->hasRole('president')) {
                $user->first_name = $request->input('first_name');
                $user->middle_name = $request->input('middle_name');
                $user->last_name = $request->input('last_name');
                $user->email = $request->input('email');
                $user->father_first_name = $request->input('father_first_name');
                $user->father_last_name = $request->input('father_last_name');
                $user->dob = $request->input('dob');
                $user->gender = $request->input('gender');
                $user->mobile1 = $request->input('mobile1');
                $user->mobile2 = $request->input('mobile2');
                $user->aadhaar_no = $request->input('aadhaar_no');
                $user->licence_no = $request->input('licence_no');
                $user->designation = $request->input('designation');
                $user->degrees = $request->input('degrees');
                $user->address = $request->input('address');
                $user->other_details = $request->input('other_details');
                $user->status = $request->input('status');
                $user->chamber_number = $request->input('chamber_number');
                $user->status = $request->input('status');
                $user->is_deceased = $request->input('is_deceased');
                $user->is_physically_disabled = $request->input('is_physically_disabled');

                if ($request->input('password') && $request->input('password') != "") {
                    $user->password = Hash::make($request->input('password'));
                }

                if ($request->hasFile('image') && $base64Picture) {
                    $user->picture = $request->input('picture');
                }

                $user->save();

                if ($user->designation == User::DESIGNATION_VENDOR) {
                    $existingRecord = Vendor::where('user_id', $user->id)->first();

                    $payload['business_name'] = $request->input('business_name');
                    $payload['employees'] = $request->input('employees');
                    $payload['location_id'] = $request->input('location_id');
                    $payload['security_deposit'] = $request->input('security_deposit');

                    if ($existingRecord) {
                        $existingRecord->update($payload);
                    } else {
                        $payload['user_id'] = $user->id;
                        Vendor::create($payload);
                    }
                }

                if ($request->input('designation')) {
                    $user->roles()->sync($request->input('designation'));
                }
            } else {
                $user->account_modified = true;
                $user->save();

                $this->userRequestSubmitted($id, $request);
            }

            // Handle address proofs
            if ($request->hasFile('address_proofs')) {

                $addressProofsData = [];
                foreach ($request->file('address_proofs') as $addressImage) {
                    $imageData = base64_encode(file_get_contents($addressImage->getPathname()));
                    $addressProofsData[] = [
                        'user_id' => $user->id,
                        'image' => $imageData,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                // Insert new address proofs
                AddressProof::insert($addressProofsData);
            }

            // Handle degree pictures
            if ($request->hasFile('degree_pictures')) {

                $degreeImagesData = [];
                foreach ($request->file('degree_pictures') as $degreeImage) {
                    $imageData = base64_encode(file_get_contents($degreeImage->getPathname()));
                    $degreeImagesData[] = [
                        'user_id' => $user->id,
                        'image' => $imageData,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                // Insert new degree pictures
                DegreeImage::insert($degreeImagesData);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function userRequestSubmitted($id, $request)
    {
        // user submitted for approval
        $loggedInUserId = Auth::id();
        $payload = $request->all();
        $payload['change_type'] = UserUpdateRequest::CHANGE_TYPE_EDIT;
        $payload['user_id'] = $id;
        $payload['changes_requested_by'] = $loggedInUserId;

        // Submit Request
        $this->submitUpdateOrDeleteUserRequest($id, $payload);
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            if (auth()->user()->hasRole('president')) {
                // Set the deleted_by field with the authenticated user's ID
                $user->deleted_by = Auth::id();
                $user->save(); // Save the user to update the deleted_by field
                // Soft delete the user
                $user->delete();
            } else {

                $payload = $user->toArray();
                // unset unnecessary fields
                unset($payload['id'], $payload['created_at'], $payload['updated_at'], $payload['email_verified_at']);

                $payload['user_id'] = $user->id;
                $payload['change_type'] = UserUpdateRequest::CHANGE_TYPE_DELETE;
                $payload['changes_requested_by'] = Auth::id();

                // Submit Request
                $this->submitUpdateOrDeleteUserRequest($user->id, $payload);
            }

            return redirect()->route('users')->with('success', 'User delete request submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('users')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    private function submitUpdateOrDeleteUserRequest($userId, $payload)
    {
        $existingRequest = UserUpdateRequest::where('user_id', $userId)->where('approved_by_president', NULL)->where('approved_by_secretary', NULL)->first();

        if ($existingRequest) {
            $existingRequest->update($payload);
        } else {
            UserUpdateRequest::create($payload);
        }
    }

    public function deleteImage($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->picture = NULL;
            $user->save();

            $message = 'Image deleted successfully.';
        } catch (\Exception $e) {
            $message = 'Something went wrong. Please try again later.';
        }

        return response()->json(['message' => $message]);
    }

    public function deleteAddressProofImage($id)
    {
        try {
            $addressProofRecord = AddressProof::findOrFail($id);
            $addressProofRecord->delete();

            $message = 'Image deleted successfully.';
        } catch (\Exception $e) {
            $message = 'Something went wrong. Please try again.';
        }

        return response()->json(['message' => $message]);
    }

    public function deleteDegreeImage($id)
    {
        try {
            $addressProofRecord = DegreeImage::findOrFail($id);
            $addressProofRecord->delete();

            $message = 'Image deleted successfully.';
        } catch (\Exception $e) {
            $message = 'Something went wrong. Please try again.';
        }

        return response()->json(['message' => $message]);
    }

    public function deleteOtherDocument($id)
    {
        try {
            $addressProofRecord = OtherDocument::findOrFail($id);
            $addressProofRecord->delete();

            $message = 'Record deleted successfully.';
        } catch (\Exception $e) {
            $message = 'Something went wrong. Please try again.';
        }

        return response()->json(['message' => $message]);
    }

    public function telephoneDirectory(Request $request)
    {
        $usersQuery = User::select('id', 'first_name', 'middle_name', 'last_name', 'mobile1', 'mobile2', 'status', 'is_deceased', 'is_physically_disabled');

        if ($request->filled('name')) {
            $usersQuery->where('first_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('l_name')) {
            $usersQuery->where('last_name', 'like', '%' . $request->l_name . '%');
        }

        if (count($_GET) > 0 && !$request->filled('is_active')) {
            $usersQuery->where('status', User::STATUS_IN_ACTIVE);
        } else {
            $usersQuery->statusActive();
        }

        if ($request->filled('is_deceased')) {
            $usersQuery->where('is_deceased', true);
        }

        if ($request->filled('is_physically_disabled')) {
            $usersQuery->where('is_physically_disabled', true);
        }

        $users = $usersQuery->paginate(10);

        return view('users.telephone-directory', compact('users'));
    }

    public function votingList(Request $request)
    {
        // Get today's date
        $today = Carbon::today();

        $usersQuery = User::whereHas('subscriptions', function ($query) use ($today) {
            $query->whereNotNull('end_date')
                ->where('end_date', '>', $today);
        })->select('id', 'first_name', 'middle_name', 'last_name', 'aadhaar_no', 'licence_no', 'mobile1', 'mobile2', 'address', 'status', 'is_deceased');

        if ($request->filled('name')) {
            $usersQuery->where('first_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('l_name')) {
            $usersQuery->where('last_name', 'like', '%' . $request->l_name . '%');
        }

        if ($request->filled('aadhaarNo')) {
            $usersQuery->where('aadhaar_no', 'like', '%' . $request->aadhaarNo . '%');
        }

        if ($request->filled('licenceNo')) {
            $usersQuery->where('licence_no', 'like', '%' . $request->licenceNo . '%');
        }

        if (count($_GET) > 0 && !$request->filled('is_active')) {
            $usersQuery->where('status', User::STATUS_IN_ACTIVE);
        } else {
            $usersQuery->statusActive();
        }

        if ($request->filled('is_deceased')) {
            $usersQuery->where('is_deceased', true);
        }

        $users = $usersQuery->paginate(10);

        return view('users.voting-list', compact('users'));
    }

    public function allUpdateRequests(Request $request)
    {
        $updateRequestsQuery = UserUpdateRequest::query();

        if ($request->filled('name')) {
            $updateRequestsQuery->where('first_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('l_name')) {
            $updateRequestsQuery->where('last_name', 'like', '%' . $request->l_name . '%');
        }

        if ($request->filled('designation')) {
            $updateRequestsQuery->where('designation', $request->designation);
        }

        if ($request->filled('age')) {
            $updateRequestsQuery->age($request->age, $request->ageOperator);
        }

        if ($request->filled('gender')) {
            $updateRequestsQuery->where('gender', $request->gender);
        }

        if ($request->filled('approvedBySecretary')) {
            $approvedBySecretaryValue = $this->getApprovalValue($request->approvedBySecretary);
            $updateRequestsQuery->where('approved_by_secretary', $approvedBySecretaryValue);
        }

        if ($request->filled('approvedByPresident')) {
            $approvedByPresidentValue = $this->getApprovalValue($request->approvedByPresident);
            $updateRequestsQuery->where('approved_by_president', $approvedByPresidentValue);
        }

        $updateRequests = $updateRequestsQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('users.update-requests', compact('updateRequests'));
    }

    public function viewUpdateRequest($id)
    {
        $updateRequest = UserUpdateRequest::with(['user' => function ($query) {
            $query->withTrashed();
        }])->findOrFail($id);

        $activeLocations = [];
        if ($updateRequest->designation == User::DESIGNATION_VENDOR) {
            $activeLocations = $this->locationService->getActiveLocations();
        }

        return view('users.view-update-request', compact('updateRequest', 'activeLocations'));
    }

    public function approveRequest(Request $request)
    {
        try {
            $requestId = $request->input('request_id');
            $isApproved = $request->input('is_approved');
            // Find the UserUpdateRequest record by ID
            $userUpdateRequest = UserUpdateRequest::find($requestId);

            if ($userUpdateRequest) {
                if (auth()->user()->hasRole('president')) {

                    $userUpdateRequest->approved_by_president = $isApproved;

                    if ($isApproved == 0) {
                        // decline the request
                        $this->declineProfileChanges($userUpdateRequest->user_id);
                    } else {
                        if ($isApproved == 1) {

                            if ($userUpdateRequest->change_type == UserUpdateRequest::CHANGE_TYPE_DELETE) {
                                // update the user data
                                $this->deleteUserAfterApproval($userUpdateRequest->user_id, $userUpdateRequest);
                            } else {
                                // update the user data
                                $this->updateUserAfterApproval($userUpdateRequest->user_id, $userUpdateRequest);
                            }
                        }
                    }
                } elseif (auth()->user()->hasRole('secretary') || auth()->user()->hasRole('finance_secretary')) {

                    $userUpdateRequest->approved_by_secretary = $isApproved;

                    if ($isApproved != 1) {
                        $this->declineProfileChanges($userUpdateRequest->user_id);
                    }
                }
                // update the user request
                $userUpdateRequest->save();

                if ($userUpdateRequest->change_type == UserUpdateRequest::CHANGE_TYPE_DELETE) {
                    return response()->json(['success' => true, 'message' => 'Request approved successfully!', 'redirect_url' => route('users.update-requests')]);
                }
                return response()->json(['message' => 'Request approved successfully.'], 200);
            } else {
                return response()->json(['message' => 'Record not found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }

    public function declineProfileChanges($userId)
    {
        $user = User::findOrFail($userId);
        $user->account_modified = false;
        $user->save();
    }

    // delete user after approve
    public function deleteUserAfterApproval($userId, $userUpdateRequest)
    {
        $user = User::findOrFail($userId);

        // Set the deleted_by field with the authenticated user's ID
        $user->deleted_by = $userUpdateRequest->changes_requested_by;
        $user->save(); // Save the user to update the deleted_by field
        // Soft delete the user
        $user->delete();
    }

    public function updateUserAfterApproval($userId, $userUpdateRequest)
    {
        // Update user data
        $user = User::findOrFail($userId);
        $user->first_name = $userUpdateRequest->first_name;
        $user->middle_name = $userUpdateRequest->middle_name;
        $user->last_name = $userUpdateRequest->last_name;
        $user->email = $userUpdateRequest->email;
        $user->father_first_name = $userUpdateRequest->father_first_name;
        $user->father_last_name = $userUpdateRequest->father_last_name;
        $user->dob = $userUpdateRequest->dob;
        $user->gender = $userUpdateRequest->gender;
        $user->mobile1 = $userUpdateRequest->mobile1;
        $user->mobile2 = $userUpdateRequest->mobile2;
        $user->aadhaar_no = $userUpdateRequest->aadhaar_no;
        $user->licence_no = $userUpdateRequest->licence_no;
        $user->designation = $userUpdateRequest->designation;
        $user->degrees = $userUpdateRequest->degrees;
        $user->address = $userUpdateRequest->address;
        $user->other_details = $userUpdateRequest->other_details;
        $user->status = $userUpdateRequest->status;
        $user->chamber_number = $userUpdateRequest->chamber_number;
        $user->status = $userUpdateRequest->status;
        $user->is_deceased = $userUpdateRequest->is_deceased ?: false;
        $user->is_physically_disabled = $userUpdateRequest->is_physically_disabled ?: false;
        $user->picture = $userUpdateRequest->picture;
        $user->account_modified = false;
        $user->account_approved = true;
        $user->save();

        if ($userUpdateRequest->designation) {
            $user->roles()->sync($userUpdateRequest->designation);
        }

        if ($userUpdateRequest->designation == User::DESIGNATION_VENDOR) {
            $existingRecord = Vendor::where('user_id', $user->id)->first();

            $payload = [];
            $payload['business_name'] = $userUpdateRequest->business_name;
            $payload['employees'] = $userUpdateRequest->employees;
            $payload['location_id'] = $userUpdateRequest->location_id;

            if ($existingRecord) {
                $existingRecord->update($payload);
            } else {
                $payload['user_id'] = $user->id;
                Vendor::create($payload);
            }
        }
    }

    public function deleteUpdateRequest($id)
    {
        // Find the user-update-request by ID
        $userUpdateRequest = UserUpdateRequest::findOrFail($id);

        // Set the deleted_by field with the authenticated user-update-request's ID
        $userUpdateRequest->deleted_by = Auth::id();
        $userUpdateRequest->save(); // Save the user-update-request to update the deleted_by field

        // Soft delete the user-update-request
        $userUpdateRequest->delete();

        return redirect()->route('users.update-requests')->with('success', 'Request deleted successfully!');
    }

    public function storeFamily(Request $request)
    {
        $request->validate([
            'type.*' => 'required|string',
            'name.*' => 'required|string',
            'date.*' => 'date',
        ]);

        $user = Auth::user();

        // Delete old family members
        $user->families()->delete();

        $families = [];
        foreach ($request->type as $index => $type) {
            $families[] = [
                'user_id' => $user->id,
                'type' => $type,
                'name' => $request->name[$index],
                'date' => $request->date[$index],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Family::insert($families);

        return redirect()->back()->with('success', 'Family members updated successfully.');
    }

    public function destroyFamilyRecord($id)
    {
        $family = Family::findOrFail($id);
        $family->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }
}
