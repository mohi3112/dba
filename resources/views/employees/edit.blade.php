@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Employees /</span> Edit Employee</h4>
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach
@endif
<form method="POST" action="{{ route('employee.update', $employee->id) }}" id="formEmployee">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="card mb-4">
            <h5 class="card-header">Employee Details</h5>
            <hr class="my-0">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" placeholder="Name" name="name" value="{{ $employee->name }}" autofocus="">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="dob" class="form-label">Date of Birth </label>
                        <input class="form-control @error('dob') is-invalid @enderror" type="date" id="dob" placeholder="Date of Birth" name="dob" value="{{ $employee->dob }}" autofocus="">
                        @error('dob')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                        <select id="gender" name="gender" class="select2 form-select @error('gender') is-invalid @enderror">
                            <option value="">Select Gender</option>
                            @foreach(\App\Models\Employee::$employeesGender as $genderId => $genderName)
                            <option value="{{$genderId}}" {{ $employee->gender == $genderId ? 'selected' : ''}}>{{$genderName}}</option>
                            @endforeach
                        </select>
                        @error('gender')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" type="text" id="email" placeholder="Email" name="email" value="{{ $employee->email }}" autofocus="">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input class="form-control @error('phone') is-invalid @enderror" maxlength="10" type="text" id="phone" placeholder="Phone" name="phone" value="{{ $employee->phone }}" autofocus="">
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="position" class="form-label">Position</label>
                        <input class="form-control @error('position') is-invalid @enderror" type="text" id="position" placeholder="Position" name="position" value="{{ $employee->position }}" autofocus="">
                        @error('position')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="salary" class="form-label">Salary</label>
                        <input class="form-control @error('salary') is-invalid @enderror" type="text" id="salary" placeholder="Salary" name="salary" value="{{ $employee->salary }}" autofocus="">
                        @error('salary')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="divider divider-primary">
                    <div class="divider-text">Bank Details</div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="bank_account_number" class="form-label">Bank Account Number</label>
                        <input class="form-control @error('bank_account_number') is-invalid @enderror" type="text" id="bank_account_number" placeholder="Bank Account Number" name="bank_account_number" value="{{ $employee->bank_account_number }}" autofocus="">
                        @error('bank_account_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="bank_ifsc_code" class="form-label">IFSC Code</label>
                        <input class="form-control @error('bank_ifsc_code') is-invalid @enderror" type="text" id="bank_ifsc_code" placeholder="IFSC Code" name="bank_ifsc_code" value="{{ $employee->bank_ifsc_code }}" autofocus="">
                        @error('bank_ifsc_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="account_holder_name" class="form-label">Account Holder Name</label>
                        <input class="form-control @error('account_holder_name') is-invalid @enderror" type="text" id="account_holder_name" placeholder="Account Holder Name" name="account_holder_name" value="{{ $employee->account_holder_name }}" autofocus="">
                        @error('account_holder_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="branch_name" class="form-label">Branch Name</label>
                        <input class="form-control @error('branch_name') is-invalid @enderror" type="text" id="branch_name" placeholder="Branch Name" name="branch_name" value="{{ $employee->branch_name }}" autofocus="">
                        @error('branch_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="divider divider-primary">
                    <div class="divider-text">Other Details</div>
                </div>
                <div class="row mb-3">
                    <div class="mb-3 col-md-6">
                        <label for="esi_number" class="form-label">ESI Number</label>
                        <input class="form-control @error('esi_number') is-invalid @enderror" type="text" id="esi_number" placeholder="ESI Number" name="esi_number" value="{{ $employee->esi_number }}" autofocus="">
                        @error('esi_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="esi_contribution" class="form-label">ESI Contribution</label>
                        <input class="form-control @error('esi_contribution') is-invalid @enderror" type="text" id="esi_contribution" placeholder="ESI Contribution Amount" name="esi_contribution" value="{{ $employee->esi_contribution }}" autofocus="">
                        @error('esi_contribution')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="esi_start_date" class="form-label">ESI Start Date</label>
                        <input class="form-control @error('esi_start_date') is-invalid @enderror" type="date" id="esi_start_date" name="esi_start_date" value="{{ $employee->esi_start_date }}" autofocus="">
                        @error('esi_start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="esi_end_date" class="form-label">ESI End Date</label>
                        <input class="form-control @error('esi_end_date') is-invalid @enderror" type="date" id="esi_end_date" name="esi_end_date" value="{{ $employee->esi_end_date }}" autofocus="">
                        @error('esi_end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="card-header pl-0">Policies Details</h5>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-primary" id="add-row">Add Row</button>
                    </div>
                </div>
                @if($employee->policies)
                <div class="card-body pt-0 pl-0 pr-0">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Policy Name</th>
                                    <th>Policy Number</th>
                                    <th>Issue Date</th>
                                    <th>Expiry Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @php
                                $i = 1;
                                $policies = json_decode($employee->policies, true);
                                @endphp
                                @foreach($policies as $policy)
                                <tr id="row-{{$i}}">
                                    <td> {{ $i }} </td>
                                    <td> {{ $policy['policy_name'] }} </td>
                                    <td> {{ $policy['policy_number'] }} </td>
                                    <td>{{ ($policy['policy_issue_date']) ? \Carbon\Carbon::parse($policy['policy_issue_date'])->format('d-M-Y') : '--' }} </td>
                                    <td>{{ ($policy['policy_expiry_date']) ? \Carbon\Carbon::parse($policy['policy_expiry_date'])->format('d-M-Y') : '--' }} </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <!-- delete -->
                                            <a onclick="confirmDelete({{ $employee->id }}, {{ $i }})" class="btn pl-3 delete-policy-record color-unset" href="javascript:void(0);">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @php($i++)
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                <div class="card-body pt-0 pl-0" id="policy-section">
                    <div class="row policy-row">
                        <div class="mb-3 col-md-3">
                            <label for="policy_name" class="form-label">Policy Name</label>
                            <input class="form-control @error('policy_name') is-invalid @enderror" type="text" id="policy_name" placeholder="Policy Name" name="policy_name[]" autofocus="">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="policy_number">Policy Number</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" placeholder="Policy Number" name="policy_number[]">
                            </div>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label class="form-label" for="policy_issue_date">Issue Date</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="date" placeholder="Issue Date" name="policy_issue_date[]">
                            </div>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label class="form-label" for="policy_expiry_date">Expiry Date</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="date" placeholder="Expiry Date" name="policy_expiry_date[]">
                            </div>
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="showToastPlacement" class="form-label">&nbsp;</label>
                            <div class="input-group input-group-merge">
                                <button class="btn btn-danger ml-2 delete-row">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2">Save changes</button>
                    <a type="reset" href="{{route('employees')}}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@section('scripts')
<script>
    function confirmDelete(recordId, index) {
        const userConfirmed = confirm("Are you sure to delete this policy record?");
        if (userConfirmed) {
            $.ajax({
                url: `/employee-policy/${recordId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token
                    policyRecordIndex: index
                },
                success: function(response) {
                    console.log(response.success);

                    if (response.success) {
                        console.log(`#row-${index}`);
                        
                        // Remove the row from the table or update the UI accordingly
                        $(`#row-${index}`).remove();
                    } else {
                        alert('Failed to delete the policy record. Please try again later.');
                    }
                },
                error: function(xhr) {
                    alert('Something went wrong. Please try again later.');
                }
            });
        }
    }
    $(document).ready(function() {

        // add row policy on clicking the button
        document.getElementById('add-row').addEventListener('click', function() {
            let newRow = document.querySelector('.policy-row').cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            document.getElementById('policy-section').appendChild(newRow);
        });

        // delete row
        document.getElementById('policy-section').addEventListener('click', function(event) {
            event.preventDefault();
            if (event.target.classList.contains('delete-row')) {
                let policyRows = document.querySelectorAll('.policy-row');
                if (policyRows.length > 1) {
                    var confirmationForDelete = confirm('Are you sure you want to delete this row?');
                    if (confirmationForDelete) {
                        event.target.closest('.policy-row').remove();
                    }
                }
            }
        });
    });
</script>
@endsection