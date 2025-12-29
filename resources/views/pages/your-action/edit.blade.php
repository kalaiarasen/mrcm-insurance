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
        
        // CRITICAL: Set up edit mode BEFORE loading any other scripts
        window.isEditMode = true;
        window.editModeData = {
            step1: {},
            step2: {},
            step3: {},
            step4: {},
            step5: {},
            step6: {}
        };
        
        // Track if Step 2 has been modified
        window.step2Modified = false;
        
        // Override loadFormData and saveFormData BEFORE new-policy scripts load
        window.loadFormData = function(step) {
            if (window.isEditMode && window.editModeData) {
                const stepKey = `step${step}`;
                const data = window.editModeData[stepKey] || {};
                console.log(`[Edit] loadFormData(${step}):`, data);
                return data;
            }
            const key = `newPolicyStep${step}`;
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : {};
        };
        
        window.saveFormData = function(step, data) {
            if (window.isEditMode && window.editModeData) {
                const stepKey = `step${step}`;
                window.editModeData[stepKey] = data;
                console.log(`[Edit] saveFormData(${step}):`, data);
                return;
            }
            const key = `newPolicyStep${step}`;
            localStorage.setItem(key, JSON.stringify(data));
        };
        
        // Override updateExpiryDate with July 1st logic for edit mode
        window.updateExpiryDate = function() {
            const policyStartDateInput = document.getElementById('policyStartDate');
            const policyExpiryDateInput = document.getElementById('policyExpiryDate');

            const startDateInput = policyStartDateInput.value;
            
            if (startDateInput) {
                // Parse the start date
                const startDate = new Date(startDateInput);
                const startYear = startDate.getFullYear();
                const startMonth = startDate.getMonth(); // 0-based (0 = January, 6 = July)
                const startDay = startDate.getDate();
                
                // Logic: If start date is July 1st or later, expiry = Dec 31 NEXT year
                //        If start date is before July 1st, expiry = Dec 31 SAME year
                let expiryYear;
                if (startMonth > 6 || (startMonth === 6 && startDay >= 1)) {
                    // On or after July 1st: next year
                    expiryYear = startYear + 1;
                } else {
                    // Before July 1st: same year
                    expiryYear = startYear;
                }
                
                // Set expiry date to December 31st
                policyExpiryDateInput.value = `${expiryYear}-12-31`;
                
                console.log('[Edit] updateExpiryDate - Start:', startDateInput, 'Expiry:', policyExpiryDateInput.value);
            }
        };
        
        // Override setupPricingCalculations to prevent auto-setting dates in edit mode
        window.setupPricingCalculations = function() {
            const policyStartDate = document.getElementById('policyStartDate');
            const policyExpiryDate = document.getElementById('policyExpiryDate');
            const liabilityLimit = document.getElementById('liabilityLimit');
            const locumExtension = document.getElementById('locumExtension');
            const toggleLocumExtensionBtn = document.getElementById('toggleLocumExtensionBtn');

            // In edit mode, DO NOT auto-set dates - preserve existing dates from database
            // Only set up event listeners once
            if (!window.pricingCalculationsSetup) {
                if (policyStartDate) {
                    policyStartDate.addEventListener('change', function() {
                        // Auto-update expiry date based on July 1st logic
                        if (typeof window.updateExpiryDate === 'function') {
                            window.updateExpiryDate();
                        }
                        if (typeof window.calculatePremium === 'function') {
                            window.calculatePremium();
                        }
                    });
                }

                if (policyExpiryDate) {
                    policyExpiryDate.addEventListener('change', function() {
                        if (typeof window.calculatePremium === 'function') {
                            window.calculatePremium();
                        }
                    });
                }

                if (liabilityLimit) {
                    liabilityLimit.addEventListener('change', function() {
                        if (typeof window.calculatePremium === 'function') {
                            window.calculatePremium();
                        }
                    });
                }

                if (locumExtension) {
                    locumExtension.addEventListener('change', function() {
                        if (typeof window.calculatePremium === 'function') {
                            window.calculatePremium();
                        }
                    });
                }

                if (toggleLocumExtensionBtn && locumExtension) {
                    toggleLocumExtensionBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        locumExtension.checked = !locumExtension.checked;
                        locumExtension.dispatchEvent(new Event('change'));
                    });
                }

                window.pricingCalculationsSetup = true;
            }
        };
    </script>
    @include('pages.new-policy.js._new-policy')
    @include('pages.new-policy.js._health-care')
    <script>
        // Wrap calculatePremium with safety checks for edit mode
        if (window.isEditMode && typeof window.calculatePremium === 'function') {
            const originalCalculatePremium = window.calculatePremium;
            window.calculatePremium = function() {
                try {
                    // Verify all required elements exist
                    const liabilityLimit = document.getElementById('liabilityLimit');
                    const policyStartDate = document.getElementById('policyStartDate');
                    const policyExpiryDate = document.getElementById('policyExpiryDate');
                    
                    if (!liabilityLimit || !policyStartDate || !policyExpiryDate) {
                        console.warn('[Edit] calculatePremium skipped - missing required elements');
                        return;
                    }
                    
                    if (!liabilityLimit.value || !policyStartDate.value || !policyExpiryDate.value) {
                        console.warn('[Edit] calculatePremium skipped - missing required values');
                        return;
                    }
                    
                    // Ensure required hidden elements exist (required by calculatePremium and updatePricingUI)
                    const requiredHiddenInputs = [
                        'voucherCodeApplied',
                        'voucherCodeInput',
                        'displayLoadingPercentageInput',
                        'displayLoadingAmountInput',
                        'displayDiscountPercentageInput',
                        'displayDiscountAmountInput'
                    ];
                    
                    requiredHiddenInputs.forEach(inputId => {
                        let input = document.getElementById(inputId);
                        if (!input) {
                            // Create hidden input if it doesn't exist
                            input = document.createElement('input');
                            input.type = 'hidden';
                            input.id = inputId;
                            input.value = '';
                            document.body.appendChild(input);
                        }
                    });
                    
                    // Call original function
                    originalCalculatePremium.call(this);
                } catch (error) {
                    console.error('[Edit] Error in calculatePremium:', error);
                }
            };
        }
    </script>
    @include('pages.your-action.edit-steps.js._edit-policy')
@endsection
