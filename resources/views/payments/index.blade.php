@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Payments</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('payments.add')}}"><i class="bx bx-user me-1"></i> Add Payment</a>
    </li>
</ul>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<!-- Striped Rows -->

<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Lawyer Name</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Payment Proof</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($payments as $payment)
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $activeLawyers[$payment->user_id] }} </td>
                    <td>₹{{ $payment->payment_amount }} </td>
                    <td> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }} </td>
                    <td><span type="button" data-bs-toggle="modal" data-bs-target="#paymentProofModal{{$payment->id}}">View</span></td>
                    <!-- Modal -->
                    <div class="modal fade" id="paymentProofModal{{$payment->id}}" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <img src="data:image/jpeg;base64,{{ $payment->payment_proof }}" alt="Payment proof of Image" style="max-width: 750px;">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="button" parent-btn="#paymentProofModal{{$payment->id}}" data-url="delete-payment-image" data-image-id="{{ $payment->id }}" class="btn btn-danger delete-image">Delete Image</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- edit -->
                            <a class="color-unset" href="{{ route('payments.edit', $payment->id) }}"><i class="fas fa-edit"></i></a>
                            <!-- view -->
                            <a class="pl-3 color-unset" data-bs-toggle="modal" data-bs-target="#modalCenter{{$payment->id}}" href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <div class="modal fade" id="modalCenter{{$payment->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Payment details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="lawyerName" class="form-label">Lawyer Name:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $activeLawyers[$payment->user_id] }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Amount:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    ₹{{ $payment->payment_amount }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="PaymentDate" class="form-label">Payment Date:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="paymentDateAdded" class="form-label">Payment Record Added Date:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ \Carbon\Carbon::parse($payment->created_at)->format('d-M-Y') }}
                                                </div>
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
                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST">
                                @csrf
                                <a class="pl-3 delete-payment color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </form>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3">
            {{ $payments->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

<!-- </div> -->
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-payment'
        document.querySelectorAll('.delete-payment').forEach(function(button) {
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

    $(document).on('click', '.delete-image', function() {
        // Get image id
        let imageId = $(this).data('image-id');
        let url = $(this).data('url');
        let ajaxUrl = '/' + url + '/' + imageId;

        // Get parent button
        var modalButton = $(this).attr('parent-btn');

        // Ask for confirmation
        var confirmation = confirm("Are you sure to delete this image?");

        if (confirmation) {
            $.ajax({
                url: ajaxUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                    $("[data-bs-dismiss='modal']").trigger('click');
                    $('[data-bs-target="' + modalButton + '"]').remove();
                }
            });
        }
    });
</script>
@endsection