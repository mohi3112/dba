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
        })->statusActive()->get();

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
            'email' => 'required|email',
            'mobile1' => 'numeric',
            'aadhaar_no' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                // Get the picture file
                $file = $request->file('image');

                // Convert the picture to base64
                $base64Picture = base64_encode(file_get_contents($file->getPathname()));

                // Add base64 image data to the request
                $request->merge(['picture' => $base64Picture]);
            }

            $user = User::create($request->all());

            $user->roles()->attach(3); // default role_id 3 corresponds to assign 'user' role

            if ($request->hasFile('address_proofs')) {
                $imageDataArray = []; // Array to store base64 encoded image data

                foreach ($request->file('address_proofs') as $address_image) {
                    // Convert image to base64
                    $imageData = base64_encode(file_get_contents($address_image->getPathname()));
                    $imageDataArray[] = ['user_id' => $user->id, 'image' => $imageData, 'created_at' => now(), 'updated_at' => now()];
                }

                // Bulk insert into address_proofs table
                AddressProof::insert($imageDataArray);
            }

            if ($request->hasFile('degree_pictures')) {
                $degreeImagesData = []; // Array to store base64 encoded image data

                foreach ($request->file('degree_pictures') as $degree_image) {
                    // Convert image to base64
                    $degreeImageData = base64_encode(file_get_contents($degree_image->getPathname()));
                    $degreeImagesData[] = ['user_id' => $user->id, 'image' => $degreeImageData, 'created_at' => now(), 'updated_at' => now()];
                }

                // Bulk insert into degree_pictures table
                DegreeImage::insert($degreeImagesData);
            }

            DB::commit();

            return redirect()->route('users')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            // If an error occurs, rollback the transaction
            DB::rollback();
            // dd($e->getMessage());
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
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email',
            'mobile1' => 'numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // $user->update($request->all());

        return redirect()->route('users.edit')
            ->with('success', 'User updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users')->with('success', 'User deleted successfully!');
    }
}
