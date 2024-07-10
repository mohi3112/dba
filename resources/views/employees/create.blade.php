@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Employees /</span> Add Employee</h4>
<form method="POST" action="{{ route('employee.store') }}" id="formEmployee">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Employee Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" placeholder="Name" name="name" value="{{ old('name') }}" autofocus="">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="dob" class="form-label">Date of Birth </label>
                            <input class="form-control @error('dob') is-invalid @enderror" type="date" id="dob" placeholder="Date of Birth" name="dob" value="{{ old('dob') }}" autofocus="">
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
                                <option value="{{$genderId}}" {{ old('gender') == $genderId ? 'selected' : ''}}>{{$genderName}}</option>
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
                            <input class="form-control @error('email') is-invalid @enderror" type="text" id="email" placeholder="Email" name="email" value="{{ old('email') }}" autofocus="">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input class="form-control @error('phone') is-invalid @enderror" maxlength="10" type="text" id="phone" placeholder="Phone" name="phone" value="{{ old('phone') }}" autofocus="">
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="position" class="form-label">Position</label>
                            <input class="form-control @error('position') is-invalid @enderror" type="text" id="position" placeholder="Position" name="position" value="{{ old('position') }}" autofocus="">
                            @error('position')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="salary" class="form-label">Salary</label>
                            <input class="form-control @error('salary') is-invalid @enderror" type="text" id="salary" placeholder="Salary" name="salary" value="{{ old('salary') }}" autofocus="">
                            @error('salary')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('employees')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection