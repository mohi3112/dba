<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $users = User::all();
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
        ]);

        $user = User::create($request->all());

        $user->roles()->attach(3); // role_id 3 corresponds to 'user'

        return redirect()->route('users')
            ->with('success', 'User created successfully.');
    }

    // Display the specified resource.
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Show the form for editing the specified resource.
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, User $user)
    {
        $request->validate([
            // Add validation rules for fields
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
