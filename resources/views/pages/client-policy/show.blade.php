@extends('layouts.main')

@section('title', 'Policy Application Details')

@section('css')
    <style>
        /* Base Styles - Using CSS Variables for Theme Support */
        .info-card {
            border-left: 3px solid #dee2e6;
            transition: all 0.3s ease;
            background-color: var(--card-color);
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
            h4, h5, h6, p {
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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i>
                <strong>Validation Errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
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
                                <h4 class="mb-2">{{ $policyApplication->user->name ?? 'N/A' }}</h4>
                                <p class="mb-1"><i class="fa fa-envelope me-2"></i>{{ $policyApplication->user->email ?? 'N/A' }}</p>
                                <p class="mb-1"><i class="fa fa-phone me-2"></i>{{ $policyApplication->user->contact_no ?? 'N/A' }}</p>
                                @if($policyApplication->reference_number)
                                    <p class="mb-0"><i class="fa fa-file-alt me-2"></i><strong>Reference:</strong> {{ $policyApplication->reference_number }}</p>
                                @endif
                            </div>
                            <div class="col-md-4 text-end">
                                @if($policyApplication->status === 'approved')
                                    <span class="status-badge badge bg-success">Approved</span>
                                @elseif($policyApplication->status === 'submitted')
                                    <span class="status-badge badge bg-info">Submitted</span>
                                @elseif($policyApplication->status === 'rejected')
                                    <span class="status-badge badge bg-danger">Rejected</span>
                                @else
                                    <span class="status-badge badge bg-secondary">{{ ucfirst($policyApplication->status) }}</span>
                                @endif
                                <p class="mt-3 mb-0 text-muted">
                                    <small>Submitted: {{ $policyApplication->submitted_at ? $policyApplication->submitted_at->format('d M Y, h:i A') : 'N/A' }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $profile = $policyApplication->user->applicantProfile;
                $contact = $policyApplication->user->applicantContact;
                $addresses = $policyApplication->user->addresses;
                $qualifications = $policyApplication->user->qualifications;
                $healthcare = $policyApplication->user->healthcareService;
                $pricing = $policyApplication->user->policyPricing;
                $risk = $policyApplication->user->riskManagement;
                $insurance = $policyApplication->user->insuranceHistory;
                $claims = $policyApplication->user->claimsExperience;
                
                // Helper function to format field names properly
                function formatFieldName($value) {
                    if (!$value) return 'N/A';
                    
                    // Special cases for proper display
                    $specialCases = [
                        'general_dental_practice' => 'General Dentist Practice',
                        'general_dental_practitioners' => 'General Dentist Practice, practicing accredited specialised procedures',
                        'clinic_based_non_general_anaesthetic' => 'Clinic based Non-General Anaesthetic Dental only procedures',
                        'hospital_based_full_fledged_omfs' => 'Hospital-based full-fledged OMFS',
                        'general_practice_with_specialized_procedures' => 'General Practice with Specialized Procedures',
                        'locum_cover_only' => 'Locum Cover Only',
                        'dental_specialists' => 'Dental Specialists',
                        'dental_specialist_oral_maxillofacial_surgery' => 'Dental Specialist practicing Oral and Maxillofacial Surgery (OMFS)',
                        'dentist_specialist' => 'Dentist Specialist',
                        'general_dentist' => 'General Dentist',
                        'medical_specialist' => 'Medical Specialist',
                        'general_practitioner' => 'General Practitioner',
                        'low_risk_specialist' => 'Low Risk Specialist',
                        'medium_risk_specialist' => 'Medium Risk Specialist',
                        'lecturer_trainee' => 'Lecturer/Trainee',
                        'non_practicing' => 'Non-Practicing',
                        'dental_practice' => 'Dental Practice',
                        'medical_practice' => 'Medical Practice',
                        'private_clinic' => 'Private Clinic',
                        'private_hospital' => 'Private Hospital',
                    ];
                    
                    return $specialCases[$value] ?? ucfirst(str_replace('_', ' ', $value));
                }
            @endphp

            <!-- Step 1: Applicant Details -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fa fa-user me-2"></i>Step 1: Details of the Applicant
                        </h5>

                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fa fa-id-card me-2"></i>Personal Information</h6>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Title</div>
                                <div class="info-value">{{ $profile ? strtoupper($profile->title) : 'N/A' }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">{{ $policyApplication->user->name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Nationality Status</div>
                                <div class="info-value">{{ $profile ? ucfirst(str_replace('_', ' ', $profile->nationality_status)) : 'N/A' }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Gender</div>
                                <div class="info-value">{{ $profile ? ucfirst($profile->gender) : 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="info-label">NRIC Number</div>
                                <div class="info-value">{{ $profile && $profile->nric_number ? $profile->nric_number : 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Passport Number</div>
                                <div class="info-value">{{ $profile && $profile->passport_number ? $profile->passport_number : 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Contact Number</div>
                                <div class="info-value">{{ $contact ? $contact->contact_no : 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Addresses -->
                        @foreach(['mailing' => 'Mailing Address', 'primary_clinic' => 'Primary Practicing Address', 'secondary_clinic' => 'Secondary Practicing Address'] as $type => $label)
                            @php
                                $address = $addresses->firstWhere('type', $type);
                            @endphp
                            @if($address && ($address->address || $address->clinic_name))
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3"><i class="fa fa-map-marker-alt me-2"></i>{{ $label }}</h6>
                                    </div>
                                    @if($type !== 'mailing')
                                        <div class="col-md-3">
                                            <div class="info-label">Type</div>
                                            <div class="info-value">{{ $address->clinic_type ? ucfirst($address->clinic_type) : 'N/A' }}</div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="info-label">Clinic/Hospital Name</div>
                                            <div class="info-value">{{ $address->clinic_name ?? 'N/A' }}</div>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="info-label">Address</div>
                                        <div class="info-value">{{ $address->address ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-label">Postcode</div>
                                        <div class="info-value">{{ $address->postcode ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-label">City</div>
                                        <div class="info-value">{{ $address->city ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-label">State</div>
                                        <div class="info-value">{{ $address->state ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <!-- Qualifications -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fa fa-graduation-cap me-2"></i>Qualifications</h6>
                            </div>
                            @forelse($qualifications as $index => $qual)
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h6 class="text-secondary">Qualification {{ $index + 1 }}</h6>
                                            <div class="info-label">Institution</div>
                                            <div class="info-value">{{ $qual->institution }}</div>
                                            <div class="info-label">Degree/Qualification</div>
                                            <div class="info-value">{{ $qual->degree_or_qualification }}</div>
                                            <div class="info-label">Year Obtained</div>
                                            <div class="info-value">{{ $qual->year_obtained }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-muted">No qualifications recorded.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Registration Details -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fa fa-id-badge me-2"></i>Registration Details</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Registration Council</div>
                                <div class="info-value">
                                    @if($profile)
                                        @if($profile->registration_council === 'mmc')
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
                            <div class="col-md-4">
                                <div class="info-label">Registration Number</div>
                                <div class="info-value">{{ $profile ? $profile->registration_number : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Healthcare Services -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fa fa-hospital me-2"></i>Step 2: Details of Healthcare Services Business
                        </h5>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-label">Professional Indemnity Type</div>
                                <div class="info-value">{{ $healthcare ? formatFieldName($healthcare->professional_indemnity_type) : 'N/A' }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Employment Status</div>
                                <div class="info-value">{{ $healthcare ? formatFieldName($healthcare->employment_status) : 'N/A' }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Specialty Area</div>
                                <div class="info-value">{{ $healthcare && $healthcare->specialty_area ? formatFieldName($healthcare->specialty_area) : 'N/A' }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Cover Type</div>
                                <div class="info-value">{{ $healthcare && $healthcare->cover_type ? formatFieldName($healthcare->cover_type) : 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="info-label">Service Type</div>
                                <div class="info-value">{{ $healthcare && $healthcare->service_type ? formatFieldName($healthcare->service_type) : 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Practice Area</div>
                                <div class="info-value">{{ $healthcare && $healthcare->practice_area ? formatFieldName($healthcare->practice_area) : 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Locum Practice Location</div>
                                <div class="info-value">{{ $healthcare && $healthcare->locum_practice_location ? formatFieldName($healthcare->locum_practice_location) : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Pricing Details -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fa fa-dollar-sign me-2"></i>Step 3: Pricing Details
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="info-label">Policy Start Date</div>
                                <div class="info-value">{{ $pricing && $pricing->policy_start_date ? \Carbon\Carbon::parse($pricing->policy_start_date)->format('d M Y') : 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Policy Expiry Date</div>
                                <div class="info-value">{{ $pricing && $pricing->policy_expiry_date ? \Carbon\Carbon::parse($pricing->policy_expiry_date)->format('d M Y') : 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Liability Limit</div>
                                <div class="info-value">
                                    @if($pricing && $pricing->liability_limit)
                                        RM {{ number_format($pricing->liability_limit, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($pricing)
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="text-center mb-3"><strong>Pricing Breakdown</strong></h6>
                                            
                                            <div class="pricing-row d-flex justify-content-between info-label">
                                                <span>Premium Per Annum</span>
                                                <span>RM {{ number_format($pricing->base_premium ?? 0, 2) }}</span>
                                            </div>
                                            
                                            <div class="pricing-row d-flex justify-content-between info-label">
                                                <span>Gross Premium</span>
                                                <span>RM {{ number_format($pricing->gross_premium ?? 0, 2) }}</span>
                                            </div>
                                            
                                            @if($pricing->locum_addon > 0)
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
                                            
                                            <div class="pricing-row d-flex justify-content-between info-label">
                                                <span>Total Payable</span>
                                                <span>RM {{ number_format($pricing->total_payable ?? 0, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Step 4: Risk Management -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fa fa-shield-alt me-2"></i>Step 4: Risk Management
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="info-label">Maintains Accurate Medical Records?</div>
                                    </div>
                                    <div>
                                        @if($risk && $risk->medical_records)
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
                                        @if($risk && $risk->informed_consent)
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
                                        @if($risk && $risk->adverse_incidents)
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
                                        @if($risk && $risk->sterilisation_facilities)
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

            <!-- Step 5: Insurance History -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fa fa-history me-2"></i>Step 5: Insurance History
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="info-label">Currently Holds Medical Malpractice Insurance?</div>
                                <div class="info-value">
                                    @if($insurance && $insurance->current_insurance)
                                        <span class="badge badge-yes">YES</span>
                                    @else
                                        <span class="badge badge-no">NO</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Previous Insurance Refused/Cancelled?</div>
                                <div class="info-value">
                                    @if($insurance && $insurance->previous_claims)
                                        <span class="badge badge-yes">YES</span>
                                    @else
                                        <span class="badge badge-no">NO</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($insurance && $insurance->current_insurance && $insurance->insurer_name)
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

                        @if($insurance && $insurance->previous_claims && $insurance->claims_details)
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

            <!-- Step 6: Claims Experience -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fa fa-exclamation-triangle me-2"></i>Step 6: Claims Experience
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="info-label">Claims Ever Made?</div>
                                <div class="info-value">
                                    @if($claims && $claims->claims_made)
                                        <span class="badge badge-yes">YES</span>
                                    @else
                                        <span class="badge badge-no">NO</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Aware of Errors/Omissions?</div>
                                <div class="info-value">
                                    @if($claims && $claims->aware_of_errors)
                                        <span class="badge badge-yes">YES</span>
                                    @else
                                        <span class="badge badge-no">NO</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Subject to Disciplinary Action?</div>
                                <div class="info-value">
                                    @if($claims && $claims->disciplinary_action)
                                        <span class="badge badge-yes">YES</span>
                                    @else
                                        <span class="badge badge-no">NO</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($claims && ($claims->claims_made || $claims->aware_of_errors || $claims->disciplinary_action))
                            @if($claims->claim_claimant_name || $claims->claim_allegations || $claims->claim_amount_claimed)
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

            <!-- Step 7 & 8: Declarations and Signature -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fa fa-file-signature me-2"></i>Step 7 & 8: Declarations and Signature
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-label">Data Protection Notice Agreed?</div>
                                <div class="info-value">
                                    @if($policyApplication->agree_data_protection)
                                        <span class="badge badge-yes"><i class="fa fa-check"></i> AGREED</span>
                                    @else
                                        <span class="badge badge-no"><i class="fa fa-times"></i> NOT AGREED</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Final Declaration Agreed?</div>
                                <div class="info-value">
                                    @if($policyApplication->agree_declaration)
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
                                    @if($policyApplication->signature_data)
                                        <img src="{{ $policyApplication->signature_data }}" alt="Signature">
                                    @else
                                        <span class="text-muted">No signature available</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Upload Section -->
            <div class="col-12">
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fa fa-credit-card me-2"></i>Payment Information
                        </h5>

                        <!-- Policy Status Display -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <h6 class="mb-2"><i class="fa fa-info-circle me-2"></i>Policy Status</h6>
                                    <p class="mb-1"><strong>Reference Number:</strong> {{ $policyApplication->reference_number ?? 'N/A' }}</p>
                                    <p class="mb-1">
                                        <strong>Current Status:</strong> 
                                        @php
                                            $customerStatusBadges = [
                                                'submitted' => ['bg-secondary', 'Submitted'],
                                                'pay_now' => ['bg-warning text-dark', 'Payment Required'],
                                                'paid' => ['bg-info', 'Payment Received'],
                                                'processing' => ['bg-primary', 'Processing'],
                                                'active' => ['bg-success', 'Active'],
                                            ];
                                            $cs = $customerStatusBadges[$policyApplication->customer_status] ?? ['bg-secondary', ucfirst($policyApplication->customer_status)];
                                        @endphp
                                        <span class="badge {{ $cs[0] }}">{{ $cs[1] }}</span>
                                    </p>
                                    <p class="mb-0"><strong>Total Amount Due:</strong> <span class="text-success fs-5">RM {{ number_format($policyApplication->user->policyPricing->total_payable ?? 0, 2) }}</span></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-warning">
                                    <h6 class="mb-2"><i class="fa fa-exclamation-triangle me-2"></i>Payment Instructions</h6>
                                    <p class="mb-1">1. Make payment to the account details provided</p>
                                    <p class="mb-1">2. Upload your payment receipt/proof below</p>
                                    <p class="mb-0">3. Your policy will be processed once payment is verified</p>
                                </div>
                            </div>
                        </div>

                        @if($policyApplication->customer_status === 'pay_now')
                            <!-- Payment Upload Form -->
                            <form action="{{ route('client-policy.upload-payment', $policyApplication->id) }}" method="POST" enctype="multipart/form-data" id="paymentUploadForm">
                                @csrf
                                
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label for="payment_document" class="form-label fw-bold">
                                            <i class="fa fa-upload me-2"></i>Upload Payment Proof <span class="text-danger">*</span>
                                        </label>
                                        <input type="file" class="form-control" id="payment_document" name="payment_document" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <small class="text-muted">Accepted formats: PDF, JPG, JPEG, PNG (Max: 5MB)</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-end">
                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                                            <i class="fa fa-arrow-left me-2"></i>Back to Dashboard
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-check me-2"></i>Submit Payment Proof
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @elseif($policyApplication->payment_document)
                            <!-- Payment Already Uploaded -->
                            <div class="alert alert-success">
                                <h6 class="mb-2"><i class="fa fa-check-circle me-2"></i>Payment Document Uploaded</h6>
                                <p class="mb-1">Your payment proof has been received and is being processed.</p>
                                <p class="mb-1"><strong>Uploaded:</strong> {{ $policyApplication->payment_received_at ? $policyApplication->payment_received_at->format('d M Y, h:i A') : 'N/A' }}</p>
                                <a href="{{ Storage::url($policyApplication->payment_document) }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                                    <i class="fa fa-eye me-1"></i>View Payment Document
                                </a>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 text-end">
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                        <i class="fa fa-arrow-left me-2"></i>Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
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
        });
    </script>
@endsection
