<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use App\Models\ModificationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employeesQuery = User::with('employees')->where('designation', User::DESIGNATION_EMPLOYEE);

        if ($request->filled('name')) {
            $employeesQuery->where('first_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('l_name')) {
            $employeesQuery->where('last_name', 'like', '%' . $request->l_name . '%');
        }

        if (count($_GET) > 0 && !$request->filled('is_active')) {
            $employeesQuery->where('status', User::STATUS_IN_ACTIVE);
        } else {
            $employeesQuery->statusActive();
        }

        if ($request->filled('gender')) {
            $employeesQuery->where('gender', $request->gender);
        }

        if ($request->filled('is_deceased')) {
            $employeesQuery->where('is_deceased', true);
        }

        if ($request->filled('is_physically_disabled')) {
            $employeesQuery->where('is_physically_disabled', true);
        }

        $employees = $employeesQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('users.create');
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

            if (auth()->user()->hasRole('president') || auth()->user()->hasRole('clerk')) {
                $employee->name = $request->input('name');
                $employee->father_name = $request->input('father_name');
                $employee->gender = $request->input('gender');
                $employee->aadhaar_no = $request->input('aadhaar_no');
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
            } else {
                $changes = $request->except(['_token', '_method']);
                $this->submitChangeRequest([
                    "table_name" => 'employees',
                    "record_id" => $employee->id,
                    "changes" => $changes,
                    "action" => ModificationRequest::REQUEST_TYPE_UPDATE,
                    "requested_by" => Auth::id(),
                ]);

                return redirect()->route('employees')->with('success', 'Employee updated request submitted successfully.');
            }
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
        $employeesQuery = User::query();

        $employeesQuery->where('users.designation', User::DESIGNATION_EMPLOYEE);

        // Include 'employees' and 'attendances' relationships
        $employeesQuery = $employeesQuery->with(['employees', 'employees.attendances']);

        // Left join employees (since User has many Employees)
        $employeesQuery->leftJoin('employees', 'users.id', '=', 'employees.user_id');

        // Left join attendances (linking through employees)
        $employeesQuery->leftJoin('attendances', function ($join) use ($request) {
            $join->on('employees.id', '=', 'attendances.employee_id');

            // Apply the date condition within the join
            if ($request->filled('attendanceDate')) {
                $join->whereDate('attendances.date', '=', $request->attendanceDate);
            } else {
                $join->whereDate('attendances.date', '=', date('Y-m-d'));
            }
        });

        // Filters based on `users` table
        if ($request->filled('employeeName')) {
            $employeesQuery->where('users.first_name', 'like', '%' . $request->employeeName . '%');
        }

        if ($request->filled('employeeGender')) {
            $employeesQuery->where('users.gender', $request->employeeGender);
        }

        if ($request->filled('employeeEmail')) {
            $employeesQuery->where('users.email', 'like', '%' . $request->employeeEmail . '%');
        }

        if ($request->filled('employeePhone')) {
            $employeesQuery->where('users.mobile1', $request->employeePhone);
        }

        // Filter by employee-specific fields
        if ($request->filled('employeePosition')) {
            $employeesQuery->where('employees.position', $request->employeePosition);
        }

        // Select fields from all related tables
        $employeesQuery->select(
            'users.*',
            'employees.id as employee_id',
            'employees.position',
            'attendances.date',
            'attendances.check_in',
            'attendances.check_out'
        );

        // Order by User's first name
        $employees = $employeesQuery
            ->orderBy('users.first_name')
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

        $query = Attendance::query()
            ->select('employees.user_id', DB::raw('COUNT(*) as total_attendance'))
            ->leftJoin('employees', 'attendances.employee_id', '=', 'employees.id') // Join employees table
            ->leftJoin('users', 'employees.user_id', '=', 'users.id') // Join users table
            ->whereBetween('attendances.date', [$startDate, $endDate])
            ->whereNotIn(DB::raw('DAYOFWEEK(attendances.date)'), [1]) // Exclude Sundays (1) and Saturdays (7)
            ->groupBy('employees.user_id');

        if ($request->filled('calculate_salary')) {
            if ($request->filled('publicHolidays')) {
            }
        }
        // Search by user name instead of employee name
        if ($name) {
            $query->where('users.first_name', 'like', '%' . $name . '%');
        }

        $attendances = $query->paginate(50);

        return view('employees.attendance-report', compact('attendances'));
    }

    public function attendanceDetails($id, Request $request)
    {
        $startOfMonth = $request->query('startDate');
        $endOfMonth = $request->query('endDate');

        if ($startOfMonth && $endOfMonth) {
            $startDate = Carbon::parse($startOfMonth)->toDateString();
            $endDate = Carbon::parse($endOfMonth)->toDateString();
        } else {
            $endDate = Carbon::now()->toDateString();
            $startDate = Carbon::now()->subDays(30)->toDateString();
        }

        $employee = Employee::select(
            'employees.id as employee_id',
            'employees.user_id',
            'employees.salary',
            'employees.esi_contribution',
            DB::raw('COUNT(attendances.id) as total_attendance')
        )
            ->leftJoin('attendances', 'employees.id', '=', 'attendances.employee_id')
            ->where('employees.id', $id)
            ->groupBy('employees.id', 'employees.user_id', 'employees.salary', 'employees.esi_contribution')
            ->firstOrFail();

        $attendances = Attendance::where('attendances.employee_id', $id)
            ->whereBetween('attendances.date', [$startDate, $endDate])->get();

        return view('employees.attendance-details', compact('attendances', 'employee', 'startDate', 'endDate'));
    }
}
