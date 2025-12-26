@extends('layouts.main')

@section('title', 'Dashboard')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
    <style>
        .announcement-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .announcement-item {
            transition: all 0.3s ease;
        }

        .announcement-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .announcement-title {
            font-weight: 600;
        }

        .announcement-description {
            line-height: 1.5;
        }

        .quick-actions .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .quick-actions .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Dashboard</h3>
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
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Default</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid default-dashboard">
        <!-- Welcome Banner with Wallet -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card" style="background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8 order-2 order-lg-1">
                                <h3 class="text-primary mb-3">
                                    {{ $dashboardSetting->welcome_title ?? 'Welcome to MRCM Services!' }}
                                </h3>
                                <p class="text-muted mb-4" style="white-space: pre-line;">
                                    {{ $dashboardSetting->welcome_description ?? 'Welcome to your dashboard!' }}</p>
                                <div class="d-flex gap-3 flex-wrap justify-content-center justify-content-lg-start">
                                    @if ($hasActiveProfessionalIndemnity && !$renewalEligible)
                                        {{-- Has active policy, not eligible for renewal --}}
                                        <button class="btn btn-secondary" disabled
                                            title="You already have an active Professional Indemnity policy">
                                            <i class="fa fa-check-circle me-2"></i>Active Professional Indemnity
                                        </button>
                                    @elseif ($hasActiveProfessionalIndemnity && $renewalEligible)
                                        {{-- Eligible for renewal --}}
                                        <a href="{{ route('new-policy') }}" class="btn btn-warning text-dark">
                                            <i class="fa fa-sync me-2"></i>Renew Professional Indemnity
                                        </a>
                                    @else
                                        {{-- No active policy --}}
                                        <a href="{{ route('new-policy') }}" class="btn btn-primary">
                                            <i class="fa fa-plus me-2"></i>Apply Professional Indemnity
                                        </a>
                                    @endif
                                    <a href="{{ route('customer.products.index') }}" class="btn btn-info">
                                        <i class="fa fa-search me-2"></i>Check our other products
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 order-1 order-lg-2">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <h6 class="text-primary mb-2">WALLET BALANCE</h6>
                                        <h2 class="mb-3">RM {{ number_format($walletAmount, 2) }}</h2>
                                        <p class="text-muted small mb-0">
                                            This amount has no expiry.<br>
                                            You may apply the earned rebate to any of our products.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcements and Action Buttons -->
        <div class="row mb-4">
            <!-- Announcements Section -->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>📢 Latest Announcements</h5>
                    </div>
                    <div class="card-body">
                        @if ($announcements && $announcements->count() > 0)
                            <div class="announcement-list">
                                @foreach ($announcements as $announcement)
                                    <div class="announcement-item mb-3 p-3 border rounded">
                                        <h6 class="announcement-title mb-2">{{ $announcement->title }}</h6>
                                        <p class="announcement-description mb-1">{{ $announcement->description }}</p>
                                        <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa fa-bell-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No announcements available at the moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <!-- Client Code Card -->
                @if(auth()->user()->client_code)
                <div class="card mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-center p-3">
                        <h6 class="text-white mb-2">
                            <i class="fa fa-id-card me-1"></i>Your Client Code
                        </h6>
                        <div class="position-relative d-inline-block">
                            <input type="text" 
                                   id="clientCodeInput" 
                                   value="{{ auth()->user()->client_code }}" 
                                   readonly 
                                   class="form-control text-center fw-bold"
                                   style="background-color: rgba(255,255,255,0.9); border: none; font-size: 1.2rem; letter-spacing: 1px; cursor: pointer;"
                                   onclick="copyClientCode()">
                        </div>
                        <button class="btn btn-light btn-sm mt-2 w-100" onclick="copyClientCode()">
                            <i class="fa fa-copy me-1"></i>Copy Code
                        </button>
                        <small class="text-white d-block mt-2" style="opacity: 0.9;">
                            Share this code with your agent
                        </small>
                    </div>
                </div>
                @endif

                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>⚡ Quick Actions</h5>
                    </div>
                    <div class="card-body quick-actions">
                        <div class="d-grid gap-2">
                            @if ($hasActiveProfessionalIndemnity && !$renewalEligible)
                                {{-- Has active policy, not eligible for renewal --}}
                                <button class="btn btn-secondary" disabled
                                    title="You already have an active Professional Indemnity policy">
                                    <i class="fa fa-check-circle me-2"></i>Active Policy
                                </button>
                            @elseif ($hasActiveProfessionalIndemnity && $renewalEligible)
                                {{-- Eligible for renewal --}}
                                <a href="{{ route('new-policy') }}" class="btn btn-warning text-dark">
                                    <i class="fa fa-sync me-2"></i>Renew Policy
                                </a>
                            @else
                                {{-- No active policy --}}
                                <a href="{{ route('new-policy') }}" class="btn btn-primary">
                                    <i class="fa fa-plus me-2"></i>New Policy Application
                                </a>
                            @endif
                            <a href="{{ route('my-policies.index') }}" class="btn btn-outline-success">
                                <i class="fa fa-file-text me-2"></i>View My Policies
                            </a>
                            <a href="#" class="btn btn-outline-info" data-bs-toggle="modal"
                                data-bs-target="#fileClaimModal">
                                <i class="fa fa-exclamation-triangle me-2"></i>File a Claim
                            </a>
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-user me-2"></i>Update Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row - Widget Statistics (Keep existing) -->
        <div class="row widget-grid">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>Policies</h5>
                        <p class="f-m-light mt-1">View all your insurance policies</p>
                    </div>
                    <div class="card-body">
                        @php
                            $rejectedPolicies = $policies->filter(function ($policy) {
                                return $policy->status === 'rejected' && !empty($policy->remarks);
                            });
                        @endphp

                        @if ($rejectedPolicies->count() > 0)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading"><i class="fa fa-exclamation-triangle me-2"></i>Policy Rejection
                                    Notice</h6>
                                @foreach ($rejectedPolicies as $rejectedPolicy)
                                    <div class="mb-2">
                                        <strong>{{ $rejectedPolicy->reference_number ?? 'Policy #' . $rejectedPolicy->id }}:</strong>
                                        <p class="mb-0">{{ $rejectedPolicy->remarks }}</p>
                                        <a href="{{ route('new-policy', ['edit' => $rejectedPolicy->id]) }}"
                                            class="btn btn-sm btn-warning mt-2">
                                            <i class="fa fa-edit me-1"></i>Edit & Resubmit This Policy
                                        </a>
                                    </div>
                                    @if (!$loop->last)
                                        <hr>
                                    @endif
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($policies->isEmpty())
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i>
                                You have no policies at the moment.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="display table-striped border datatable">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 250px">Policy Info</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Submitted Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($policies as $policy)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="mb-1"><strong>Ref:</strong>
                                                            {{ $policy->reference_number ?? '-' }}</span>

                                                        <span class="text-muted small mb-1">
                                                            <i class="fa fa-briefcase me-1"></i>
                                                            Class:
                                                            @php
                                                                $healthcareService = $policy->user->healthcareService;
                                                                $classValue =
                                                                    $healthcareService->practice_area ??
                                                                    ($healthcareService->service_type ??
                                                                        ($healthcareService->cover_type ?? null));
                                                            @endphp
                                                            {{ $classValue ? ucfirst(str_replace('_', ' ', $classValue)) : 'N/A' }}
                                                        </span>

                                                        @if ($policy->policyPricing)
                                                            <span class="text-muted small mb-1">
                                                                <i class="fa fa-calendar me-1"></i>
                                                                {{ \Carbon\Carbon::parse($policy->policyPricing->policy_start_date)->format('d M Y') }}
                                                                -
                                                                {{ \Carbon\Carbon::parse($policy->policyPricing->policy_expiry_date)->format('d M Y') }}
                                                            </span>
                                                            <span class="text-muted small">
                                                                <i class="fa fa-shield-alt me-1"></i>Limit: RM
                                                                {{ number_format($policy->policyPricing->liability_limit ?? 0) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted small">Pricing Pending</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="align-middle">RM
                                                    {{ number_format($policy->policyPricing->total_payable ?? 0, 2) }}
                                                </td>
                                                <td class="align-middle">
                                                    @if ($policy->customer_status === 'pay_now')
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fa fa-clock me-1"></i>Payment Required
                                                        </span>
                                                    @elseif($policy->customer_status === 'paid')
                                                        <span class="badge bg-success">
                                                            <i class="fa fa-check me-1"></i>Paid
                                                        </span>
                                                    @elseif($policy->customer_status === 'approved')
                                                        <span class="badge bg-info">
                                                            <i class="fa fa-check-circle me-1"></i>Approved
                                                        </span>
                                                    @elseif($policy->customer_status === 'active')
                                                        <span class="badge bg-success">
                                                            <i class="fa fa-check-circle me-1"></i>Active
                                                        </span>
                                                    @elseif($policy->customer_status === 'processing')
                                                        <span class="badge bg-info">
                                                            <i class="fa fa-spinner me-1"></i>Processing
                                                        </span>
                                                    @elseif($policy->status === 'rejected')
                                                        <span class="badge bg-danger">
                                                            <i class="fa fa-times-circle me-1"></i>Rejected
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            {{ ucfirst(str_replace('_', ' ', $policy->customer_status)) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">{{ $policy->created_at->format('d M Y') }}</td>
                                                <td class="align-middle">
                                                    <div class="btn-group">
                                                        @if ($policy->status === 'rejected')
                                                            <a href="{{ route('new-policy', ['edit' => $policy->id]) }}"
                                                                class="btn btn-warning btn-sm">
                                                                <i class="fa fa-edit me-1"></i>Edit & Resubmit
                                                            </a>
                                                        @else
                                                            <a href="{{ route('client-policy.show', $policy->id) }}"
                                                                class="btn btn-primary btn-sm">
                                                                <i class="fa fa-eye me-1"></i>View
                                                                @if ($policy->customer_status === 'pay_now')
                                                                    & Pay
                                                                @endif
                                                            </a>
                                                        @endif

                                                        {{-- Download CI Document --}}
                                                        @if ($policy->certificate_document)
                                                            <a href="{{ Storage::url($policy->certificate_document) }}"
                                                                class="btn btn-sm btn-outline-success" target="_blank"
                                                                title="Download CI">
                                                                <i class="fa fa-download"></i>
                                                                CI
                                                            </a>
                                                        @endif

                                                        {{-- Download Tax Receipt --}}
                                                        @if ($policy->tax_receipt_path)
                                                            <a href="{{ Storage::url($policy->tax_receipt_path) }}"
                                                                class="btn btn-sm btn-outline-info" target="_blank"
                                                                title="Download Tax Receipt">
                                                                <i class="fa fa-download"></i>
                                                                Tax
                                                            </a>
                                                        @endif

                                                        {{-- Download Policy Schedule --}}
                                                        @if ($policy->policy_schedule_path)
                                                            <a href="{{ Storage::url($policy->policy_schedule_path) }}"
                                                                class="btn btn-sm btn-outline-primary" target="_blank"
                                                                title="Download Policy Schedule">
                                                                <i class="fa fa-download"></i>
                                                                Certificate
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quotation Requests Section -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>📋 Other Policies</h5>
                        <p class="f-m-light mt-1">Track your insurance other policies requests</p>
                    </div>
                    <div class="card-body">
                        @if ($quotationRequests->isEmpty())
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i>
                                You have not submitted any quotation requests yet.
                                <a href="{{ route('customer.products.index') }}" class="alert-link text-dark">Browse
                                    products</a>
                                to
                                request a quote.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="display table-striped border datatable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product</th>
                                            <th>Submitted Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quotationRequests as $request)
                                            <tr>
                                                <td><strong>#{{ $request->id }}</strong></td>
                                                <td>{{ $request->product->title ?? 'N/A' }}</td>
                                                <td>{{ $request->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <span class="badge {{ $request->status_badge }}">
                                                        {{ $request->status_name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('customer.quotations.show', $request->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fa fa-eye me-1"></i>View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Claims Notification</h5>
                            <p class="f-m-light mt-1">Claims and Notifications History</p>
                        </div>
                        <a href="#" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#fileClaimModal">
                            <i class="fa fa-exclamation-triangle me-2"></i>File a Claim
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($claims->count() > 0)
                            <div class="table-responsive">
                                <table class="display table-striped border datatable">
                                    <thead>
                                        <tr>
                                            <th>Claim Title</th>
                                            <th>Policy</th>
                                            <th>Incident Date</th>
                                            <th>Status</th>
                                            <th>Documents</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($claims as $claim)
                                            <tr>
                                                <td><strong>{{ $claim->claim_title }}</strong></td>
                                                <td>
                                                    @if ($claim->policyApplication)
                                                        <small
                                                            class="text-muted">{{ $claim->policyApplication->reference_number ?? 'N/A' }}</small>
                                                    @else
                                                        <span class="badge bg-secondary">No Policy</span>
                                                    @endif
                                                </td>
                                                <td>{{ $claim->incident_date?->format('d M Y') ?? 'N/A' }}</td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'approved' => 'success',
                                                            'rejected' => 'danger',
                                                            'closed' => 'secondary',
                                                        ];
                                                        $statusColor = $statusColors[$claim->status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $statusColor }}">
                                                        {{ ucfirst($claim->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-info">{{ $claim->claimDocuments->count() }}</span>
                                                </td>
                                                <td>{{ $claim->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <ul class="action">
                                                        <li class="edit">
                                                            <a href="{{ route('claims.show', $claim->id) }}"
                                                                title="View">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No claims filed yet.</p>
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#fileClaimModal">
                                    <i class="fa fa-file-alt me-1"></i>File Your First Claim
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid Ends-->

    <!-- File Claim Modal -->
    <div class="modal fade" id="fileClaimModal" tabindex="-1" aria-labelledby="fileClaimModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="fileClaimModalLabel">
                        <i class="fa fa-file-alt me-2"></i>File a Claim
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('claims.store') }}" method="POST" enctype="multipart/form-data"
                    id="fileClaimForm">
                    @csrf
                    <div class="modal-body">
                        @if ($activePoliciesForClaims->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                <strong>No Active Policies Available</strong>
                                <p class="mb-0">You need an active policy from this year to file a claim. Please create
                                    or activate a policy first.</p>
                            </div>
                        @else
                            <!-- Policy Selection -->
                            <div class="mb-3">
                                <label for="policy_id" class="form-label fw-bold">
                                    Select Policy <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="policy_id" name="policy_application_id" required>
                                    <option value="">-- Choose a Policy --</option>
                                    @foreach ($activePoliciesForClaims as $policy)
                                        <option value="{{ $policy->id }}">
                                            {{ $policy->reference_number }} -
                                            @php
                                                $healthcareService = $policy->user->healthcareService;
                                                $classValue =
                                                    $healthcareService->practice_area ??
                                                    ($healthcareService->service_type ??
                                                        ($healthcareService->cover_type ?? null));
                                            @endphp
                                            {{ $classValue ? ucfirst(str_replace('_', ' ', $classValue)) : 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Only active policies created in {{ now()->year }} are
                                    shown</small>
                            </div>

                            <!-- Action Type -->
                            <div class="mb-3">
                                <label for="action" class="form-label fw-bold">
                                    Action <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="action" name="action" required>
                                    <option value="new" selected>New</option>
                                </select>
                                <small class="text-muted">This will be the status of your claim</small>
                            </div>

                            <hr>

                            <!-- Incident Details -->
                            <h6 class="mb-3 text-primary">
                                <i class="fa fa-calendar-alt me-2"></i>Claim Internation Details
                            </h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="incident_date" class="form-label fw-bold">
                                        Incident Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="incident_date" name="incident_date"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="notification_date" class="form-label fw-bold">
                                        Notification Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="notification_date"
                                        name="notification_date" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="claim_title" class="form-label fw-bold">
                                    Claim Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="claim_title" name="claim_title"
                                    placeholder="e.g., Lip paraesthesia after extraction" required>
                            </div>

                            <div class="mb-3">
                                <label for="claim_description" class="form-label fw-bold">
                                    Claim Detail Description <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="claim_description" name="claim_description" rows="4"
                                    placeholder="Describe the incident details..." required></textarea>
                            </div>

                            <hr>

                            <!-- Document Upload -->
                            <h6 class="mb-3 text-primary">
                                <i class="fa fa-upload me-2"></i>Uploaded Claim Internation Documents
                            </h6>

                            <div class="mb-3">
                                <label for="claim_documents" class="form-label fw-bold">
                                    Upload Documents <span class="text-muted">(Optional)</span>
                                </label>
                                <input type="file" class="form-control" id="claim_documents" name="claim_documents[]"
                                    multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">You can upload multiple files (PDF, DOC, DOCX, JPG, PNG). Max 5MB
                                    per file.</small>
                                <div id="fileList" class="mt-2"></div>
                            </div>
                        @endif
                    </div>
                    @if (!$activePoliciesForClaims->isEmpty())
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-info">
                                <i class="fa fa-paper-plane me-2"></i>Submit Claim
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('assets/js/clock.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
    <script src="{{ asset('assets/js/notify/index.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables1.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom2.js') }}"></script>
    <script>
        // Copy Client Code Function
        function copyClientCode() {
            const input = document.getElementById('clientCodeInput');
            
            // Select the text
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices
            
            // Copy to clipboard
            navigator.clipboard.writeText(input.value).then(function() {
                // Show success notification
                showNotification('Client code copied to clipboard!', 'success');
            }).catch(function(err) {
                // Fallback for older browsers
                document.execCommand('copy');
                showNotification('Client code copied to clipboard!', 'success');
            });
        }

        // Show Notification Function
        function showNotification(message, type = 'info') {
            const alertTypes = {
                'success': 'alert-success',
                'error': 'alert-danger',
                'warning': 'alert-warning',
                'info': 'alert-info'
            };

            const alertClass = alertTypes[type] || 'alert-info';
            const iconMap = {
                'success': 'fa-check-circle',
                'error': 'fa-exclamation-circle',
                'warning': 'fa-exclamation-triangle',
                'info': 'fa-info-circle'
            };

            const icon = iconMap[type] || 'fa-info-circle';

            const alertHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <i class="fa ${icon} me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            // Create container if it doesn't exist
            let alertContainer = document.getElementById('notificationContainer');
            if (!alertContainer) {
                alertContainer = document.createElement('div');
                alertContainer.id = 'notificationContainer';
                alertContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px;';
                document.body.appendChild(alertContainer);
            }

            // Add the alert
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = alertHTML;
            const alertElement = tempDiv.firstElementChild;
            alertContainer.appendChild(alertElement);

            // Auto-remove after 3 seconds
            setTimeout(() => {
                alertElement.classList.remove('show');
                alertElement.addEventListener('transitionend', () => {
                    alertElement.remove();
                }, { once: true });
            }, 3000);
        }

        $(document).ready(function() {
            $(".datatable").DataTable({
                "order": [
                    [6, "desc"]
                ] // Sort by Submitted Date column (index 6) in descending order
            });

            // File upload preview
            document.getElementById('claim_documents').addEventListener('change', function(e) {
                const fileList = document.getElementById('fileList');
                fileList.innerHTML = '';

                Array.from(this.files).forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className =
                        'alert alert-light d-flex justify-content-between align-items-center mb-2';
                    fileItem.innerHTML = `
                        <span>
                            <i class="fa fa-file me-2"></i>${file.name}
                            <small class="text-muted ms-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                        </span>
                        <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()">
                            <i class="fa fa-trash"></i>
                        </button>
                    `;
                    fileList.appendChild(fileItem);
                });
            });

            // Set today's date as default for dates
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('incident_date').value = today;
            document.getElementById('notification_date').value = today;
        });
    </script>
@endsection
