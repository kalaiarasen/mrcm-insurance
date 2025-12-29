@extends('layouts.main')

@section('title', 'Policy Application Details')

@section('css')
    <style>
        /* Base Styles - Using CSS Variables for Theme Support */
        .info-card {
            border-left: 3px solid #dee2e6;
            transition: all 0.3s ease;
            background-color: #ffffff;
        }

        .info-card:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            color: var(--body-font-color);
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .info-label {
            font-weight: 600;
            color: var(--body-font-color);
            margin-bottom: 5px;
        }

        .info-value {
            color: var(--body-font-color);
            margin-bottom: 15px;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .signature-box {
            border: 2px dashed var(--recent-border);
            border-radius: 8px;
            padding: 15px;
            background-color: var(--light-background);
            text-align: center;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signature-box img {
            max-width: 100%;
            max-height: 150px;
        }

        .pricing-row {
            padding: 10px 0;
            border-bottom: 1px solid var(--recent-border);
        }

        .pricing-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--theme-default);
        }

        .timeline-item {
            border-left: 2px solid var(--recent-border);
            padding-left: 20px;
            margin-bottom: 15px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--theme-default);
        }

        /* Badge Dark Mode Support */
        body.dark-only .badge.bg-success {
            background-color: #198754 !important;
            color: #ffffff !important;
        }

        body.dark-only .badge.bg-danger {
            background-color: #dc3545 !important;
            color: #ffffff !important;
        }

        body.dark-only .badge.bg-primary {
            background-color: #0d6efd !important;
            color: #ffffff !important;
        }

        body.dark-only .badge.bg-info {
            background-color: #0dcaf0 !important;
            color: #000000 !important;
        }

        body.dark-only .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000000 !important;
        }

        body.dark-only .badge.bg-secondary {
            background-color: #6c757d !important;
            color: #ffffff !important;
        }

        body.dark-only .badge.bg-light {
            background-color: #495057 !important;
            color: #ffffff !important;
        }

        .badge-yes {
            background-color: #198754 !important;
            color: #fff !important;
        }

        .badge-no {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        /* Dark Mode Specific Overrides */
        body.dark-only .info-card,
        body.dark-sidebar .info-card {
            background-color: var(--card-color);
            border-left-color: #495057;
        }

        body.dark-only .section-title,
        body.dark-sidebar .section-title {
            color: var(--body-font-color);
            border-bottom-color: #495057;
        }

        body.dark-only .info-label,
        body.dark-sidebar .info-label {
            color: var(--body-font-color);
            opacity: 0.9;
        }

        body.dark-only .info-value,
        body.dark-sidebar .info-value {
            color: var(--body-font-color);
        }

        body.dark-only .signature-box,
        body.dark-sidebar .signature-box {
            background-color: rgba(255, 255, 255, 0.03);
            border-color: var(--recent-border);
        }

        body.dark-only .pricing-row,
        body.dark-sidebar .pricing-row {
            border-bottom-color: var(--recent-border);
        }

        body.dark-only .card.bg-light,
        body.dark-sidebar .card.bg-light {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: var(--recent-border);
        }

        body.dark-only .text-muted,
        body.dark-sidebar .text-muted {
            color: var(--light-font) !important;
            opacity: 0.7;
        }

        body.dark-only .text-secondary,
        body.dark-sidebar .text-secondary {
            color: var(--light-font) !important;
        }

        body.dark-only .text-primary,
        body.dark-sidebar .text-primary,
        body.dark-only h6.text-primary,
        body.dark-sidebar h6.text-primary {
            color: var(--theme-default) !important;
        }

        body.dark-only h4,
        body.dark-only h5,
        body.dark-only h6,
        body.dark-sidebar h4,
        body.dark-sidebar h5,
        body.dark-sidebar h6 {
            color: var(--body-font-color);
        }

        body.dark-only p,
        body.dark-sidebar p {
            color: var(--body-font-color);
        }

        body.dark-only .breadcrumb-item,
        body.dark-sidebar .breadcrumb-item,
        body.dark-only .breadcrumb-item.active,
        body.dark-sidebar .breadcrumb-item.active {
            color: var(--light-font);
        }

        /* Print Styles */
        @media print {

            .print-button,
            .breadcrumb,
            .no-print,
            .page-title {
                display: none !important;
            }

            /* Force light mode for printing */
            body,
            .info-card,
            .card {
                background-color: #fff !important;
                color: #000 !important;
            }

            .info-label,
            .info-value,
            h4,
            h5,
            h6,
            p {
                color: #000 !important;
            }

            .info-card {
                border-left-color: #0d6efd !important;
                page-break-inside: avoid;
            }

            .section-title {
                color: #0d6efd !important;
                border-bottom-color: #0d6efd !important;
            }

            .signature-box {
                border-color: #dee2e6 !important;
                background-color: #f8f9fa !important;
            }

            .card.bg-light {
                background-color: #f8f9fa !important;
            }

            .badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Equal Height Cards - Flexbox Solution */
        @media (min-width: 768px) {
            .row>.col-md-6 {
                display: flex;
                flex-direction: column;
            }

            .row>.col-md-6>.info-card {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .row>.col-md-6>.info-card>.card-body {
                flex: 1;
            }
        }
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Policy Application Details</h3>
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('for-your-action') }}">For Your Action</a>
                        </li>
                        <li class="breadcrumb-item active">Application Details</li>
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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i>
                <strong>Validation Errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Header Card -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">{{ ucfirst($policyApplication->user->applicantProfile->title) }}. {{ $policyApplication->user->name ?? 'N/A' }}</h4>
                                <p class="mb-1"><i
                                        class="fa fa-envelope me-2"></i>{{ $policyApplication->user->email ?? 'N/A' }}</p>
                                <p class="mb-1"><i
                                        class="fa fa-phone me-2"></i>{{ $policyApplication->user->contact_no ?? 'N/A' }}</p>
                                @if ($policyApplication->reference_number)
                                    <p class="mb-0"><i class="fa fa-file-alt me-2"></i><strong>Reference:</strong>
                                        {{ $policyApplication->reference_number }}</p>
                                @endif
                            </div>
                            <div class="col-md-4 text-end">
                                @if ($policyApplication->status === 'approved')
                                    <span class="status-badge badge bg-success">Approved</span>
                                @elseif($policyApplication->status === 'submitted')
                                    <span class="status-badge badge bg-info">Submitted</span>
                                @elseif($policyApplication->status === 'rejected')
                                    <span class="status-badge badge bg-danger">Rejected</span>
                                @else
                                    <span
                                        class="status-badge badge bg-secondary">{{ str_replace('_', ' ', ucwords($policyApplication->status)) }}</span>
                                @endif
                                <p class="mt-3 mb-0 text-muted">
                                    <small>Submitted:
                                        {{ $policyApplication->submitted_at ? $policyApplication->submitted_at->format('d M Y, h:i A') : 'N/A' }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Upload Section - Show if pay_now status or payment data exists -->
            @if ($policyApplication->customer_status === 'pay_now')
                <div class="col-12">
                    <div class="card border-info mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fa fa-credit-card me-2"></i>Payment Management (Admin)</h5>
                        </div>
                        <div class="card-body">
                            <!-- Policy Status Display -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-3"><i class="fa fa-info-circle me-2"></i>Policy Status</h6>
                                            <p class="mb-2"><strong>Reference Number:</strong>
                                                {{ $policyApplication->reference_number ?? 'N/A' }}</p>
                                            <p class="mb-2">
                                                <strong>Current Status:</strong>
                                                @php
                                                    $customerStatusBadges = [
                                                        'submitted' => ['bg-secondary', 'Submitted'],
                                                        'pay_now' => ['bg-warning text-dark', 'Payment Required'],
                                                        'paid' => ['bg-info', 'Payment Received'],
                                                        'processing' => ['bg-primary', 'Processing'],
                                                        'active' => ['bg-success', 'Active'],
                                                    ];
                                                    $cs = $customerStatusBadges[
                                                        $policyApplication->customer_status
                                                    ] ?? ['bg-secondary', ucfirst($policyApplication->customer_status)];
                                                @endphp
                                                <span class="badge {{ $cs[0] }}">{{ $cs[1] }}</span>
                                            </p>
                                            <p class="mb-0"><strong>Total Amount Due:</strong> <span
                                                    class="text-success fs-5">RM
                                                    {{ number_format($policyApplication->policyPricing->total_payable ?? 0, 2) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-3"><i class="fa fa-file-invoice me-2"></i>Admin Instructions
                                            </h6>
                                            <p class="mb-2">1. Upload payment proof on behalf of client</p>
                                            <p class="mb-2">2. Or enter credit card payment information</p>
                                            <p class="mb-0">3. Payment status will be updated to 'Paid' automatically</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing Breakdown -->
                            @php
                                $pricing = $policyApplication->policyPricing;
                            @endphp
                            @if ($pricing)
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card bg-light border-primary">
                                            <div class="card-body">
                                                <h6 class="text-center mb-3"><i
                                                        class="fa fa-dollar-sign me-2"></i><strong>Pricing
                                                        Breakdown</strong></h6>

                                                <div class="pricing-row d-flex justify-content-between info-label">
                                                    <span>Premium Per Annum</span>
                                                    <span>RM {{ number_format($pricing->base_premium ?? 0, 2) }}</span>
                                                </div>

                                                @if (($pricing->loading_amount ?? 0) > 0)
                                                    <div class="pricing-row d-flex justify-content-between info-label">
                                                        <span>Loading
                                                            ({{ number_format($pricing->loading_percentage ?? 0, 2) }}%)</span>
                                                        <span>RM {{ number_format($pricing->loading_amount, 2) }}</span>
                                                    </div>
                                                @endif

                                                <div class="pricing-row d-flex justify-content-between info-label">
                                                    <span>Gross Premium</span>
                                                    <span>RM {{ number_format($pricing->gross_premium ?? 0, 2) }}</span>
                                                </div>

                                                @if ($pricing->locum_addon > 0)
                                                    <div class="pricing-row d-flex justify-content-between info-label">
                                                        <span>Locum Extension</span>
                                                        <span>RM {{ number_format($pricing->locum_addon, 2) }}</span>
                                                    </div>
                                                @endif

                                                <div class="pricing-row d-flex justify-content-between info-label">
                                                    <span>8% SST</span>
                                                    <span>RM {{ number_format($pricing->sst ?? 0, 2) }}</span>
                                                </div>

                                                <div class="pricing-row d-flex justify-content-between info-label">
                                                    <span>Stamp Duty</span>
                                                    <span>RM {{ number_format($pricing->stamp_duty ?? 10, 2) }}</span>
                                                </div>

                                                @if (($pricing->wallet_used ?? 0) > 0)
                                                    <div
                                                        class="pricing-row d-flex justify-content-between info-label text-success">
                                                        <span>Wallet Amount Used</span>
                                                        <span>- RM {{ number_format($pricing->wallet_used, 2) }}</span>
                                                    </div>
                                                @endif

                                                <div class="pricing-row d-flex justify-content-between info-label">
                                                    <span><strong>Total Payable</strong></span>
                                                    <span class="text-success"><strong>RM
                                                            {{ number_format($pricing->total_payable ?? 0, 2) }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body border-dark">
                                            <h6 class="mb-3"><i class="fa fa-coins me-2"></i>Select Payment Method</h6>
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check" name="payment_method_admin"
                                                    id="payment_proof_method_admin" value="proof" checked>
                                                <label class="btn btn-outline-primary" for="payment_proof_method_admin">
                                                    <i class="fa fa-file-upload me-2"></i>Upload Payment Proof
                                                </label>

                                                <input type="radio" class="btn-check" name="payment_method_admin"
                                                    id="credit_card_method_admin" value="credit_card">
                                                <label class="btn btn-outline-primary" for="credit_card_method_admin">
                                                    <i class="fa fa-credit-card me-2"></i>Credit Card Payment
                                                </label>
                                            </div>
                                            <form action="{{ route('for-your-action.upload-payment', $policyApplication->id) }}"
                                                method="POST" enctype="multipart/form-data" id="paymentUploadFormAdmin">
                                                @csrf
                                                <input type="hidden" name="payment_type" id="payment_type_admin" value="proof">

                                                <!-- Payment Proof Upload Section -->
                                                <div id="proofPaymentSectionAdmin" class="payment-section">
                                                    <!-- Bank Account Details -->
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <div class="alert alert-info">
                                                                <h6 class="mb-2"><i class="fa fa-university me-2"></i>Bank Account
                                                                    Details for Payment:</h6>
                                                                <p class="mb-1"><strong>Great Eastern General Insurance (Malaysia)
                                                                        BHD</strong></p>
                                                                <p class="mb-1"><strong>Branch:</strong> OCBC bank Malaysia</p>
                                                                <p class="mb-0"><strong>A/C No:</strong> 7041102530</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <label for="payment_document_admin" class="form-label fw-bold">
                                                                <i class="fa fa-upload me-2"></i>Upload payment proof: <span
                                                                    class="text-danger">*</span>
                                                            </label>
                                                            <input type="file" class="form-control" id="payment_document_admin"
                                                                name="payment_document" accept=".pdf,.jpg,.jpeg,.png" required>
                                                            <small class="text-muted">Accepted formats: PDF, JPG, JPEG, PNG (Max:
                                                                5MB)</small>
                                                            @if ($policyApplication->payment_document)
                                                                <div class="mt-2">
                                                                    <small class="text-success">
                                                                        <i class="fa fa-check-circle me-1"></i>Current payment document:
                                                                        <a href="{{ asset('storage/' . $policyApplication->payment_document) }}"
                                                                            target="_blank">View Document</a>
                                                                    </small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12 text-end">
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fa fa-check me-2"></i>Submit Payment Proof
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Credit Card Payment Section -->
                                                <div id="creditCardSectionAdmin" class="payment-section d-none">
                                                    <!-- Notice -->
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <div class="alert alert-danger">
                                                                <p class="mb-0"><strong>Payment will be charged by Great Eastern after
                                                                        receiving this form.</strong></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Credit Card Fields -->
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="name_on_card_admin" class="form-label">Name On Card</label>
                                                            <input type="text" class="form-control" id="name_on_card_admin"
                                                                name="name_on_card" placeholder="Name On Card"
                                                                value="{{ $policyApplication->name_on_card ?? '' }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="nric_no_admin" class="form-label">NRIC NO</label>
                                                            <input type="text" class="form-control" id="nric_no_admin" name="nric_no"
                                                                placeholder="NRIC NO" value="{{ $policyApplication->nric_no ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="card_no_admin" class="form-label">Card No</label>
                                                            <input type="text" class="form-control" id="card_no_admin" name="card_no"
                                                                placeholder="Card No" value="{{ $policyApplication->card_no ?? '' }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="card_issuing_bank_admin" class="form-label">Card Issuing
                                                                Bank</label>
                                                            <input type="text" class="form-control" id="card_issuing_bank_admin"
                                                                name="card_issuing_bank" placeholder="Card Issuing Bank"
                                                                value="{{ $policyApplication->card_issuing_bank ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <!-- Card Type -->
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="visa_card_admin"
                                                                    name="card_type[]" value="visa"
                                                                    {{ is_array($policyApplication->card_type) && in_array('visa', $policyApplication->card_type) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="visa_card_admin">Visa Card</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="master_card_admin"
                                                                    name="card_type[]" value="master"
                                                                    {{ is_array($policyApplication->card_type) && in_array('master', $policyApplication->card_type) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="master_card_admin">Master
                                                                    card</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Expiry Date -->
                                                    <div class="row mb-3">
                                                        <div class="col-md-4">
                                                            <label for="expiry_month_admin" class="form-label">Month</label>
                                                            <select class="form-select" id="expiry_month_admin" name="expiry_month">
                                                                <option value="">Month</option>
                                                                <option value="01"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '01' ? 'selected' : '' }}>
                                                                    01 - January</option>
                                                                <option value="02"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '02' ? 'selected' : '' }}>
                                                                    02 - February</option>
                                                                <option value="03"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '03' ? 'selected' : '' }}>
                                                                    03 - March</option>
                                                                <option value="04"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '04' ? 'selected' : '' }}>
                                                                    04 - April</option>
                                                                <option value="05"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '05' ? 'selected' : '' }}>
                                                                    05 - May</option>
                                                                <option value="06"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '06' ? 'selected' : '' }}>
                                                                    06 - June</option>
                                                                <option value="07"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '07' ? 'selected' : '' }}>
                                                                    07 - July</option>
                                                                <option value="08"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '08' ? 'selected' : '' }}>
                                                                    08 - August</option>
                                                                <option value="09"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '09' ? 'selected' : '' }}>
                                                                    09 - September</option>
                                                                <option value="10"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '10' ? 'selected' : '' }}>
                                                                    10 - October</option>
                                                                <option value="11"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '11' ? 'selected' : '' }}>
                                                                    11 - November</option>
                                                                <option value="12"
                                                                    {{ ($policyApplication->expiry_month ?? '') == '12' ? 'selected' : '' }}>
                                                                    12 - December</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="expiry_year_admin" class="form-label">Year</label>
                                                            <select class="form-select" id="expiry_year_admin" name="expiry_year">
                                                                <option value="">Year</option>
                                                                @for ($year = date('Y'); $year <= date('Y') + 20; $year++)
                                                                    <option value="{{ $year }}"
                                                                        {{ ($policyApplication->expiry_year ?? '') == $year ? 'selected' : '' }}>
                                                                        {{ $year }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Expiry</label>
                                                            <input type="text" class="form-control" readonly placeholder="Expiry"
                                                                id="expiry_display_admin"
                                                                value="{{ $policyApplication->expiry_month && $policyApplication->expiry_year ? $policyApplication->expiry_month . '/' . $policyApplication->expiry_year : '' }}">
                                                        </div>
                                                    </div>

                                                    <!-- Relationship to Policy Holders -->
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <label class="form-label">Relationship To policy holders</label>
                                                            <div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="self_admin"
                                                                        name="relationship[]" value="self"
                                                                        {{ is_array($policyApplication->relationship) && in_array('self', $policyApplication->relationship) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="self_admin">Self(01)</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="others_admin"
                                                                        name="relationship[]" value="others"
                                                                        {{ is_array($policyApplication->relationship) && in_array('others', $policyApplication->relationship) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="others_admin">Others(11)</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="family_members_admin" name="relationship[]"
                                                                        value="family_members"
                                                                        {{ is_array($policyApplication->relationship) && in_array('family_members', $policyApplication->relationship) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="family_members_admin">Family
                                                                        Members(10)</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Authorization Checkbox -->
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="authorize_payment_admin" name="authorize_payment"
                                                                    {{ $policyApplication->authorize_payment ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="authorize_payment_admin">
                                                                    I hereby authorise Great Eastern General Insurance (Malaysia) Berhad
                                                                    (GEGM) to charge one-off payment to premium for the above insurance
                                                                    policy to my card as stated above.
                                                                    I undertake that all information stated above is true and complete in
                                                                    all respects. I have read and understood the terms & conditions
                                                                    contained in this form and I hereby agreed that the Company may process
                                                                    the instruction in the manner as stated in GEGM's Easi-pay Service Form
                                                                    (A copy can be obtained upon request).
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12 text-end">
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fa fa-paper-plane me-2"></i>SAVE PAYMENT INFO
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <hr>
                </div>
            @endif

            @php
                $profile = $policyApplication->user->applicantProfile;
                $contact = $policyApplication->user->applicantContact;
                $addresses = $policyApplication->user->addresses;
                $qualifications = $policyApplication->user->qualifications;
                $healthcare = $policyApplication->user->healthcareService;
                $pricing = $policyApplication->policyPricing;
                $risk = $policyApplication->user->riskManagement;
                $insurance = $policyApplication->user->insuranceHistory;
                $claims = $policyApplication->user->claimsExperience;

                // Helper function to get Class value (following YourActionController logic)
                function getClassValue($healthcareService)
                {
                    if (!$healthcareService) {
                        return 'N/A';
                    }

                    // Try practice_area first, fallback to service_type, then cover_type
                    $classValue = $healthcareService->practice_area 
                               ?? $healthcareService->service_type 
                               ?? $healthcareService->cover_type;

                    // Comprehensive mapping for practice_area, service_type, and cover_type values
                    $classMap = [
                        // Practice Area values
                        'general_practice' => 'General Practice',
                        'general_practice_with_specialized_procedures' => 'General Practice with Specialized Procedures',
                        'core_services' => 'Core Services',
                        'core_services_with_procedures' => 'Core Services with Procedures',
                        'general_practitioner_with_obstetrics' => 'General Practitioner with Obstetrics',
                        'cosmetic_aesthetic_non_invasive' => 'Cosmetic & Aesthetic – Non-Invasive',
                        'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic & Aesthetic – Non-Surgical Invasive',
                        'office_clinical_orthopaedics' => 'Office / Clinical Orthopaedics',
                        'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A.)',
                        'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic and Aesthetic (Surgical, Invasive)',
                        'general_dental_practice' => 'General Dental Practice',
                        'general_dental_practitioners_accredited_specialised_procedures' => 'General Dental Practitioners, practising accredited specialised procedures',
                        // Service Type values (fallback)
                        'general_practitioner_private_hospital_outpatient' => 'General Practitioner in Private Hospital - Outpatient Services',
                        'general_practitioner_private_hospital_emergency' => 'General Practitioner in Private Hospital – Emergency Department',
                        // Cover Type values (third fallback)
                        'basic_coverage' => 'Basic Coverage',
                        'comprehensive_coverage' => 'Comprehensive Coverage',
                        'premium_coverage' => 'Premium Coverage',
                        'general_dentist_practice' => 'General Dentist Practice',
                        'general_dentist_practice_practising_accredited_specialised_procedures' => 'General Dentist Practice, practising accredited specialised procedures',
                        'general_dental_practitioners' => 'General Dental Practitioners, practising accredited specialised procedures',
                    ];

                    return $classMap[$classValue] ?? 'N/A';
                }

                // Helper function to format other field names
                function formatFieldName($value)
                {
                    if (!$value) {
                        return 'N/A';
                    }

                    // Special cases for proper display
                    $specialCases = [
                        'locum_cover_only' => 'Locum Cover Only',
                        'dental_practice' => 'Dental Practice',
                        'medical_practice' => 'Medical Practice',
                        'private_clinic' => 'Private Clinic',
                        'private_hospital' => 'Private Hospital',
                        'medical_specialist' => 'Medical Specialist',
                        'general_practitioner' => 'General Practitioner',
                        'low_risk_specialist' => 'Low Risk Specialist',
                        'medium_risk_specialist' => 'Medium Risk Specialist',
                        'lecturer_trainee' => 'Lecturer/Trainee',
                        'non_practicing' => 'Non-Practicing',
                    ];

                    return $specialCases[$value] ?? ucfirst(str_replace('_', ' ', $value));
                }
            @endphp

            <!-- Step 1: Applicant Details - Title -->
            <div class="col-12">
                <h5 class="mb-3">
                    <i class="fa fa-user me-2"></i>Step 1: Details of the Applicant
                </h5>
            </div>

            <!-- Personal Information Card -->
            <div class="col-md-6">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fa fa-id-card me-2"></i>Personal Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="info-label">Title</div>
                                <div class="info-value">{{ $profile ? strtoupper($profile->title) : 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">{{ $policyApplication->user->name ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="info-label">Nationality Status</div>
                                <div class="info-value">
                                    {{ $profile ? ucfirst(str_replace('_', ' ', $profile->nationality_status)) : 'N/A' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Gender</div>
                                <div class="info-value">{{ $profile ? ucfirst($profile->gender) : 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="info-label">NRIC Number</div>
                                <div class="info-value">
                                    {{ $profile && $profile->nric_number ? $profile->nric_number : 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Passport Number</div>
                                <div class="info-value">
                                    {{ $profile && $profile->passport_number ? $profile->passport_number : 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="info-label">Contact Number</div>
                                <div class="info-value">
                                    {{ $contact ? $contact->contact_no : $policyApplication->user->contact_no ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Addresses Cards -->
            @foreach (['mailing' => 'Mailing Address', 'primary_clinic' => 'Primary Practicing Address', 'secondary_clinic' => 'Secondary Practicing Address'] as $type => $label)
                @php
                    $address = $addresses->firstWhere('type', $type);
                @endphp
                @if ($address && ($address->address || $address->clinic_name))
                    <div class="col-md-6">
                        <div class="card info-card mb-3">
                            <div class="card-body">
                                <h6 class="text-primary mb-3"><i
                                        class="fa fa-map-marker-alt me-2"></i>{{ $label }}
                                </h6>

                                <div class="row">
                                    @if ($type !== 'mailing')
                                        <div class="col-md-12 mb-3">
                                            <div class="info-label">Type</div>
                                            <div class="info-value">
                                                {{ $address->clinic_type ? ucfirst($address->clinic_type) : 'N/A' }}</div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="info-label">Clinic/Hospital Name</div>
                                            <div class="info-value">{{ $address->clinic_name ?? 'N/A' }}</div>
                                        </div>
                                    @endif
                                    <div class="col-md-12 mb-3">
                                        <div class="info-label">Address</div>
                                        <div class="info-value">{{ $address->address ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">Postcode</div>
                                        <div class="info-value">{{ $address->postcode ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">City</div>
                                        <div class="info-value">{{ $address->city ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">State</div>
                                        <div class="info-value">{{ $address->state ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Registration Details Card -->
            <div class="col-md-6">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fa fa-id-badge me-2"></i>Registration Details</h6>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="info-label">Registration Council</div>
                                <div class="info-value">
                                    @if ($profile)
                                        @if ($profile->registration_council === 'mmc')
                                            Malaysian Medical Council
                                        @elseif($profile->registration_council === 'mdc')
                                            Malaysian Dental Council
                                        @elseif($profile->registration_council === 'others')
                                            {{ $profile->other_council ?? 'Others' }}
                                        @else
                                            {{ ucfirst($profile->registration_council) }}
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="info-label">Registration Number</div>
                                <div class="info-value">{{ $profile ? $profile->registration_number : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Qualifications Card -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fa fa-graduation-cap me-2"></i>Qualifications</h6>

                        @forelse($qualifications as $index => $qual)
                            @if($index > 0)
                                <hr class="my-3">
                            @endif
                            <div class="mb-3">
                                <div class="mb-2">
                                    <strong class="text-secondary">Qualification {{ $index + 1 }}</strong>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Institution</div>
                                    <div class="info-value">{{ $qual->institution }}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Degree/Qualification</div>
                                    <div class="info-value">{{ $qual->degree_or_qualification }}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Year Obtained</div>
                                    <div class="info-value">{{ $qual->year_obtained }}</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No qualifications recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Step 2: Healthcare Services - Title -->
            <div class="col-12">
                <h5 class="mb-3">
                    <i class="fa fa-hospital me-2"></i>Step 2: Details of Healthcare Services Business
                </h5>
            </div>

            <!-- Healthcare Services Card -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Professional Indemnity Type</div>
                                <div class="info-value">
                                    {{ $healthcare ? formatFieldName($healthcare->professional_indemnity_type) : 'N/A' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Employment Status</div>
                                <div class="info-value">
                                    {{ $healthcare ? formatFieldName($healthcare->employment_status) : 'N/A' }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Specialty Area</div>
                                <div class="info-value">
                                    {{ $healthcare && $healthcare->specialty_area ? formatFieldName($healthcare->specialty_area) : 'N/A' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Class</div>
                                <div class="info-value">
                                    {{ getClassValue($healthcare) }} @if($pricing && $pricing->locum_extension) (with locum extension)@endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Locum Practice Location</div>
                                <div class="info-value">
                                    {{ $healthcare && $healthcare->locum_practice_location ? formatFieldName($healthcare->locum_practice_location) : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Pricing Details - Title -->
            <div class="col-12">
                <h5 class="mb-3">
                    <i class="fa fa-dollar-sign me-2"></i>Step 3: Pricing Details
                </h5>
            </div>

            <!-- Policy Details Card -->
            <div class="col-md-6">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fa fa-calendar-alt me-2"></i>Policy Details</h6>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="info-label">Policy Start Date</div>
                                <div class="info-value">
                                    {{ $pricing && $pricing->policy_start_date ? \Carbon\Carbon::parse($pricing->policy_start_date)->format('d M Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="info-label">Policy Expiry Date</div>
                                <div class="info-value">
                                    {{ $pricing && $pricing->policy_expiry_date ? \Carbon\Carbon::parse($pricing->policy_expiry_date)->format('d M Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="info-label">Liability Limit</div>
                                <div class="info-value">
                                    @if ($pricing && $pricing->liability_limit)
                                        RM {{ number_format($pricing->liability_limit, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Breakdown Card -->
            @if ($pricing)
                <div class="col-md-6">
                    <div class="card info-card mb-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="fa fa-calculator me-2"></i>Pricing Breakdown</h6>

                            <div class="pricing-row d-flex justify-content-between info-label">
                                <span>Premium Per Annum</span>
                                <span>RM {{ number_format($pricing->base_premium ?? 0, 2) }}</span>
                            </div>

                            @if (($pricing->loading_amount ?? 0) > 0)
                                <div class="pricing-row d-flex justify-content-between info-label">
                                    <span>Loading ({{ number_format($pricing->loading_percentage ?? 0, 2) }}%)</span>
                                    <span>RM {{ number_format($pricing->loading_amount, 2) }}</span>
                                </div>
                            @endif

                            <div class="pricing-row d-flex justify-content-between info-label">
                                <span>Gross Premium</span>
                                <span>RM {{ number_format($pricing->gross_premium ?? 0, 2) }}</span>
                            </div>

                            @if ($pricing->locum_addon > 0)
                                <div class="pricing-row d-flex justify-content-between info-label">
                                    <span>Locum Extension</span>
                                    <span>RM {{ number_format($pricing->locum_addon, 2) }}</span>
                                </div>
                            @endif

                            @if ($pricing->discount_percentage > 0)
                                <div class="pricing-row d-flex justify-content-between info-label">
                                    <span>Discount ({{ number_format($pricing->discount_percentage, 2) }}%)</span>
                                    <span class="text-success">- RM
                                        {{ number_format($pricing->discount_amount ?? 0, 2) }}</span>
                                </div>
                            @endif

                            <div class="pricing-row d-flex justify-content-between info-label">
                                <span>8% SST</span>
                                <span>RM {{ number_format($pricing->sst ?? 0, 2) }}</span>
                            </div>

                            <div class="pricing-row d-flex justify-content-between info-label">
                                <span>Stamp Duty</span>
                                <span>RM {{ number_format($pricing->stamp_duty ?? 10, 2) }}</span>
                            </div>

                            @if (($pricing->wallet_used ?? 0) > 0)
                                <div class="pricing-row d-flex justify-content-between info-label text-success">
                                    <span>Wallet Amount Used</span>
                                    <span>- RM {{ number_format($pricing->wallet_used, 2) }}</span>
                                </div>
                            @endif

                            <div class="pricing-row d-flex justify-content-between info-label">
                                <span><strong>Total Payable</strong></span>
                                <span><strong>RM {{ number_format($pricing->total_payable ?? 0, 2) }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif



            <!-- Step 4: Risk Management Card -->
            <div class="col-md-6">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fa fa-shield-alt me-2"></i>Step 4: Risk Management</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="info-label">Maintains Accurate Medical Records?</div>
                                    </div>
                                    <div>
                                        @if ($risk && $risk->medical_records)
                                            <span class="badge badge-yes">YES</span>
                                        @else
                                            <span class="badge badge-no">NO</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="info-label">Informed Consent Obtained?</div>
                                    </div>
                                    <div>
                                        @if ($risk && $risk->informed_consent)
                                            <span class="badge badge-yes">YES</span>
                                        @else
                                            <span class="badge badge-no">NO</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="info-label">Procedures for Adverse Incidents?</div>
                                    </div>
                                    <div>
                                        @if ($risk && $risk->adverse_incidents)
                                            <span class="badge badge-yes">YES</span>
                                        @else
                                            <span class="badge badge-no">NO</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="info-label">Sterilization Facilities Available?</div>
                                    </div>
                                    <div>
                                        @if ($risk && $risk->sterilisation_facilities)
                                            <span class="badge badge-yes">YES</span>
                                        @else
                                            <span class="badge badge-no">NO</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Step 5: Insurance History Card -->
            <div class="col-md-6">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fa fa-history me-2"></i>Step 5: Insurance History</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="info-label">Currently Holds Medical Malpractice Insurance?</div>
                                <div class="info-value">
                                    @if ($insurance && $insurance->current_insurance)
                                        <span class="badge badge-yes">YES</span>
                                    @else
                                        <span class="badge badge-no">NO</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Previous Insurance Refused/Cancelled?</div>
                                <div class="info-value">
                                    @if ($insurance && $insurance->previous_claims)
                                        <span class="badge badge-yes">YES</span>
                                    @else
                                        <span class="badge badge-no">NO</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($insurance && $insurance->current_insurance && $insurance->insurer_name)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">Current Insurance Details</h6>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-label">Insurer Name</div>
                                    <div class="info-value">{{ $insurance->insurer_name }}</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-label">Period of Insurance</div>
                                    <div class="info-value">{{ $insurance->period_of_insurance ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-label">Policy Limit (MYR)</div>
                                    <div class="info-value">{{ $insurance->policy_limit_myr ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-label">Excess (MYR)</div>
                                    <div class="info-value">{{ $insurance->excess_myr ?? 'N/A' }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($insurance && $insurance->previous_claims && $insurance->claims_details)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">Previous Claims Details</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <p class="mb-0">{{ $insurance->claims_details }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>



            <!-- Step 6: Claims Experience Card -->
            <div class="col-md-6">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fa fa-exclamation-triangle me-2"></i>Step 6: Claims
                            Experience</h6>

                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <div class="info-label">Claims Ever Made?</div>
                                <div class="info-value">
                                    @if ($claims && $claims->claims_made)
                                        <span class="badge badge-yes">YES</span>
                                    @else
                                        <span class="badge badge-no">NO</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="info-label">Aware of Errors/Omissions?</div>
                                <div class="info-value">
                                    @if ($claims && $claims->aware_of_errors)
                                        <span class="badge badge-yes">YES</span>
                                    @else
                                        <span class="badge badge-no">NO</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="info-label">Subject to Disciplinary Action?</div>
                                <div class="info-value">
                                    @if ($claims && $claims->disciplinary_action)
                                        <span class="badge badge-yes">YES</span>
                                    @else
                                        <span class="badge badge-no">NO</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($claims && ($claims->claims_made || $claims->aware_of_errors || $claims->disciplinary_action))
                            @if ($claims->claim_claimant_name || $claims->claim_allegations || $claims->claim_amount_claimed)
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">Claim Details</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">Date of Claim</div>
                                        <div class="info-value">{{ $claims->claim_date_of_claim ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">Date Notified to Insurer</div>
                                        <div class="info-value">{{ $claims->claim_notified_date ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">Claimant Name</div>
                                        <div class="info-value">{{ $claims->claim_claimant_name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">Allegations</div>
                                        <div class="info-value">{{ $claims->claim_allegations ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">Amount Claimed</div>
                                        <div class="info-value">{{ $claims->claim_amount_claimed ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">Status</div>
                                        <div class="info-value">{{ $claims->claim_status ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="info-label">Amounts Paid</div>
                                        <div class="info-value">{{ $claims->claim_amounts_paid ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>



            <!-- Step 7 & 8: Declarations and Signature Card -->
            <div class="col-md-6">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fa fa-file-signature me-2"></i>Step 7 & 8: Declarations
                            and Signature</h6>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-label">Data Protection Notice Agreed?</div>
                                <div class="info-value">
                                    @if ($policyApplication->agree_data_protection)
                                        <span class="badge badge-yes"><i class="fa fa-check"></i> AGREED</span>
                                    @else
                                        <span class="badge badge-no"><i class="fa fa-times"></i> NOT AGREED</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Final Declaration Agreed?</div>
                                <div class="info-value">
                                    @if ($policyApplication->agree_declaration)
                                        <span class="badge badge-yes"><i class="fa fa-check"></i> AGREED</span>
                                    @else
                                        <span class="badge badge-no"><i class="fa fa-times"></i> NOT AGREED</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-label">Applicant Signature</div>
                                <div class="signature-box">
                                    @if ($policyApplication->signature_data)
                                        @php
                                            // Check if signature is base64 or file path
                                            $isBase64 = str_starts_with(
                                                $policyApplication->signature_data,
                                                'data:image',
                                            );
                                            if ($isBase64) {
                                                $signatureUrl = $policyApplication->signature_data;
                                            } else {
                                                // Old data: strip "app/" prefix if present
                                                $signaturePath = $policyApplication->signature_data;
                                                if (str_starts_with($signaturePath, 'app/')) {
                                                    $signaturePath = substr($signaturePath, 4); // Remove "app/" prefix
                                                }
                                                $signatureUrl = Storage::url($signaturePath);
                                            }
                                        @endphp
                                        <img src="{{ $signatureUrl }}" alt="Signature">
                                    @else
                                        <span class="text-muted">No signature available</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Document Section -->
            @if (
                $policyApplication->payment_method != null ||
                    $policyApplication->customer_status === 'paid' ||
                    $policyApplication->admin_status === 'paid')
                <div class="col-12">
                    <div class="card info-card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="section-title mb-0">
                                    <i class="fa fa-credit-card me-2"></i>Payment Information
                                </h5>
                                @if ($policyApplication->payment_document || $policyApplication->payment_method)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editPaymentModal">
                                        <i class="fa fa-edit me-1"></i>Edit Payment
                                    </button>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-label">Payment Status</div>
                                    <div class="info-value">
                                        @if ($policyApplication->customer_status === 'paid' && $policyApplication->admin_status === 'paid')
                                            <span class="badge bg-success fs-6">
                                                <i class="fa fa-check-circle me-1"></i>Payment Received
                                            </span>
                                        @elseif($policyApplication->customer_status === 'pay_now')
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="fa fa-clock me-1"></i>Awaiting Payment
                                            </span>
                                        @else
                                            <span class="badge bg-info fs-6">
                                                <i class="fa fa-check-circle me-1"></i>Payment Received
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-label">Payment Received Date</div>
                                    <div class="info-value">
                                        @if ($policyApplication->payment_received_at)
                                            <i class="fa fa-calendar-check text-success me-1"></i>
                                            {{ $policyApplication->payment_received_at->format('d M Y, h:i A') }}
                                        @else
                                            <span class="text-muted">Not yet received</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($policyApplication->payment_document)
                                @php
                                    // Check if payment document is old imported data (starts with app/)
                                    $paymentDocPath = $policyApplication->payment_document;
                                    if (str_starts_with($paymentDocPath, 'app/')) {
                                        $paymentDocPath = substr($paymentDocPath, 4); // Remove "app/" prefix
                                    }
                                    $paymentDocUrl = Storage::url($paymentDocPath);
                                @endphp
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-success">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-2">
                                                        <i class="fa fa-file-pdf text-danger me-2"></i>
                                                        Payment Document Uploaded
                                                    </h6>
                                                    <p class="mb-0 small">
                                                        <strong>File:</strong>
                                                        {{ basename($policyApplication->payment_document) }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <a href="{{ $paymentDocUrl }}" target="_blank"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fa fa-eye me-1"></i>View Document
                                                    </a>
                                                    <a href="{{ $paymentDocUrl }}" download
                                                        class="btn btn-success btn-sm">
                                                        <i class="fa fa-download me-1"></i>Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if ($policyApplication->payment_method === 'credit_card' && $policyApplication->card_no)
                                    <!-- Credit Card Payment Info -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="mb-3"><i
                                                            class="fa fa-credit-card text-success me-2"></i>Credit Card
                                                        Payment Details</h6>
                                                    <hr style="color:black">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <div class="info-label">Payment Method</div>
                                                            <div class="info-value">Credit Card</div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="info-label">Payment Date</div>
                                                            <div class="info-value">
                                                                {{ $policyApplication->payment_received_at ? $policyApplication->payment_received_at->format('d M Y, h:i A') : 'N/A' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="info-label">Name On Card</div>
                                                            <div class="info-value">
                                                                {{ $policyApplication->name_on_card ?? 'N/A' }}</div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="info-label">NRIC NO</div>
                                                            <div class="info-value">
                                                                {{ $policyApplication->nric_no ?? 'N/A' }}</div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="info-label">Card No</div>
                                                            <div class="info-value">
                                                                {{ $policyApplication->card_no ?? 'N/A' }}</div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="info-label">Card Issuing Bank</div>
                                                            <div class="info-value">
                                                                {{ $policyApplication->card_issuing_bank ?? 'N/A' }}</div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="info-label">Card Type</div>
                                                            <div class="info-value">
                                                                @if ($policyApplication->card_type && is_array($policyApplication->card_type))
                                                                    {{ implode(', ', array_map('ucfirst', $policyApplication->card_type)) }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="info-label">Card Expiry</div>
                                                            <div class="info-value">
                                                                @if ($policyApplication->expiry_month && $policyApplication->expiry_year)
                                                                    {{ $policyApplication->expiry_month }}/{{ $policyApplication->expiry_year }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="info-label">Relationship To Policy Holder</div>
                                                            <div class="info-value">
                                                                @if ($policyApplication->relationship && is_array($policyApplication->relationship))
                                                                    {{ implode(', ', array_map('ucfirst', str_replace('_', ' ', $policyApplication->relationship))) }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="info-label">Authorization Status</div>
                                                            <div class="info-value">
                                                                @if ($policyApplication->authorize_payment)
                                                                    <span class="badge bg-success"><i
                                                                            class="fa fa-check"></i> Authorized</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Not Authorized</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="info-label">Amount</div>
                                                            <div class="info-value">
                                                                <span class="text-success fw-bold fs-5">RM
                                                                    {{ number_format($policyApplication->policyPricing->total_payable ?? 0, 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="alert alert-warning">
                                                <i class="fa fa-exclamation-triangle me-2"></i>
                                                <strong>No payment document uploaded yet.</strong>
                                                <p class="mb-0 mt-1 small">Client will upload payment proof once they
                                                    complete the payment.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="info-label">Total Amount</div>
                                    <div class="info-value">
                                        <span class="text-success fw-bold fs-5">
                                            RM
                                            {{ number_format($policyApplication->policyPricing->total_payable ?? 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-label">Customer Status</div>
                                    <div class="info-value">
                                        @php
                                            $customerStatusBadges = [
                                                'submitted' => ['bg-secondary', 'Submitted'],
                                                'pay_now' => ['bg-warning text-dark', 'Pay Now'],
                                                'paid' => ['bg-info', 'Paid'],
                                                'processing' => ['bg-primary', 'Processing'],
                                                'active' => ['bg-success', 'Active'],
                                            ];
                                            $cs = $customerStatusBadges[$policyApplication->customer_status] ?? [
                                                'bg-secondary',
                                                ucfirst($policyApplication->customer_status),
                                            ];
                                        @endphp
                                        <span class="badge {{ $cs[0] }}">{{ $cs[1] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-label">Admin Status</div>
                                    <div class="info-value">
                                        @php
                                            $adminStatusBadges = [
                                                'new_case' => ['bg-light text-dark', 'New Case'],
                                                'new_renewal' => ['bg-light text-dark', 'New Renewal'],
                                                'not_paid' => ['bg-warning text-dark', 'Not Paid'],
                                                'paid' => ['bg-info', 'Paid'],
                                                'sent_uw' => ['bg-primary', 'Sent UW'],
                                                'active' => ['bg-success', 'Active'],
                                            ];
                                            $as = $adminStatusBadges[$policyApplication->admin_status] ?? [
                                                'bg-secondary',
                                                ucfirst($policyApplication->admin_status),
                                            ];
                                        @endphp
                                        <span class="badge {{ $as[0] }}">{{ $as[1] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fa fa-user-shield me-2"></i>Admin Action
                        </h5>

                        <form action="{{ route('for-your-action.update-status', $policyApplication->id) }}"
                            method="POST" id="adminActionForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="status" class="form-label fw-bold">
                                        <i class="fa fa-flag me-2"></i>Update Application Status <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="new"
                                            {{ $policyApplication->status === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="approved"
                                            {{ $policyApplication->status === 'approved' ? 'selected' : '' }}>Approved
                                        </option>
                                        <option value="send_uw"
                                            {{ $policyApplication->status === 'send_uw' ? 'selected' : '' }}>Send UW
                                        </option>
                                        <option value="active"
                                            {{ $policyApplication->status === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        {{-- <option value="processing"
                                            {{ $policyApplication->status === 'processing' ? 'selected' : '' }}>Processing
                                        </option> --}}
                                        <option value="rejected"
                                            {{ $policyApplication->status === 'rejected' ? 'selected' : '' }}>Rejected
                                        </option>
                                        <option value="cancelled"
                                            {{ $policyApplication->status === 'cancelled' ? 'selected' : '' }}>Cancelled
                                        </option>
                                    </select>
                                    <small class="text-muted d-block mt-1">
                                        <strong>C.S:</strong>
                                        @php
                                            $customerStatusBadges = [
                                                'submitted' => ['bg-secondary', 'Submitted'],
                                                'pay_now' => ['bg-warning', 'Pay Now'],
                                                'paid' => ['bg-info', 'Paid'],
                                                'processing' => ['bg-primary', 'Processing'],
                                                'active' => ['bg-success', 'Active'],
                                            ];
                                            $cs = $customerStatusBadges[$policyApplication->customer_status] ?? [
                                                'bg-secondary',
                                                ucfirst($policyApplication->customer_status),
                                            ];
                                        @endphp
                                        <span class="badge {{ $cs[0] }}">{{ $cs[1] }}</span>
                                    </small>
                                    <small class="text-muted d-block">
                                        <strong>A.S:</strong>
                                        @php
                                            $adminStatusBadges = [
                                                'new_case' => ['bg-light text-dark', 'New Case'],
                                                'new_renewal' => ['bg-light text-dark', 'New Renewal'],
                                                'not_paid' => ['bg-warning', 'Not Paid'],
                                                'paid' => ['bg-info', 'Paid'],
                                                'sent_uw' => ['bg-primary', 'Sent UW'],
                                                'active' => ['bg-success', 'Active'],
                                            ];
                                            $as = $adminStatusBadges[$policyApplication->admin_status] ?? [
                                                'bg-secondary',
                                                ucfirst($policyApplication->admin_status),
                                            ];
                                        @endphp
                                        <span class="badge {{ $as[0] }}">{{ $as[1] }}</span>
                                    </small>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">
                                        <i class="fa fa-user me-2"></i>Customer Status (C.S)
                                    </label>
                                    <input type="text" class="form-control"
                                        value="{{ ucfirst(str_replace('_', ' ', $policyApplication->customer_status ?? 'submitted')) }}"
                                        disabled>
                                    <small class="text-muted">Status visible to customer</small>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">
                                        <i class="fa fa-user-shield me-2"></i>Admin Status (A.S)
                                    </label>
                                    <input type="text" class="form-control"
                                        value="{{ ucfirst(str_replace('_', ' ', $policyApplication->admin_status ?? 'new_case')) }}"
                                        disabled>
                                    <small class="text-muted">Internal admin status</small>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">
                                        <i class="fa fa-info-circle me-2"></i>Reference Number
                                    </label>
                                    <input type="text" class="form-control"
                                        value="{{ $policyApplication->reference_number ?? 'Will be auto-generated on approval' }}"
                                        disabled>
                                    <small class="text-muted">Reference number is auto-assigned when status is changed to
                                        "Approved"</small>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="remarks" class="form-label fw-bold">
                                        <i class="fa fa-comment-dots me-2"></i>Admin Remarks / Notes
                                    </label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="4"
                                        placeholder="Enter remarks, notes, or reason for status change...">{{ $policyApplication->remarks }}</textarea>
                                    <small class="text-muted">Add any notes or comments about this application status
                                        change</small>
                                </div>
                            </div>

                            @if ($policyApplication->remarks)
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <strong><i class="fa fa-info-circle me-2"></i>Previous Remarks:</strong>
                                            <p class="mb-0 mt-2">{{ $policyApplication->remarks }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($policyApplication->action_by)
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="mb-3"><i class="fa fa-history me-2"></i>Action History</h6>
                                                <p class="mb-1">
                                                    <strong>Last Action By:</strong>
                                                    {{ $policyApplication->actionBy->name ?? 'N/A' }}
                                                </p>
                                                @if ($policyApplication->action_at)
                                                    <p class="mb-1">
                                                        <strong>Action Date:</strong>
                                                        {{ $policyApplication->action_at->format('d M Y, h:i A') }}
                                                    </p>
                                                @endif
                                                <p class="mb-0">
                                                    <strong>Current Status:</strong>
                                                    @if ($policyApplication->status === 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($policyApplication->status === 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @elseif($policyApplication->status === 'active')
                                                        <span class="badge bg-primary">Active</span>
                                                    @elseif($policyApplication->status === 'processing')
                                                        <span class="badge bg-info">Processing</span>
                                                    @elseif($policyApplication->status === 'send_uw')
                                                        <span class="badge bg-warning">Send UW</span>
                                                    @elseif($policyApplication->status === 'cancelled')
                                                        <span class="badge bg-secondary">Cancelled</span>
                                                    @elseif($policyApplication->status === 'new')
                                                        <span class="badge bg-light text-dark">New</span>
                                                    @else
                                                        <span
                                                            class="badge bg-secondary">{{ ucfirst($policyApplication->status) }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-12 text-end">
                                    {{-- <button type="button" class="btn btn-outline-secondary me-2" onclick="resetForm()">
                                        <i class="fa fa-undo me-2"></i>Reset
                                    </button> --}}
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save me-2"></i>Update Status
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Upload Tax Receipt & Policy Schedule (Only for Active Policies) -->
            @if ($policyApplication->admin_status === 'active')
                <div class="col-12">
                    <div class="card info-card mb-3">
                        <div class="card-body">
                            <h5 class="section-title">
                                <i class="fa fa-upload me-2"></i>Upload Documents
                            </h5>

                            <form action="{{ route('for-your-action.upload-documents', $policyApplication->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">
                                    <!-- Tax Receipt Upload -->
                                    <div class="col-md-6">
                                        <label for="tax_receipt" class="form-label fw-bold">
                                            <i class="fa fa-file-invoice me-2"></i>Tax Receipt
                                        </label>
                                        @if ($policyApplication->tax_receipt_path)
                                            <div class="alert alert-success mb-2">
                                                <i class="fa fa-check-circle me-2"></i>
                                                <a href="{{ Storage::url($policyApplication->tax_receipt_path) }}"
                                                    target="_blank" class="text-decoration-none">
                                                    View Current Tax Receipt
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" id="tax_receipt" name="tax_receipt"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Upload tax receipt (PDF, JPG, PNG - Max 5MB)</small>
                                    </div>

                                    <!-- Policy Schedule Upload -->
                                    <div class="col-md-6">
                                        <label for="policy_schedule" class="form-label fw-bold">
                                            <i class="fa fa-file-contract me-2"></i>Policy Schedule
                                        </label>
                                        @if ($policyApplication->policy_schedule_path)
                                            <div class="alert alert-success mb-2">
                                                <i class="fa fa-check-circle me-2"></i>
                                                <a href="{{ Storage::url($policyApplication->policy_schedule_path) }}"
                                                    target="_blank" class="text-decoration-none">
                                                    View Current Policy Schedule
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" id="policy_schedule"
                                            name="policy_schedule" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Upload policy schedule (PDF, JPG, PNG - Max 5MB)</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-upload me-2"></i>Upload Documents
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Certificate of Insurance (CI) Document Display -->
            <!-- Certificate of Insurance (CI) Document Display -->
            @if ($policyApplication->admin_status === 'active')
                @if ($policyApplication->certificate_document)
                    <div class="col-12">
                        <div class="card info-card mb-3 border-success">
                            <div class="card-body">
                                <h5 class="section-title text-success">
                                    <i class="fa fa-certificate me-2"></i>Certificate of Insurance (CI)
                                </h5>

                                <div class="row">
                                    <div class="col-md-8">
                                        <i class="fa fa-check-circle me-2"></i>
                                        <strong>Certificate Document Available</strong>
                                        <p class="mb-2 mt-2">The Certificate of Insurance has been uploaded and is
                                            available for download.</p>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ Storage::url($policyApplication->certificate_document) }}"
                                                target="_blank" class="btn btn-success btn-sm">
                                                <i class="fa fa-download me-2"></i>Download CI Document
                                            </a>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#reuploadCIModal">
                                                <i class="fa fa-upload me-2"></i>Reupload CI
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">Document Information</div>
                                        <div class="info-value">
                                            <small>
                                                <strong>Uploaded:</strong>
                                                {{ $policyApplication->activated_at ? $policyApplication->activated_at->format('d M Y, h:i A') : 'N/A' }}<br>
                                                <strong>Policy Status:</strong> <span
                                                    class="badge bg-success">Active</span><br>
                                                <strong>File:</strong>
                                                {{ basename($policyApplication->certificate_document) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="card info-card mb-3 border-warning">
                            <div class="card-body">
                                <h5 class="section-title text-warning">
                                    <i class="fa fa-certificate me-2"></i>Certificate of Insurance (CI)
                                </h5>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-warning mb-0">
                                            <i class="fa fa-exclamation-triangle me-2"></i>
                                            <strong>Certificate Not Yet Uploaded</strong>
                                            <p class="mb-2 mt-2">The Certificate of Insurance document has not been uploaded yet. Please upload it to complete the policy activation.</p>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#ciUploadModal">
                                                <i class="fa fa-upload me-2"></i>Upload CI Document
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Action Buttons -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('for-your-action') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-arrow-left me-2"></i>Back to List
                        </a>
                        <a href="{{ route('for-your-action.export-pdf', $policyApplication->id) }}"
                            class="btn btn-danger me-2" target="_blank">
                            <i class="fa fa-file-pdf me-2"></i>Export PDF
                        </a>
                        <a href="{{ route('for-your-action.edit', $policyApplication->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit me-2"></i>Edit Application
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CI Document Upload Modal -->
    <div class="modal fade" id="ciUploadModal" tabindex="-1" aria-labelledby="ciUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="ciUploadModalLabel">
                        <i class="fa fa-file-pdf me-2"></i>Upload Certificate of Insurance (CI)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>Required:</strong> Please upload the Certificate of Insurance (CI) PDF document before
                        activating this policy.
                    </div>

                    <div class="mb-3">
                        <label for="certificate_document_input" class="form-label fw-bold">
                            <i class="fa fa-upload me-2"></i>CI Document (PDF) <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control" id="certificate_document_input"
                            name="certificate_document" accept=".pdf" required>
                        <small class="text-muted">Maximum file size: 10MB | Format: PDF only</small>

                        <div id="filePreview" class="mt-3" style="display: none;">
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle me-2"></i>
                                <strong>Selected file:</strong> <span id="fileName"></span>
                                <br>
                                <small><strong>Size:</strong> <span id="fileSize"></span></small>
                            </div>
                        </div>

                        <div id="fileError" class="mt-2" style="display: none;">
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                <span id="errorMessage"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmUploadBtn">
                        <i class="fa fa-check me-2"></i>Confirm & Activate Policy
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Edit Modal -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editPaymentModalLabel">
                        <i class="fa fa-edit me-2"></i>Edit Payment Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Payment Method Selection -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="mb-3"><i class="fa fa-coins me-2"></i>Select Payment Method</h6>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="payment_method_modal"
                                    id="payment_proof_method_modal" value="proof"
                                    {{ !$policyApplication->payment_method || $policyApplication->payment_document ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="payment_proof_method_modal">
                                    <i class="fa fa-file-upload me-2"></i>Upload Payment Proof
                                </label>

                                <input type="radio" class="btn-check" name="payment_method_modal"
                                    id="credit_card_method_modal" value="credit_card"
                                    {{ $policyApplication->payment_method === 'credit_card' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="credit_card_method_modal">
                                    <i class="fa fa-credit-card me-2"></i>Credit Card Payment
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Upload Form -->
                    <form action="{{ route('for-your-action.upload-payment', $policyApplication->id) }}" method="POST"
                        enctype="multipart/form-data" id="paymentEditForm">
                        @csrf
                        <input type="hidden" name="payment_type" id="payment_type_modal"
                            value="{{ $policyApplication->payment_document ? 'proof' : ($policyApplication->payment_method === 'credit_card' ? 'credit_card' : 'proof') }}">

                        <!-- Payment Proof Upload Section -->
                        <div id="proofPaymentSectionModal"
                            class="payment-section {{ $policyApplication->payment_method === 'credit_card' && !$policyApplication->payment_document ? 'd-none' : '' }}">
                            <!-- Bank Account Details -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h6 class="mb-2"><i class="fa fa-university me-2"></i>Bank Account Details for
                                            Payment:</h6>
                                        <p class="mb-1"><strong>Great Eastern General Insurance (Malaysia) BHD</strong>
                                        </p>
                                        <p class="mb-1"><strong>Branch:</strong> OCBC bank Malaysia</p>
                                        <p class="mb-0"><strong>A/C No:</strong> 7041102530</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label for="payment_document_modal" class="form-label fw-bold">
                                        <i class="fa fa-upload me-2"></i>Upload payment proof: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control" id="payment_document_modal"
                                        name="payment_document" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Accepted formats: PDF, JPG, JPEG, PNG (Max: 5MB)</small>
                                    @if ($policyApplication->payment_document)
                                        <div class="mt-2">
                                            <small class="text-success">
                                                <i class="fa fa-check-circle me-1"></i>Current payment document:
                                                <a href="{{ asset('storage/' . $policyApplication->payment_document) }}"
                                                    target="_blank">View Document</a>
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Credit Card Payment Section -->
                        <div id="creditCardSectionModal"
                            class="payment-section {{ $policyApplication->payment_method !== 'credit_card' || $policyApplication->payment_document ? 'd-none' : '' }}">
                            <!-- Notice -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        <p class="mb-0"><strong>Payment will be charged by Great Eastern after receiving
                                                this form.</strong></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Credit Card Fields -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name_on_card_modal" class="form-label">Name On Card</label>
                                    <input type="text" class="form-control" id="name_on_card_modal"
                                        name="name_on_card" placeholder="Name On Card"
                                        value="{{ $policyApplication->name_on_card ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="nric_no_modal" class="form-label">NRIC NO</label>
                                    <input type="text" class="form-control" id="nric_no_modal" name="nric_no"
                                        placeholder="NRIC NO" value="{{ $policyApplication->nric_no ?? '' }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="card_no_modal" class="form-label">Card No</label>
                                    <input type="text" class="form-control" id="card_no_modal" name="card_no"
                                        placeholder="Card No" value="{{ $policyApplication->card_no ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="card_issuing_bank_modal" class="form-label">Card Issuing Bank</label>
                                    <input type="text" class="form-control" id="card_issuing_bank_modal"
                                        name="card_issuing_bank" placeholder="Card Issuing Bank"
                                        value="{{ $policyApplication->card_issuing_bank ?? '' }}">
                                </div>
                            </div>

                            <!-- Card Type -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Card Type</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="visa_card_modal"
                                                name="card_type[]" value="visa"
                                                {{ is_array($policyApplication->card_type) && in_array('visa', $policyApplication->card_type) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="visa_card_modal">Visa Card</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="master_card_modal"
                                                name="card_type[]" value="master"
                                                {{ is_array($policyApplication->card_type) && in_array('master', $policyApplication->card_type) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="master_card_modal">Master card</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Expiry Date -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="expiry_month_modal" class="form-label">Month</label>
                                    <select class="form-select" id="expiry_month_modal" name="expiry_month">
                                        <option value="">Month</option>
                                        <option value="01"
                                            {{ ($policyApplication->expiry_month ?? '') == '01' ? 'selected' : '' }}>01 -
                                            January</option>
                                        <option value="02"
                                            {{ ($policyApplication->expiry_month ?? '') == '02' ? 'selected' : '' }}>02 -
                                            February</option>
                                        <option value="03"
                                            {{ ($policyApplication->expiry_month ?? '') == '03' ? 'selected' : '' }}>03 -
                                            March</option>
                                        <option value="04"
                                            {{ ($policyApplication->expiry_month ?? '') == '04' ? 'selected' : '' }}>04 -
                                            April</option>
                                        <option value="05"
                                            {{ ($policyApplication->expiry_month ?? '') == '05' ? 'selected' : '' }}>05 -
                                            May</option>
                                        <option value="06"
                                            {{ ($policyApplication->expiry_month ?? '') == '06' ? 'selected' : '' }}>06 -
                                            June</option>
                                        <option value="07"
                                            {{ ($policyApplication->expiry_month ?? '') == '07' ? 'selected' : '' }}>07 -
                                            July</option>
                                        <option value="08"
                                            {{ ($policyApplication->expiry_month ?? '') == '08' ? 'selected' : '' }}>08 -
                                            August</option>
                                        <option value="09"
                                            {{ ($policyApplication->expiry_month ?? '') == '09' ? 'selected' : '' }}>09 -
                                            September</option>
                                        <option value="10"
                                            {{ ($policyApplication->expiry_month ?? '') == '10' ? 'selected' : '' }}>10 -
                                            October</option>
                                        <option value="11"
                                            {{ ($policyApplication->expiry_month ?? '') == '11' ? 'selected' : '' }}>11 -
                                            November</option>
                                        <option value="12"
                                            {{ ($policyApplication->expiry_month ?? '') == '12' ? 'selected' : '' }}>12 -
                                            December</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="expiry_year_modal" class="form-label">Year</label>
                                    <select class="form-select" id="expiry_year_modal" name="expiry_year">
                                        <option value="">Year</option>
                                        @for ($year = date('Y'); $year <= date('Y') + 20; $year++)
                                            <option value="{{ $year }}"
                                                {{ ($policyApplication->expiry_year ?? '') == $year ? 'selected' : '' }}>
                                                {{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Expiry</label>
                                    <input type="text" class="form-control" readonly placeholder="Expiry"
                                        id="expiry_display_modal"
                                        value="{{ $policyApplication->expiry_month && $policyApplication->expiry_year ? $policyApplication->expiry_month . '/' . $policyApplication->expiry_year : '' }}">
                                </div>
                            </div>

                            <!-- Relationship to Policy Holders -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Relationship To policy holders</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="self_modal"
                                                name="relationship[]" value="self"
                                                {{ is_array($policyApplication->relationship) && in_array('self', $policyApplication->relationship) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="self_modal">Self(01)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="others_modal"
                                                name="relationship[]" value="others"
                                                {{ is_array($policyApplication->relationship) && in_array('others', $policyApplication->relationship) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="others_modal">Others(11)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="family_members_modal"
                                                name="relationship[]" value="family_members"
                                                {{ is_array($policyApplication->relationship) && in_array('family_members', $policyApplication->relationship) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="family_members_modal">Family
                                                Members(10)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Authorization Checkbox -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="authorize_payment_modal"
                                            name="authorize_payment"
                                            {{ $policyApplication->authorize_payment ? 'checked' : '' }}>
                                        <label class="form-check-label" for="authorize_payment_modal">
                                            I hereby authorise Great Eastern General Insurance (Malaysia) Berhad
                                            (GEGM) to charge one-off payment to premium for the above insurance
                                            policy to my card as stated above.
                                            I undertake that all information stated above is true and complete in
                                            all respects. I have read and understood the terms & conditions
                                            contained in this form and I hereby agreed that the Company may process
                                            the instruction in the manner as stated in GEGM's Easi-pay Service Form
                                            (A copy can be obtained upon request).
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function resetForm() {
            if (confirm('Are you sure you want to reset the form?')) {
                document.getElementById('adminActionForm').reset();
            }
        }

        // Auto-hide success/error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Initialize CI upload modal handling
            initializeCIUpload();
        });

        // Helper function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        function initializeCIUpload() {
            const form = document.getElementById('adminActionForm');
            const statusSelect = document.getElementById('status');
            const ciModal = new bootstrap.Modal(document.getElementById('ciUploadModal'));
            const ciFileInput = document.getElementById('certificate_document_input');
            const confirmBtn = document.getElementById('confirmUploadBtn');
            const filePreview = document.getElementById('filePreview');
            const fileError = document.getElementById('fileError');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const errorMessage = document.getElementById('errorMessage');

            let isSubmittingWithCI = false;
            let selectedFile = null;

            // Intercept form submission
            form.addEventListener('submit', function(e) {
                const selectedStatus = statusSelect.value;

                // If status is "active" and we haven't shown the modal yet
                if (selectedStatus === 'active' && !isSubmittingWithCI) {
                    e.preventDefault();
                    ciModal.show();
                    return false;
                }
            });

            // Handle file selection and validation
            ciFileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                // Reset states
                filePreview.style.display = 'none';
                fileError.style.display = 'none';
                confirmBtn.disabled = true;
                selectedFile = null;

                if (!file) {
                    return;
                }

                console.log('File selected:', file.name, 'Type:', file.type, 'Size:', file.size);

                // Validate file type - check both MIME type and extension
                const isPDF = file.type === 'application/pdf' ||
                    file.type === 'application/x-pdf' ||
                    file.name.toLowerCase().endsWith('.pdf');

                if (!isPDF) {
                    fileError.style.display = 'block';
                    errorMessage.textContent = 'Please select a PDF file only. Selected file type: ' + (file.type ||
                        'unknown');
                    ciFileInput.value = '';
                    console.error('Invalid file type:', file.type);
                    return;
                }

                // Validate file size (10MB = 10485760 bytes)
                const maxSize = 10 * 1024 * 1024; // 10MB
                if (file.size > maxSize) {
                    fileError.style.display = 'block';
                    errorMessage.textContent = 'File size exceeds 10MB. Please select a smaller file.';
                    ciFileInput.value = '';
                    console.error('File too large:', file.size);
                    return;
                }

                // Store the file
                selectedFile = file;

                // Show file preview
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                filePreview.style.display = 'block';
                confirmBtn.disabled = false;

                console.log('File validated successfully, button enabled');
            });

            // Handle confirm button click
            confirmBtn.addEventListener('click', function() {
                console.log('Confirm button clicked!');
                console.log('Selected file:', selectedFile);

                if (!selectedFile) {
                    fileError.style.display = 'block';
                    errorMessage.textContent = 'Please select a file before confirming.';
                    console.error('No file selected');
                    return;
                }

                console.log('Creating file input in form...');

                // Create or get the file input in the form
                let formFileInput = form.querySelector('input[name="certificate_document"]');
                if (!formFileInput) {
                    formFileInput = document.createElement('input');
                    formFileInput.type = 'file';
                    formFileInput.name = 'certificate_document';
                    formFileInput.style.display = 'none';
                    form.appendChild(formFileInput);
                    console.log('Created new file input in form');
                } else {
                    console.log('Using existing file input in form');
                }

                // Transfer the file to the form's file input
                try {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(selectedFile);
                    formFileInput.files = dataTransfer.files;
                    console.log('File transferred to form input:', formFileInput.files[0].name);
                } catch (error) {
                    console.error('Error transferring file:', error);
                    fileError.style.display = 'block';
                    errorMessage.textContent = 'Error preparing file for upload. Please try again.';
                    return;
                }

                // Set flag to allow form submission
                isSubmittingWithCI = true;
                console.log('Set isSubmittingWithCI to true');

                // Hide modal
                ciModal.hide();
                console.log('Modal hidden');

                // Submit the form
                console.log('Submitting form...');
                form.submit();
                console.log('Form submitted');
            });

            // Reset flag when modal is closed without confirming
            document.getElementById('ciUploadModal').addEventListener('hidden.bs.modal', function() {
                if (!isSubmittingWithCI) {
                    // Reset file input
                    ciFileInput.value = '';
                    filePreview.style.display = 'none';
                    fileError.style.display = 'none';
                    confirmBtn.disabled = true;
                    selectedFile = null;
                }
            });
        }

        // Admin Payment Method Toggle
        const paymentProofMethodAdmin = document.getElementById('payment_proof_method_admin');
        const creditCardMethodAdmin = document.getElementById('credit_card_method_admin');
        const proofSectionAdmin = document.getElementById('proofPaymentSectionAdmin');
        const creditCardSectionAdmin = document.getElementById('creditCardSectionAdmin');
        const paymentTypeInputAdmin = document.getElementById('payment_type_admin');
        const paymentDocumentAdmin = document.getElementById('payment_document_admin');
        const authorizePaymentAdmin = document.getElementById('authorize_payment_admin');

        if (paymentProofMethodAdmin && creditCardMethodAdmin) {
            paymentProofMethodAdmin.addEventListener('change', function() {
                if (this.checked) {
                    proofSectionAdmin.classList.remove('d-none');
                    creditCardSectionAdmin.classList.add('d-none');
                    if (paymentTypeInputAdmin) paymentTypeInputAdmin.value = 'proof';
                    if (paymentDocumentAdmin) paymentDocumentAdmin.setAttribute('required', 'required');
                    if (authorizePaymentAdmin) authorizePaymentAdmin.removeAttribute('required');
                }
            });

            creditCardMethodAdmin.addEventListener('change', function() {
                if (this.checked) {
                    creditCardSectionAdmin.classList.remove('d-none');
                    proofSectionAdmin.classList.add('d-none');
                    if (paymentTypeInputAdmin) paymentTypeInputAdmin.value = 'credit_card';
                    if (paymentDocumentAdmin) paymentDocumentAdmin.removeAttribute('required');
                    if (authorizePaymentAdmin) authorizePaymentAdmin.setAttribute('required', 'required');
                }
            });
        }

        // Update expiry display for admin form
        const expiryMonthAdmin = document.getElementById('expiry_month_admin');
        const expiryYearAdmin = document.getElementById('expiry_year_admin');
        const expiryDisplayAdmin = document.getElementById('expiry_display_admin');

        function updateExpiryDisplayAdmin() {
            if (expiryMonthAdmin && expiryYearAdmin && expiryDisplayAdmin) {
                const month = expiryMonthAdmin.value;
                const year = expiryYearAdmin.value;
                if (month && year) {
                    expiryDisplayAdmin.value = month + '/' + year;
                } else {
                    expiryDisplayAdmin.value = '';
                }
            }
        }

        if (expiryMonthAdmin) {
            expiryMonthAdmin.addEventListener('change', updateExpiryDisplayAdmin);
        }
        if (expiryYearAdmin) {
            expiryYearAdmin.addEventListener('change', updateExpiryDisplayAdmin);
        }

        // Modal Payment Method Toggle
        const paymentProofMethodModal = document.getElementById('payment_proof_method_modal');
        const creditCardMethodModal = document.getElementById('credit_card_method_modal');
        const proofSectionModal = document.getElementById('proofPaymentSectionModal');
        const creditCardSectionModal = document.getElementById('creditCardSectionModal');
        const paymentTypeInputModal = document.getElementById('payment_type_modal');
        const paymentDocumentModal = document.getElementById('payment_document_modal');
        const authorizePaymentModal = document.getElementById('authorize_payment_modal');

        if (paymentProofMethodModal && creditCardMethodModal) {
            paymentProofMethodModal.addEventListener('change', function() {
                if (this.checked) {
                    proofSectionModal.classList.remove('d-none');
                    creditCardSectionModal.classList.add('d-none');
                    if (paymentTypeInputModal) paymentTypeInputModal.value = 'proof';
                    if (paymentDocumentModal) paymentDocumentModal.setAttribute('required', 'required');
                    if (authorizePaymentModal) authorizePaymentModal.removeAttribute('required');
                }
            });

            creditCardMethodModal.addEventListener('change', function() {
                if (this.checked) {
                    creditCardSectionModal.classList.remove('d-none');
                    proofSectionModal.classList.add('d-none');
                    if (paymentTypeInputModal) paymentTypeInputModal.value = 'credit_card';
                    if (paymentDocumentModal) paymentDocumentModal.removeAttribute('required');
                    if (authorizePaymentModal) authorizePaymentModal.setAttribute('required', 'required');
                }
            });
        }

        // Update expiry display for modal form
        const expiryMonthModal = document.getElementById('expiry_month_modal');
        const expiryYearModal = document.getElementById('expiry_year_modal');
        const expiryDisplayModal = document.getElementById('expiry_display_modal');

        function updateExpiryDisplayModal() {
            if (expiryMonthModal && expiryYearModal && expiryDisplayModal) {
                const month = expiryMonthModal.value;
                const year = expiryYearModal.value;
                if (month && year) {
                    expiryDisplayModal.value = month + '/' + year;
                } else {
                    expiryDisplayModal.value = '';
                }
            }
        }

        if (expiryMonthModal) {
            expiryMonthModal.addEventListener('change', updateExpiryDisplayModal);
        }
        if (expiryYearModal) {
            expiryYearModal.addEventListener('change', updateExpiryDisplayModal);
        }
    </script>

    <!-- Reupload CI Modal -->
    <div class="modal fade" id="reuploadCIModal" tabindex="-1" aria-labelledby="reuploadCIModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('for-your-action.reupload-ci', $policyApplication->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="reuploadCIModalLabel">
                            <i class="fa fa-upload me-2"></i>Reupload Certificate of Insurance
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This will replace the existing CI document with a new one.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Current File:</label>
                            <p class="text-muted">{{ basename($policyApplication->certificate_document) }}</p>
                        </div>

                        <div class="mb-3">
                            <label for="new_certificate_document" class="form-label fw-bold">
                                New Certificate Document <span class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control" id="new_certificate_document"
                                name="certificate_document" accept=".pdf" required>
                            <small class="text-muted">PDF format only, max 10MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fa fa-upload me-2"></i>Reupload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Remove CI Modal -->
    <div class="modal fade" id="removeCIModal" tabindex="-1" aria-labelledby="removeCIModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('for-your-action.remove-ci', $policyApplication->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="removeCIModalLabel">
                            <i class="fa fa-trash me-2"></i>Remove Certificate of Insurance
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            <strong>Danger:</strong> This action cannot be undone!
                        </div>

                        <p>Are you sure you want to remove the Certificate of Insurance document?</p>

                        <div class="mb-3">
                            <label class="form-label fw-bold">File to be removed:</label>
                            <p class="text-muted">{{ basename($policyApplication->certificate_document) }}</p>
                        </div>

                        <p class="text-danger"><strong>Note:</strong> The file will be permanently deleted from the
                            server.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-trash me-2"></i>Remove Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
