@extends('layouts.app')
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Other /</span> Update Requests</h4>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('requests.update-requests') }}">
            <div class="row gx-3 gy-2 align-items-center">

                <div class="col-md-3">
                    <label for="table" class="form-label">Requested For</label>
                    <select id="table" name="table" class="select2 form-select">
                        <option value="">Please Select</option>
                        @foreach(\App\Models\ModificationRequest::$tableNames as $key => $table_name)
                        <option value="{{$key}}" @if(@$_GET['table']==$key) selected @endif>{{$table_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="requestType" class="form-label">Request Type</label>
                    <select id="requestType" name="requestType" class="select2 form-select">
                        <option value="">Please Select</option>
                        @foreach(\App\Models\ModificationRequest::$reuqestType as $k => $type)
                        <option value="{{$k}}" @if(@$_GET['requestType']==$k) selected @endif>{{$type}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="approvedBySecretary" class="form-label">Approved By Secretary</label>
                    <select id="approvedBySecretary" name="approvedBySecretary" class="select2 form-select">
                        <option value="">Please Select</option>
                        <option value="yes" @if(@$_GET['approvedBySecretary']=='yes' ) selected @endif>Yes</option>
                        <option value="no" @if(@$_GET['approvedBySecretary']=='no' ) selected @endif>No</option>
                        <option value="pending" @if(@$_GET['approvedBySecretary']=='pending' ) selected @endif>Pending</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="approvedByPresident" class="form-label">Approved By President</label>
                    <select id="approvedByPresident" name="approvedByPresident" class="select2 form-select">
                        <option value="">Please Select</option>
                        <option value="yes" @if(@$_GET['approvedByPresident']=='yes' ) selected @endif>Yes</option>
                        <option value="no" @if(@$_GET['approvedByPresident']=='no' ) selected @endif>No</option>
                        <option value="pending" @if(@$_GET['approvedByPresident']=='pending' ) selected @endif>Pending</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('requests.update-requests') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Striped Rows -->
<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Requested For</th>
                    <th>Request Type</th>
                    <th>Secretary Approval</th>
                    <th>President Approval</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $i = 1;
                foreach ($allRequests as $request) {
                ?>
                    <tr>
                        <td> {{ $i }} </td>
                        <td> {{ \App\Models\ModificationRequest::$tableNames[$request->table_name] }} </td>
                        <td>
                            @if($request->action == \App\Models\ModificationRequest::REQUEST_TYPE_UPDATE)
                            <span class="badge bg-label-warning me-1">Update</span>
                            @elseif($request->action == \App\Models\ModificationRequest::REQUEST_TYPE_DELETE)
                            <span class="badge bg-label-danger me-1">Delete</span>
                            @endif
                        </td>
                        @php
                        $secretaryApproval = \App\Models\ModificationRequest::PENDING_REQUEST;
                        if($request->approved_by_secretary == true){
                        $secretaryApproval = \App\Models\ModificationRequest::APPROVED_REQUEST;
                        } elseif ($request->approved_by_secretary == false && $request->approved_by_secretary !== NULL) {
                        $secretaryApproval = \App\Models\ModificationRequest::REJECTED_REQUEST;
                        }
                        @endphp
                        <td> {{ $secretaryApproval }} </td>
                        @php
                        $presidentApproval = \App\Models\ModificationRequest::PENDING_REQUEST;
                        if($request->approved_by_president == true){
                        $presidentApproval = \App\Models\ModificationRequest::APPROVED_REQUEST;
                        } elseif ($request->approved_by_president == false && $request->approved_by_president !== NULL) {
                        $presidentApproval = \App\Models\ModificationRequest::REJECTED_REQUEST;
                        }
                        @endphp
                        <td> {{ $presidentApproval }} </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <!-- view -->
                                <a class="pl-3 color-unset" href="{{ route('request.view-update-request', $request->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <!-- delete -->
                                <!-- <form action="{{ route('user.delete-update-request', $request->id) }}" method="POST">
                                    @csrf
                                    <a class="pl-3 delete-request color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </form> -->
                            </div>
                        </td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3 mr-3">
            <!-- Add pagination links -->
            {{ $allRequests->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

@endsection