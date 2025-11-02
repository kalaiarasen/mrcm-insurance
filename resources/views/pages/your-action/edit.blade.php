@extends('layouts.main')

@section('title', 'Edit Policy Application')

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Edit Policy Application</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('for-your-action') }}">For Your Action</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('for-your-action.show', $policyApplication->id) }}">Application Details</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Application</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <!-- Progress Indicator -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 16.66%;" aria-valuenow="16.66" aria-valuemin="0" aria-valuemax="100">
                                Step 1 of 6
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Step 1: Applicant Details</small>
                            <small class="text-muted">Step 2: Healthcare Services</small>
                            <small class="text-muted">Step 3: Pricing Details</small>
                            <small class="text-muted">Step 4: Risk Management</small>
                            <small class="text-muted">Step 5: Insurance History</small>
                            <small class="text-muted">Step 6: Claims Experience</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid default-dashboard">
        <div class="row widget-grid">
            <!-- Hidden field to store policy application ID -->
            <input type="hidden" id="policyApplicationId" value="{{ $policyApplication->id }}">
            
            {{-- Include all 8 step forms directly from new-policy --}}
            {{-- This ensures exact match with the new-policy form structure --}}
            {{-- The JavaScript will pre-populate fields with existing data from $policyApplication --}}
            
            @include('pages.new-policy._forms')
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Store policy data in JavaScript for pre-population
        window.policyData = @json($policyApplication);
    </script>
    @include('pages.new-policy.js._new-policy')
    @include('pages.new-policy.js._health-care')
    @include('pages.your-action.edit-steps.js._edit-policy')
@endsection
