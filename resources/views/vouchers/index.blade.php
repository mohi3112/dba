@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Vouchers</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('vouchers.add')}}"><i class="bx bx-user me-1"></i> Add Voucher</a>
    </li>
</ul>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach
@endif

<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('vouchers') }}">
            <div class="row gx-3 gy-2 align-items-center">

                <div class="col-md-3">
                    <label for="title" class="form-label">Title</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="title" value="{{@$_GET['title']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="price" class="form-label">Price</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="price" value="{{@$_GET['price']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="startDate" class="form-label">Start Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="startDate" value="{{@$_GET['startDate']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="endDate" class="form-label">End Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="endDate" value="{{@$_GET['endDate']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-check form-switch" style="margin-top: 15%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="showTotal" type="checkbox" id="showTotalAmount" {{ (count($_GET) > 0 && isset($_GET['showTotal'])) ? "checked" : "" }}>
                        <label class="form-check-label" for="showTotalAmount">Show Total Amount</label>
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('vouchers') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Striped Rows -->

<div class="card">
    <div class="table-responsive text-nowrap">
        @if($totalPrice > 0)
        <div class="m-3 d-flex justify-content-end" style="color: #5F61E6;"> Total Amount: ₹{{ number_format($totalPrice, 2) }} </div>
        @endif
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Serial Number</th>
                    <th>Title</th>
                    <th>Amount</th>
                    <th>Voucher Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($vouchers as $voucher)
                <tr>
                    <td> {{ $i }} </td>
                    <td> E-{{ $voucher->id }} </td>
                    <td> {{ $voucher->title }} </td>
                    <td>₹{{ $voucher->price }} </td>
                    <td>{{ \Carbon\Carbon::parse($voucher->date)->format('d-M-Y') }} </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- edit -->
                            <a class="color-unset" href="{{ route('vouchers.edit', $voucher->id) }}"><i class="fas fa-edit"></i></a>
                            <!-- view -->
                            <a class="pl-3 color-unset" data-bs-toggle="modal" data-bs-target="#modalCenter{{$voucher->id}}" href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <div class="modal fade" id="modalCenter{{$voucher->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Voucher details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="lawyerName" class="form-label">Serial Number:</label>
                                                </div>
                                                <div class="col-md-7">
                                                E-{{ $voucher->id }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="lawyerName" class="form-label">Title:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $voucher->title }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Amount:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    ₹{{ $voucher->price }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="voucherDate" class="form-label">Voucher Date:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ \Carbon\Carbon::parse($voucher->date)->format('d-M-Y') }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="issued_to" class="form-label">Issued To:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ ($voucher->issued_to) ? $activeLawyers[$voucher->issued_to] : '--' }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="issued_by" class="form-label">Issued By:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ ($voucher->issued_by) ? $activeLawyers[$voucher->issued_by] : '--' }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="description" class="form-label">Description:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $voucher->description ?? '--' }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="description" class="form-label">Uploaded Scanned Voucher:</label>
                                                </div>
                                                @if($voucher->image)
                                                <div class="col-md-7">
                                                    <div class="d-flex pt-1 pb-3">
                                                        <div class="">
                                                            <a href="data:image/jpeg;base64,{!! $voucher->image !!}" download="receipt.jpg"><span type="button" class="badge bg-label-dark">Uploaded Receipt</span></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- delete -->
                            <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST">
                                @csrf
                                <a class="pl-3 delete-voucher color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </form>

                            <!-- print -->
                            <a href="{{ route('vouchers.receipt', $voucher->id) }}" target="_blank" class="pl-3 color-unset" title="Print">
                                <i class='fa fa-print'></i>
                            </a>

                            <!-- Hidden File Input -->
                            <a class="pl-3 color-unset" data-bs-toggle="modal" data-bs-target="#modalDocumentUpload{{$voucher->id}}" title="Upload" href="#">
                                <i class="fa fa-upload" aria-hidden="true"></i>
                            </a>

                            <div class="modal fade" id="modalDocumentUpload{{$voucher->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalDocumentTitle">Upload Scanned Voucher</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('vouchers.upload', $voucher->id) }}" method="POST" id="upload-form-{{ $voucher->id }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label for="voucherTitle" class="form-label">Title:</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ $voucher->title }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label for="voucherAmount" class="form-label">Amount:</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        ₹{{ $voucher->price }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label for="voucherDate" class="form-label">Voucher Date:</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ \Carbon\Carbon::parse($voucher->date)->format('d-M-Y') }}
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label for="issued_to" class="form-label">Issued To:</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ ($voucher->issued_to) ? $activeLawyers[$voucher->issued_to] : '--' }}
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label for="issued_by" class="form-label">Issued By:</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ ($voucher->issued_by) ? $activeLawyers[$voucher->issued_by] : '--' }}
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label for="description" class="form-label">Description:</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        {{ $voucher->description ?? '--' }}
                                                    </div>
                                                </div>
                                                <!-- File Upload Section -->
                                                <div class="row mt-3">
                                                    <div class="col-md-5">
                                                        <label for="image" class="form-label">Upload Scanned Voucher:</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <input type="file" class="form-control" name="image" accept="application/pdf,image/*" id="image_{{ $voucher->id }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <button type="submit" class="btn btn-primary">Upload</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3 mr-3">
            {{ $vouchers->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-voucher'
        document.querySelectorAll('.delete-voucher').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Show the confirmation dialog
                if (confirm('Are you sure you want to delete this record?')) {
                    // If the user confirms, submit the nearest form
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection