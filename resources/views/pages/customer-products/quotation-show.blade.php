@extends('layouts.main')

@section('title', 'Quotation Request Details')

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Quotation Request #{{ $quotation->id }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Quotation Request</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Request Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Product:</strong>
                                <p>{{ $quotation->product->title }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Product Type:</strong>
                                <p><span class="badge bg-info">{{ $quotation->product->type_name }}</span></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Submitted Date:</strong>
                                <p>{{ $quotation->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong>
                                <p><span class="badge {{ $quotation->status_badge }}">{{ $quotation->status_name }}</span>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Your Submitted Information</h6>
                        @if ($quotation->form_data)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        @foreach ($quotation->form_data as $key => $value)
                                            <tr>
                                                <th style="width: 30%">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                <td>{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No form data available.</p>
                        @endif

                        <!-- Quotation Details (if approved) -->
                        @if ($quotation->quoted_price || $quotation->quotation_details)
                            <hr>
                            <h6 class="mb-3 text-success"><i class="fa fa-check-circle"></i> Quotation Provided</h6>

                            @if ($quotation->quoted_price)
                                <div class="alert alert-success">
                                    <h5 class="mb-0">Quoted Price: RM {{ number_format($quotation->quoted_price, 2) }}
                                    </h5>
                                </div>
                            @endif

                            @if ($quotation->quotation_details)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <strong class="text-dark">Quotation Details:</strong>
                                        <p class="mb-0 mt-2 text-dark">{{ $quotation->quotation_details }}</p>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if ($quotation->admin_notes)
                            <hr>
                            <h6 class="mb-3">Notes from Admin</h6>
                            <div class="alert alert-info">
                                {{ $quotation->admin_notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Payment Upload (if status is pay_now) -->
                @if ($quotation->customer_status === 'pay_now' && !$quotation->payment_document)
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fa fa-upload"></i> Upload Payment</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Please upload your payment proof to proceed.</p>

                            @if ($quotation->quoted_price)
                                <div class="alert alert-info mb-3">
                                    <strong>Amount to Pay:</strong><br>
                                    RM {{ number_format($quotation->quoted_price, 2) }}
                                </div>
                            @endif

                            <form action="{{ route('customer.quotations.upload-payment', $quotation->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="payment_document" class="form-label">Payment Proof <span
                                            class="text-danger">*</span></label>
                                    <input type="file"
                                        class="form-control @error('payment_document') is-invalid @enderror"
                                        id="payment_document" name="payment_document" accept=".pdf,.jpg,.jpeg,.png"
                                        required>
                                    <small class="text-muted">Accepted: PDF, JPG, PNG (Max 5MB)</small>
                                    @error('payment_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-upload"></i> Upload Payment Proof
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Payment Status (if paid) -->
                @if ($quotation->payment_document)
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fa fa-check-circle"></i> Payment Submitted</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Uploaded:</strong>
                                {{ $quotation->payment_uploaded_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                            <a href="{{ $quotation->payment_document_url }}" target="_blank"
                                class="btn btn-outline-primary w-100">
                                <i class="fa fa-file"></i> View Payment Proof
                            </a>
                            <p class="text-muted mt-3 mb-0"><small>Your payment is being processed. We will notify you once
                                    it's confirmed.</small></p>
                        </div>
                    </div>
                @endif

                <!-- Actions Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('customer.products.show', $quotation->product_id) }}"
                            class="btn btn-primary w-100 mb-2">
                            <i class="fa fa-arrow-left"></i> Back to Product
                        </a>
                        <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fa fa-list"></i> Browse All Products
                        </a>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Need Help?</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">If you have any questions about your quotation request, please contact us.
                        </p>
                        @if ($quotation->product->notification_email)
                            <p><strong>Email:</strong> {{ $quotation->product->notification_email }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
