<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    private function createPoliciesPayload($request)
    {
        $policies = [];
        $payload = $request->all();

        $policyArray = ($payload['policy_name']) ? $payload['policy_name'] : $payload['policy_number'];

        $count = count($policyArray);
        for ($i = 0; $i < $count; $i++) {
            $policies[] = [
                'policy_name' => $payload['policy_name'][$i] ?? null,
                'policy_number' => $payload['policy_number'][$i] ?? null,
                'policy_issue_date' => $payload['policy_issue_date'][$i] ?? null,
                'policy_expiry_date' => $payload['policy_expiry_date'][$i] ?? null,
            ];
        }
        return $policies;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required',
        ]);

        if ($request->has('policy_name') || $request->has('policy_number')) {
            $policiesArray = $this->createPoliciesPayload($request);
            $request->merge(['policies' => json_encode($policiesArray)]);
        }

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
            $existingPoliciesData = ($employee->policies) ? json_decode($employee->policies, true) : [];

            if ($request->has('policy_name') || $request->has('policy_number')) {
                $policiesArray = $this->createPoliciesPayload($request);
                $existingPoliciesData = array_merge($existingPoliciesData, $policiesArray);
            }

            $request->merge(['policies' => !(empty($existingPoliciesData)) ? json_encode($existingPoliciesData) : null]);

            $employee->name = $request->input('name');
            $employee->gender = $request->input('gender');
            $employee->email = $request->input('email');
            $employee->dob = $request->input('dob');
            $employee->phone = $request->input('phone');
            $employee->position = $request->input('position');
            $employee->salary = $request->input('salary');
            $employee->esi_number = $request->input('esi_number');
            $employee->esi_start_date = $request->input('esi_start_date');
            $employee->esi_end_date = $request->input('esi_end_date');
            $employee->esi_contribution = $request->input('esi_contribution');
            $employee->bank_account_number = $request->input('bank_account_number');
            $employee->bank_ifsc_code = $request->input('bank_ifsc_code');
            $employee->account_holder_name = $request->input('account_holder_name');
            $employee->branch_name = $request->input('branch_name');
            $employee->policies = $request->input('policies');
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

    public function destroyPolicyRecord(Request $request, $id)
    {
        try {
            // Get the employee record from the database
            $employeeRecord = Employee::findOrFail($id);

            // Get the policies data from the database
            $policiesData = json_decode($employeeRecord->policies, true);

            // Get the index to delete from the request
            $indexToDelete = $request->input('policyRecordIndex') - 1;

            // If the index exists, delete the record
            if (isset($policiesData[$indexToDelete])) {
                unset($policiesData[$indexToDelete]);
                // Reindex the array if necessary
                $policiesData = array_values($policiesData);
            }

            // Encode the data back to JSON and save
            $employeeRecord->policies = !empty($policiesData) ? json_encode($policiesData) : null;
            $employeeRecord->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Return an error response
            return response()->json(['error' => false]);
        }
    }

    public function dailyAttendance(Request $request)
    {
        $employeesQuery = Employee::query();

        $employeesQuery = $employeesQuery->with('attendances');

        // Left join the attendances table
        $employeesQuery->leftJoin('attendances', function ($join) use ($request) {
            $join->on('employees.id', '=', 'attendances.employee_id');

            // Apply the date condition within the join
            if ($request->filled('attendanceDate')) {
                $join->whereDate('attendances.date', '=', $request->attendanceDate);
            } else {
                $join->whereDate('attendances.date', '=', date('Y-m-d'));
            }
        });

        if ($request->filled('employeeName')) {
            $employeesQuery->where('employees.name', 'like', '%' . $request->employeeName . '%');
        }

        if ($request->filled('employeeGender')) {
            $employeesQuery->where('employees.gender', $request->employeeGender);
        }

        if ($request->filled('employeeEmail')) {
            $employeesQuery->where('employees.email', 'like', '%' . $request->employeeEmail . '%');
        }

        if ($request->filled('employeePhone')) {
            $employeesQuery->where('employees.phone', $request->employeePhone);
        }

        if ($request->filled('employeePosition')) {
            $employeesQuery->where('employees.position', $request->employeePosition);
        }

        $employeesQuery->select('employees.*', 'attendances.date', 'attendances.check_in', 'attendances.check_out');

        // Group by employee ID to handle the potential many-to-one relationship between employees and attendances
        $employees = $employeesQuery
            ->orderBy('employees.name')
            ->paginate(20);

        return view('employees.daily-attendance', compact('employees'));
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'attendance_date' => 'required',
            'attendance_type' => 'required',
        ]);

        try {
            // Get the employee and check if they already have a check-in for today
            $employee = Employee::findOrFail($request->employee_id);
            $attendanceDate = $request->attendance_date;
            $attendanceType = $request->attendance_type;

            $existingAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $attendanceDate)->first();

            $attendanceAlreadyMarked = false;

            if ($attendanceType == 'in' && $existingAttendance && $existingAttendance->check_in != null) {
                $attendanceAlreadyMarked = true;
            } elseif ($attendanceType == 'out' && $existingAttendance && $existingAttendance->check_out != null) {
                $attendanceAlreadyMarked = true;
            }

            if ($attendanceAlreadyMarked) {
                return response()->json(['message' => 'Attendance already marked.'], 400);
            }

            $current_time = Carbon::now()->format('H:i:s');

            if ($existingAttendance) {
                // Update the existing attendance record
                $existingAttendance->check_out = $current_time;
                $existingAttendance->save();
            } else {
                // Create a new attendance record with the current time

                $attendance = new Attendance();
                $attendance->employee_id = $employee->id;
                $attendance->date = $attendanceDate;

                if ($attendanceType == 'in') {
                    $attendance->check_in = $current_time;
                } elseif ($attendanceType == 'out') {
                    $attendance->check_out = $current_time;
                }

                // Save the attendance record
                $attendance->save();
            }

            return response()->json(['message' => 'Attendance marked successful.'], 200);
        } catch (\Exception $e) {
            // Log any exceptions for further investigation
            return response()->json(['message' => 'Error processing request.'], 500);
        }
    }

    public function attendanceReport(Request $request)
    {
        $startOfMonth = $request->query('startOfMonth');
        $endOfMonth = $request->query('endOfMonth');
        $name = $request->query('employeeName');

        if ($startOfMonth && $endOfMonth) {
            $startDate = Carbon::parse($startOfMonth)->toDateString();
            $endDate = Carbon::parse($endOfMonth)->toDateString();
        } else {
            $endDate = Carbon::now()->toDateString();
            $startDate = Carbon::now()->subDays(30)->toDateString();
        }

        $query = Attendance::with('employee')->select('employee_id', DB::raw('COUNT(*) as total_attendance'))
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotIn(DB::raw('DAYOFWEEK(date)'), [1, 7]) // Exclude Sundays (1) and Saturdays (7)
            ->groupBy('employee_id');

        if ($name) {
            $query->whereHas('employee', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        $attendances = $query->paginate(20);

        return view('employees.attendance-report', compact('attendances'));
    }
}
