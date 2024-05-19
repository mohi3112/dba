<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AddressProof;
use App\Models\DegreeImage;

class UserController extends Controller
{
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
            'mobile1' => 'nullable|numeric',
            'aadhaar_no' => 'required|numeric|unique:users,aadhaar_no', // Ensure Aadhaar number is unique
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address_proofs.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'degree_pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Handle the profile image
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $base64Picture = base64_encode(file_get_contents($file->getPathname()));
                $request->merge(['picture' => $base64Picture]);
            }

            // Create the user
            $user = User::create($request->all());

            // Assign default role (role_id 3)
            $user->roles()->attach(3);

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
    public function show(User $user)
    {
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
        ]);

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
            $user->city = $request->input('city');
            $user->state = $request->input('state');
            $user->country = $request->input('country');
            $user->zip = $request->input('zip');
            $user->status = $request->input('status');
            $user->chamber_number = $request->input('chamber_number');
            $user->floor_number = $request->input('floor_number');
            $user->building = $request->input('building');
            $user->save();

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
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users')->with('success', 'User deleted successfully!');
    }
}
