<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        // Fetch roles with issued roles
        $roles = Role::paginate(10);

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Role::create($request->all());

        return redirect()->route('roles')->with('success', 'Role added successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();

        return redirect()->route('roles')->with('success', 'Role updated successfully.');
    }
}
