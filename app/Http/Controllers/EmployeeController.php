<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employeesQuery = Employee::query();
        if ($request->filled('employeeName')) {
            $employeesQuery->where('name', 'like', '%' . $request->employeeName . '%');
        }

        if ($request->filled('employeeGender')) {
            $employeesQuery->where('gender', $request->employeeGender);
        }

        if ($request->filled('employeeEmail')) {
            $employeesQuery->where('email', 'like', '%' . $request->employeeEmail . '%');
        }

        if ($request->filled('employeePhone')) {
            $employeesQuery->where('phone', $request->employeePhone);
        }

        if ($request->filled('employeePosition')) {
            $employeesQuery->where('position', $request->employeePosition);
        }

        $employees = $employeesQuery->orderBy('id', 'desc')->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees')->with('success', 'Employee record added successfully.');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required',
        ]);
        $employee = Employee::findOrFail($id);

        if ($employee) {
            $employee->name = $request->name;
            $employee->gender = $request->gender;
            $employee->email = $request->email;
            $employee->dob = $request->dob;
            $employee->phone = $request->phone;
            $employee->position = $request->position;
            $employee->salary = $request->salary;
            $employee->save();

            return redirect()->route('employees')->with('success', 'Employee record updated successfully.');
        }

        return redirect()->route('employees')->with('error', 'Something went wrong.');
    }

    public function destroy($id)
    {
        // Find the payment by ID
        $employee = Employee::findOrFail($id);

        if ($employee) {
            $employee->deleted_by = Auth::id();
            $employee->save();

            // Soft delete the rent
            $employee->delete();

            return redirect()->route('employees')->with('success', 'Record deleted successfully!');
        }

        return redirect()->route('employees')->with('error', 'Something went wrong.');
    }
}
