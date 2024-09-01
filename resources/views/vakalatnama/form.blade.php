@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Vakalatnama /</span> Get Vakalatnama</h4>

<form method="POST" action="{{ route('vakalatnama.generate-vakalatnama') }}" id="formVakalatnama">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Vakalatnama Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="lawyer" class="form-label">Lawyer <span class="text-danger">*</span></label>
                            <select id="lawyer" name="user_id" class="form-control form-select user-select @error('user_id') is-invalid @enderror">
                                <option></option>
                                @foreach($activeLawyers as $lawyerId => $lawyerName)
                                <option value=" {{$lawyerId}}" @if(old('user_id')==$lawyerId) selected @endif>{{$lawyerName}}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="unique_id"> Unique Id <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" name="unique_id" readonly value="{{ $uniqueId }}">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="showToastPlacement">&nbsp;</label>
                            <div class="form-check">
                                <input class="form-check-input" id="bulkIssue" type="checkbox" value="1" name="bulk_issue">
                                <label class="form-check-label" for="bulk_issue"> Bulk Issue </label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6 d-none" id="numberOfBulkIssue">
                            <label class="form-label" for="number_of_issue_vakalatnamas"> Number of Vakalatnama issue <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="number" min="1" name="number_of_issue_vakalatnamas">
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Issue</button>
                        <a type="reset" href="{{route('vakalatnamas')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.user-select').select2({
            placeholder: 'Please select',
            allowClear: true
        });

        document.getElementById('bulkIssue').addEventListener('change', function() {
            const numberOfBulkIssue = document.getElementById('numberOfBulkIssue');
            if (this.checked) {
                numberOfBulkIssue.classList.remove('d-none');
            } else {
                numberOfBulkIssue.classList.add('d-none');
            }
        });
    });
</script>
@endSection