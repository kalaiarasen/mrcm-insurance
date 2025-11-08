@extends('layouts.main')

@section('title', 'Policy Holder Details')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dataTables.bootstrap5.css') }}">
    <style>
        .info-card {
            border-left: 3px solid #dee2e6;
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
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
    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Policy Holder Details</h3>
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
                            <a href="{{ route('policy-holder') }}">Policy Holders</a>
                        </li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- User Information Card -->
        <div class="row">
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fa fa-user me-2"></i>Personal Information
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">{{ $user->applicantProfile->title }}. {{ $user->name }}</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="info-label">Contact Number</div>
                                <div class="info-value">{{ $user->contact_no }}</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="info-label">Gender</div>
                                <div class="info-value">{{ ucfirst($user->applicantProfile->gender ?? '-') }}</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="info-label">Nationality Status</div>
                                <div class="info-value">
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $user->applicantProfile->nationality_status ?? '-')) }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="info-label">NRIC Number</div>
                                <div class="info-value">{{ $user->applicantProfile->nric_number ?? '-' }}</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="info-label">Passport Number</div>
                                <div class="info-value">{{ $user->applicantProfile->passport_number ?? '-' }}</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="info-label">Registered Date</div>
                                <div class="info-value">{{ $user->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Policy Applications History -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Policy Applications History</h5>
                                <p class="f-m-light mt-1">Total applications: {{ $policyApplications->count() }}</p>
                            </div>
                            <div>
                                <a href="{{ route('policy-holder') }}" class="btn btn-light">
                                    <i class="fa fa-arrow-left me-1"></i>Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($policyApplications->count() > 0)
                            <div class="table-responsive">
                                <table class="display table-striped border datatable">
                                    <thead>
                                        <tr>
                                            <th>Reference No</th>
                                            <th>Submission Date</th>
                                            <th>Type</th>
                                            <th>Professional Type</th>
                                            <th>Liability Limit</th>
                                            <th>Total Payable</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($policyApplications as $application)
                                            <tr>
                                                <td>
                                                    <strong>{{ $application->reference_number }}</strong>
                                                </td>
                                                <td>
                                                    {{ $application->created_at->format('d M Y') }}<br>
                                                    <small class="text-muted">{{ $application->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    @if($application->submission_version == 0)
                                                        <span class="badge bg-primary">New</span>
                                                    @else
                                                        <span class="badge bg-warning">Renewal</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($application->healthcareService)
                                                        {{ ucfirst(str_replace('_', ' ', $application->healthcareService->professional_indemnity_type ?? '-')) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($application->policyPricing && $application->policyPricing->liability_limit)
                                                        RM {{ number_format($application->policyPricing->liability_limit, 0) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($application->policyPricing)
                                                        <strong>RM {{ number_format($application->policyPricing->total_payable ?? 0, 2) }}</strong>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $statusMap = [
                                                            'pending' => ['badge' => 'bg-warning', 'text' => 'Pending'],
                                                            'pay_now' => ['badge' => 'bg-info', 'text' => 'Pay Now'],
                                                            'paid' => ['badge' => 'bg-success', 'text' => 'Paid'],
                                                            'sent_uw' => ['badge' => 'bg-primary', 'text' => 'Sent to UW'],
                                                            'active' => ['badge' => 'bg-success', 'text' => 'Active'],
                                                            'rejected' => ['badge' => 'bg-danger', 'text' => 'Rejected'],
                                                        ];
                                                        $adminStatus = $application->admin_status ?? 'pending';
                                                        $statusInfo = $statusMap[$adminStatus] ?? ['badge' => 'bg-secondary', 'text' => ucfirst($adminStatus)];
                                                    @endphp
                                                    <span class="badge {{ $statusInfo['badge'] }}">{{ $statusInfo['text'] }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('policy-holders.application.show', ['user' => $user->id, 'application' => $application->id]) }}" class="btn btn-sm btn-primary" title="View Details">
                                                        <i class="fa fa-eye me-1"></i>View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i>
                                No policy applications found for this user.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.bootstrap5.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".datatable").DataTable({
                order: [[1, 'desc']] // Sort by submission date descending
            });
        });
    </script>
@endsection
