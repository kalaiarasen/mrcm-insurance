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
                                    <strong>Quoted Price:</strong><br>
                                    RM <span id="quotedPrice">{{ number_format($quotation->quoted_price, 2) }}</span>
                                </div>

                                <!-- Wallet Balance Info -->
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-dark"><i class="fa fa-wallet"></i> Your Wallet Balance:</span>
                                            <strong class="text-primary">RM
                                                {{ number_format(auth()->user()->wallet_amount ?? 0, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('customer.quotations.upload-payment', $quotation->id) }}" method="POST"
                                enctype="multipart/form-data" id="paymentForm">
                                @csrf

                                <!-- Wallet Amount Input -->
                                @if ($quotation->quoted_price && auth()->user()->wallet_amount > 0)
                                    <div class="mb-3">
                                        <label for="wallet_amount" class="form-label">Apply Wallet Balance</label>
                                        <div class="input-group">
                                            <span class="input-group-text">RM</span>
                                            <input type="number" step="0.01" min="0"
                                                max="{{ min(auth()->user()->wallet_amount, $quotation->quoted_price) }}"
                                                class="form-control @error('wallet_amount') is-invalid @enderror"
                                                id="wallet_amount" name="wallet_amount" value="0" placeholder="0.00">
                                        </div>
                                        <small class="text-muted">Maximum: RM
                                            {{ number_format(min(auth()->user()->wallet_amount, $quotation->quoted_price), 2) }}</small>
                                        @error('wallet_amount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Final Amount Display -->
                                    <div class="alert alert-success mb-3">
                                        <strong>Final Amount to Pay:</strong><br>
                                        RM <span id="finalAmount">{{ number_format($quotation->quoted_price, 2) }}</span>
                                    </div>
                                @endif

                                <!-- Bank Payment Details -->
                                <div class="card bg-light border-primary mb-3">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3">
                                            <i class="fa fa-university"></i> Payment Details
                                        </h6>
                                        <div class="mb-2">
                                            <small class="text-muted">Bank Name:</small>
                                            <div class="text-dark"><strong>Public Bank</strong></div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Account Number:</small>
                                            <div class="text-dark"><strong>3231145024</strong></div>
                                        </div>
                                        <div class="mb-0">
                                            <small class="text-muted">Account Name:</small>
                                            <div class="text-dark"><strong>MRCM Services (M) Sdn Bhd</strong></div>
                                        </div>
                                    </div>
                                </div>

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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const walletInput = document.getElementById('wallet_amount');
            const finalAmountSpan = document.getElementById('finalAmount');
            const quotedPriceSpan = document.getElementById('quotedPrice');

            if (walletInput && finalAmountSpan && quotedPriceSpan) {
                const quotedPrice = parseFloat(quotedPriceSpan.textContent.replace(/,/g, ''));

                walletInput.addEventListener('input', function() {
                    let walletAmount = parseFloat(this.value) || 0;
                    const maxWallet = parseFloat(this.max);

                    // Ensure wallet amount doesn't exceed max
                    if (walletAmount > maxWallet) {
                        walletAmount = maxWallet;
                        this.value = maxWallet.toFixed(2);
                    }

                    // Calculate final amount
                    const finalAmount = Math.max(0, quotedPrice - walletAmount);

                    // Update display
                    finalAmountSpan.textContent = finalAmount.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                });
            }
        });
    </script>
@endsection
