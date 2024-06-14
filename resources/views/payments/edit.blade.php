@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Payments /</span> Edit Payment</h4>
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach
@endif
<form method="POST" action="{{ route('payments.update', $payment->id) }}" id="formPayment" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Payment Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="lawyer" class="form-label">Lawyer <span class="text-danger">*</span></label>
                            <select id="lawyer" name="user_id" class="select2 form-select">
                                <option value="">Select Lawyer</option>
                                @foreach($activeLawyers as $lawyerId => $lawyerName)
                                <option value="{{$lawyerId}}" {{ $payment->user_id == $lawyerId ? 'selected' : '' }}>{{$lawyerName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="payment_amount" class="form-label">Amount</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" value="{{$payment->payment_amount}}" name="payment_amount" placeholder="Payment Amount">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="payment_date">Payment Date <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="date" value="{{$payment->payment_date}}" name="payment_date" value="" id="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="image" class="form-label">Payment Proof</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>

                            @if($payment->payment_proof)
                            <div class="demo-inline-spacing">
                                <span type="button" class="pl-2 badge bg-label-dark" data-bs-toggle="modal" data-bs-target="#paymentProofModal">Uploaded Document</span>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="paymentProofModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <img src="data:image/jpeg;base64,{{ $payment->payment_proof }}" alt="Description of Image" style="max-width: 750px;">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="button" parent-btn="#paymentProofModal" data-url="delete-payment-image" data-image-id="{{ $payment->id }}" class="btn btn-danger delete-image">Delete Image</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            @endif
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('payments')}}" class="btn btn-outline-secondary">Cancel</a>
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
        $('.delete-image').click(function() {
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
    });
</script>
@endsection