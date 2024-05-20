<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AddressProof;
use App\Models\DegreeImage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $validationMessages;

    public function __construct()
    {
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
    public function index()
    {
        $authUser = auth()->user();
        $roles = ['admin', 'superadmin'];
        if ($authUser->hasRole('superadmin')) {
            $roles = ['superadmin'];
        }

        $users = User::whereDoesntHave('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->statusActive()->paginate(10);

        return view('users.index', compact('users'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('users.create');
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

            // Handle user role
            $userRole = $request->input('user_role') ?? 3; //Assign default user/lawyer role (role_id 3)

            // Create the user
            $user = User::create($request->all());

            // Assign default role (role_id 3)
            $user->roles()->attach($userRole);

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
        $user = User::with(['roles', 'subscriptions'])->findOrFail($id);
        return view('users.show', compact('user'));
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        // Validate form data
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id, // Ensure email is unique except for the current user
            'mobile1' => 'nullable|numeric',
            'aadhaar_no' => 'required|numeric|unique:users,aadhaar_no,' . $id, // Ensure Aadhaar number is unique except for the current user
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
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $base64Picture = base64_encode(file_get_contents($file->getPathname()));
                $user->picture = $base64Picture;
            }

            // Update user data
            $user->first_name = $request->input('first_name');
            $user->middle_name = $request->input('middle_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->father_first_name = $request->input('father_first_name');
            $user->father_last_name = $request->input('father_last_name');
            $user->gender = $request->input('gender');
            $user->mobile1 = $request->input('mobile1');
            $user->mobile2 = $request->input('mobile2');
            $user->aadhaar_no = $request->input('aadhaar_no');
            $user->designation = $request->input('designation');
            $user->degrees = $request->input('degrees');
            $user->address = $request->input('address');
            $user->status = $request->input('status');
            $user->chamber_number = $request->input('chamber_number');
            $user->save();

            if ($request->input('user_role')) {
                $user->roles()->sync($request->input('user_role'));
            }

            // Handle address proofs
            if ($request->hasFile('address_proofs')) {
                // Delete old address proofs
                // AddressProof::where('user_id', $user->id)->delete();

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
                // Delete old degree pictures
                // DegreeImage::where('user_id', $user->id)->delete();

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

            return redirect()->route('users')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update user. Please try again.');
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Set the deleted_by field with the authenticated user's ID
        $user->deleted_by = Auth::id();
        $user->save(); // Save the user to update the deleted_by field

        // Soft delete the user
        $user->delete();

        return redirect()->route('users')->with('success', 'User deleted successfully!');
    }

    public function deleteImage($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->picture = NULL;
            $user->save();

            $message = 'Image deleted successfully.';
        } catch (\Exception $e) {
            $message = 'Something went wrong. Please try again.';
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
}
