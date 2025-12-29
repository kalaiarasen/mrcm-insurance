<script>
    /**
     * Edit Policy Application JavaScript  
     * This file directly populates form fields from server data
     * WITHOUT using localStorage to avoid conflicts between different users
     * 
     * NOTE: window.isEditMode, window.editModeData, and the override functions
     * are defined in edit.blade.php BEFORE this script loads
     */

    // Get the policy application ID
    const policyApplicationId = document.getElementById('policyApplicationId')?.value || null;
    
    // Flag to prevent change events during initial population from marking Step 2 as modified
    let isInitialPopulation = true;

    /**
     * Directly populate form fields from policyData
     * This bypasses localStorage completely
     */
    function directlyPopulateFields() {
        if (!window.policyData) {
            console.error('[Edit] No policyData found in window');
            return;
        }

        const data = window.policyData;
        console.log('[Edit] Directly populating fields with data:', data);
        console.log('[Edit] user.applicant_profile:', data.user?.applicant_profile);
        console.log('[Edit] Title value:', data.user?.applicant_profile?.title);
        console.log('[Edit] Gender value:', data.user?.applicant_profile?.gender);
        
        // Helper function to find address by type
        const findAddress = (type) => {
            return data.user?.addresses?.find(addr => addr.type === type);
        };

        // Helper function to find qualification by sequence
        const findQualification = (sequence) => {
            return data.user?.qualifications?.find(qual => qual.sequence === sequence);
        };

        // Helper to get relationship data (handles both camelCase and snake_case)
        const getRelation = (obj, camelCase, snakeCase) => {
            return obj?.[camelCase] || obj?.[snakeCase];
        };

        // Helper to set field value
        const setFieldValue = (name, value) => {
            if (value === null || value === undefined || value === '') {
                console.log(`[Edit] Skipping empty value for ${name}`);
                return;
            }
            
            const elements = document.getElementsByName(name);
            if (elements.length === 0) {
                console.warn(`[Edit] No elements found with name="${name}"`);
                return;
            }

            console.log(`[Edit] Setting ${name} = ${value}, found ${elements.length} elements`);

            elements.forEach(el => {
                if (el.type === 'radio') {
                    // Convert both to lowercase for case-insensitive comparison
                    if (el.value.toLowerCase() === String(value).toLowerCase()) {
                        el.checked = true;
                        console.log(`[Edit] Checked radio ${name} with value ${value}`);
                    }
                } else if (el.type === 'checkbox') {
                    el.checked = (value === true || value === 'true' || value === 1 || value === '1' || value === 'yes');
                    console.log(`[Edit] Set checkbox ${name} to ${el.checked}`);
                } else if (el.tagName === 'SELECT') {
                    // Try lowercase first (for title, gender fields), then original value
                    const lowerValue = String(value).toLowerCase().replace(/\./g, '');
                    el.value = lowerValue;
                    if (!el.value) {
                        el.value = value; // Fallback to original
                    }
                    // Trigger change event for select elements
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                    console.log(`[Edit] Set select ${name} to ${value} (tried: ${lowerValue}, result: ${el.value})`);
                } else {
                    el.value = value;
                    console.log(`[Edit] Set ${el.type} ${name} to ${value}`);
                }
            });
        };

        // Get addresses
        const mailingAddress = findAddress('mailing');
        const primaryAddress = findAddress('primary_clinic');
        const secondaryAddress = findAddress('secondary_clinic');
        
        console.log('[Edit] Addresses:', { mailingAddress, primaryAddress, secondaryAddress });
        console.log('[Edit] Primary clinic_type:', primaryAddress?.clinic_type);
        console.log('[Edit] Secondary clinic_type:', secondaryAddress?.clinic_type);

        // Get qualifications
        const qual1 = findQualification(1);
        const qual2 = findQualification(2);
        const qual3 = findQualification(3);

        // Step 1: Applicant Details
        const applicantProfile = data.user?.applicant_profile || data.user?.applicantProfile;
        const applicantContact = data.user?.applicant_contact || data.user?.applicantContact;
        
        console.log('[Edit] Setting Step 1 fields...');
        console.log('[Edit] applicantProfile:', applicantProfile);
        
        setFieldValue('title', applicantProfile?.title);
        setFieldValue('full_name', data.user?.name);
        setFieldValue('nationality_status', applicantProfile?.nationality_status);
        setFieldValue('nric_number', applicantProfile?.nric_number);
        setFieldValue('passport_number', applicantProfile?.passport_number);
        setFieldValue('gender', applicantProfile?.gender);
        setFieldValue('contact_no', applicantContact?.contact_no);
        setFieldValue('email_address', data.user?.email);
        setFieldValue('mailing_address', mailingAddress?.address);
        setFieldValue('mailing_postcode', mailingAddress?.postcode);
        setFieldValue('mailing_city', mailingAddress?.city);
        setFieldValue('mailing_state', mailingAddress?.state);
        setFieldValue('mailing_country', mailingAddress?.country);
        setFieldValue('primary_clinic_type', primaryAddress?.clinic_type);
        setFieldValue('primary_clinic_name', primaryAddress?.clinic_name);
        setFieldValue('primary_address', primaryAddress?.address);
        setFieldValue('primary_postcode', primaryAddress?.postcode);
        setFieldValue('primary_city', primaryAddress?.city);
        setFieldValue('primary_state', primaryAddress?.state);
        setFieldValue('primary_country', primaryAddress?.country);
        setFieldValue('secondary_clinic_type', secondaryAddress?.clinic_type);
        setFieldValue('secondary_clinic_name', secondaryAddress?.clinic_name);
        setFieldValue('secondary_address', secondaryAddress?.address);
        setFieldValue('secondary_postcode', secondaryAddress?.postcode);
        setFieldValue('secondary_city', secondaryAddress?.city);
        setFieldValue('secondary_state', secondaryAddress?.state);
        setFieldValue('secondary_country', secondaryAddress?.country);
        setFieldValue('institution_1', qual1?.institution);
        setFieldValue('qualification_1', qual1?.degree_or_qualification);
        setFieldValue('year_obtained_1', qual1?.year_obtained);
        setFieldValue('institution_2', qual2?.institution);
        setFieldValue('qualification_2', qual2?.degree_or_qualification);
        setFieldValue('year_obtained_2', qual2?.year_obtained);
        setFieldValue('institution_3', qual3?.institution);
        setFieldValue('qualification_3', qual3?.degree_or_qualification);
        setFieldValue('year_obtained_3', qual3?.year_obtained);
        setFieldValue('registration_council', data.user?.applicant_profile?.registration_council);
        setFieldValue('other_council', data.user?.applicant_profile?.other_council);
        setFieldValue('registration_number', data.user?.applicant_profile?.registration_number);

        // Step 2: Healthcare Services - Populate in sequence to trigger cascading updates
        const healthcareService = getRelation(data.user, 'healthcareService', 'healthcare_service');
        
        // Save Step 2 data to in-memory store (NOT localStorage)
        if (healthcareService) {
            window.editModeData.step2 = {
                professional_indemnity_type: healthcareService.professional_indemnity_type,
                employment_status: healthcareService.employment_status,
                specialty_area: healthcareService.specialty_area,
                cover_type: healthcareService.cover_type,
                locum_practice_location: healthcareService.locum_practice_location,
                service_type: healthcareService.service_type,
                practice_area: healthcareService.practice_area
            };
        }
        
        // Populate professional_indemnity_type first
        if (healthcareService?.professional_indemnity_type) {
            setFieldValue('professional_indemnity_type', healthcareService.professional_indemnity_type);
            // Wait for the change event to complete before continuing
            setTimeout(() => {
                // Then populate employment_status
                if (healthcareService?.employment_status) {
                    setFieldValue('employment_status', healthcareService.employment_status);
                    setTimeout(() => {
                        // Then populate specialty_area
                        if (healthcareService?.specialty_area) {
                            setFieldValue('specialty_area', healthcareService.specialty_area);
                            setTimeout(() => {
                                // Then populate cover_type
                                if (healthcareService?.cover_type) {
                                    setFieldValue('cover_type', healthcareService.cover_type);
                                    setTimeout(() => {
                                        // Finally populate remaining fields
                                        setFieldValue('locum_practice_location', healthcareService?.locum_practice_location);
                                        setFieldValue('service_type', healthcareService?.service_type);
                                        setFieldValue('practice_area', healthcareService?.practice_area);
                                    }, 100);
                                }
                            }, 100);
                        }
                    }, 100);
                }
            }, 100);
        }

        // Step 3: Pricing Details - Save to in-memory store AND populate fields immediately
        const policyPricing = getRelation(data, 'policyPricing', 'policy_pricing');
        console.log('[Edit] Step 3 - Policy Pricing Object:', policyPricing);
        console.log('[Edit] Step 3 - policy_start_date from DB:', policyPricing?.policy_start_date);
        console.log('[Edit] Step 3 - policy_expiry_date from DB:', policyPricing?.policy_expiry_date);
        
        if (policyPricing) {
            const liabilityLimit = policyPricing.liability_limit ? String(Math.round(parseFloat(policyPricing.liability_limit))) : '';
            console.log('[Edit] Step 3 - Saving liability_limit:', liabilityLimit, 'Type:', typeof liabilityLimit);
            
            window.editModeData.step3 = {
                policy_start_date: policyPricing.policy_start_date,
                policy_expiry_date: policyPricing.policy_expiry_date,
                liability_limit: liabilityLimit,
                locum_extension: policyPricing.locum_extension || false
            };
            
            console.log('[Edit] Step 3 - Saved to editModeData.step3:', window.editModeData.step3);
            
            // Populate dates IMMEDIATELY so setupPricingCalculations() doesn't override them
            setFieldValue('policy_start_date', policyPricing.policy_start_date);
            setFieldValue('policy_expiry_date', policyPricing.policy_expiry_date);
            
            const locumExtensionCheckbox = document.getElementById('locumExtension');
            if (locumExtensionCheckbox) {
                locumExtensionCheckbox.checked = policyPricing.locum_extension || false;
            }
            
            console.log('[Edit] Step 3 - Dates populated immediately');
            
            // Liability limit will be set when user navigates to Step 3
            // via the overridden populatePricingSummary function
        } else {
            console.warn('[Edit] Step 3 - No policyPricing data found!');
        }

        // Step 4: Risk Management
        const riskManagement = getRelation(data.user, 'riskManagement', 'risk_management');
        
        // Save Step 4 data to in-memory store
        if (riskManagement) {
            window.editModeData.step4 = {
                medical_records: riskManagement.medical_records ? 'yes' : 'no',
                informed_consent: riskManagement.informed_consent ? 'yes' : 'no',
                adverse_incidents: riskManagement.adverse_incidents ? 'yes' : 'no',
                sterilisation_facilities: riskManagement.sterilisation_facilities ? 'yes' : 'no'
            };
            console.log('[Edit] Step 4 - Saved to editModeData.step4:', window.editModeData.step4);
        }
        
        setFieldValue('medical_records', riskManagement?.medical_records ? 'yes' : 'no');
        setFieldValue('informed_consent', riskManagement?.informed_consent ? 'yes' : 'no');
        setFieldValue('adverse_incidents', riskManagement?.adverse_incidents ? 'yes' : 'no');
        setFieldValue('sterilisation_facilities', riskManagement?.sterilisation_facilities ? 'yes' : 'no');

        // Step 5: Insurance History
        const insuranceHistory = getRelation(data.user, 'insuranceHistory', 'insurance_history');
        
        // Save Step 5 data to in-memory store
        if (insuranceHistory) {
            window.editModeData.step5 = {
                current_insurance: insuranceHistory.current_insurance ? 'yes' : 'no',
                insurer_name: insuranceHistory.insurer_name,
                period_of_insurance: insuranceHistory.period_of_insurance,
                policy_limit_myr: insuranceHistory.policy_limit_myr,
                excess_myr: insuranceHistory.excess_myr,
                retroactive_date: insuranceHistory.retroactive_date,
                previous_claims: insuranceHistory.previous_claims ? 'yes' : 'no',
                claims_details: insuranceHistory.claims_details
            };
            console.log('[Edit] Step 5 - Saved to editModeData.step5:', window.editModeData.step5);
        }
        
        setFieldValue('current_insurance', insuranceHistory?.current_insurance ? 'yes' : 'no');
        setFieldValue('insurer_name', insuranceHistory?.insurer_name);
        setFieldValue('period_of_insurance', insuranceHistory?.period_of_insurance);
        setFieldValue('policy_limit_myr', insuranceHistory?.policy_limit_myr);
        setFieldValue('excess_myr', insuranceHistory?.excess_myr);
        setFieldValue('retroactive_date', insuranceHistory?.retroactive_date);
        setFieldValue('previous_claims', insuranceHistory?.previous_claims ? 'yes' : 'no');
        setFieldValue('claims_details', insuranceHistory?.claims_details);

        // Step 6: Claims Experience
        const claimsExperience = getRelation(data.user, 'claimsExperience', 'claims_experience');
        
        // Save Step 6 data to in-memory store
        if (claimsExperience) {
            window.editModeData.step6 = {
                claims_made: claimsExperience.claims_made ? 'yes' : 'no',
                aware_of_errors: claimsExperience.aware_of_errors ? 'yes' : 'no',
                disciplinary_action: claimsExperience.disciplinary_action ? 'yes' : 'no',
                claim_date_of_claim: claimsExperience.claim_date_of_claim,
                claim_notified_date: claimsExperience.claim_notified_date,
                claim_claimant_name: claimsExperience.claim_claimant_name,
                claim_allegations: claimsExperience.claim_allegations,
                claim_amount_claimed: claimsExperience.claim_amount_claimed,
                claim_status: claimsExperience.claim_status,
                claim_amounts_paid: claimsExperience.claim_amounts_paid
            };
            console.log('[Edit] Step 6 - Saved to editModeData.step6:', window.editModeData.step6);
        }
        
        setFieldValue('claims_made', claimsExperience?.claims_made ? 'yes' : 'no');
        setFieldValue('aware_of_errors', claimsExperience?.aware_of_errors ? 'yes' : 'no');
        setFieldValue('disciplinary_action', claimsExperience?.disciplinary_action ? 'yes' : 'no');
        setFieldValue('claim_date_of_claim', claimsExperience?.claim_date_of_claim);
        setFieldValue('claim_notified_date', claimsExperience?.claim_notified_date);
        setFieldValue('claim_claimant_name', claimsExperience?.claim_claimant_name);
        setFieldValue('claim_allegations', claimsExperience?.claim_allegations);
        setFieldValue('claim_amount_claimed', claimsExperience?.claim_amount_claimed);
        setFieldValue('claim_status', claimsExperience?.claim_status);
        setFieldValue('claim_amounts_paid', claimsExperience?.claim_amounts_paid);
        
        // Trigger conditional display logic for Step 4, 5, and 6 after population
        setTimeout(() => {
            if (typeof window.restoreConditionalSections === 'function') {
                window.restoreConditionalSections();
                console.log('[Edit] Conditional sections restored for Steps 4-6');
            }
        }, 100);

        // Restore pricing display
        if (policyPricing) {
            restorePricingDisplay(policyPricing);
        }

        console.log('[Edit] All fields directly populated from server data');
        
        // Log the liability limit value we're trying to save (reuse existing policyPricing variable)
        const policyPricingData = getRelation(data, 'policyPricing', 'policy_pricing');
        console.log('[Edit] Policy Pricing Data:', policyPricingData);
        console.log('[Edit] Liability Limit from server:', policyPricingData?.liability_limit);
        
        // Mark initial population as complete AFTER all cascading Step 2 events finish
        // Step 2 has cascading timeouts: 100ms + 100ms + 100ms + 100ms = 400ms + buffer
        setTimeout(() => {
            isInitialPopulation = false;
            console.log('[Edit] Initial population complete - future Step 2 changes will trigger recalculation');
        }, 800); // Wait for all Step 2 cascading to complete
    }

    /**
     * Restore pricing display without localStorage
     */
    function restorePricingDisplay(policyPricing) {
        console.log('[Edit] Restoring pricing display:', policyPricing);
        
        const formatCurrency = (value) => {
            const num = parseFloat(value) || 0;
            return num.toLocaleString('en-MY', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        };

        const displayMappings = [
            { id: 'displayLiabilityLimit', value: policyPricing.liability_limit },
            { id: 'displayBasePremium', value: policyPricing.base_premium },
            { id: 'displayLoadingPercentage', value: policyPricing.loading_percentage },
            { id: 'displayLoadingAmount', value: policyPricing.loading_amount },
            { id: 'displayGrossPremium', value: policyPricing.gross_premium },
            { id: 'displayLocumAddon', value: policyPricing.locum_addon },
            { id: 'displayDiscountPercentage', value: policyPricing.discount_percentage },
            { id: 'displayDiscountAmount', value: policyPricing.discount_amount },
            { id: 'displaySST', value: policyPricing.sst },
            { id: 'displayStampDuty', value: policyPricing.stamp_duty },
            { id: 'displayTotalPayable', value: policyPricing.total_payable }
        ];

        displayMappings.forEach(({ id, value }) => {
            const el = document.getElementById(id);
            if (el && value !== undefined && value !== null) {
                el.textContent = formatCurrency(value);
            }
        });
        
        // Also populate hidden input fields
        const hiddenFieldMappings = [
            { id: 'displayBasePremiumInput', value: policyPricing.base_premium },
            { id: 'displayLoadingPercentageInput', value: policyPricing.loading_percentage },
            { id: 'displayLoadingAmountInput', value: policyPricing.loading_amount },
            { id: 'displayGrossPremiumInput', value: policyPricing.gross_premium },
            { id: 'displayLocumAddonInput', value: policyPricing.locum_addon },
            { id: 'displayDiscountPercentageInput', value: policyPricing.discount_percentage },
            { id: 'displayDiscountAmountInput', value: policyPricing.discount_amount },
            { id: 'displaySSTInput', value: policyPricing.sst },
            { id: 'displayStampDutyInput', value: policyPricing.stamp_duty },
            { id: 'displayTotalPayableInput', value: policyPricing.total_payable }
        ];
        
        hiddenFieldMappings.forEach(({ id, value }) => {
            const el = document.getElementById(id);
            if (el && value !== undefined && value !== null) {
                el.value = parseFloat(value).toFixed(2);
            }
        });

        // Show conditional rows
        const loadingRow = document.getElementById('loadingRow');
        if (loadingRow && (parseFloat(policyPricing.loading_amount) > 0 || parseFloat(policyPricing.loading_percentage) > 0)) {
            loadingRow.style.display = 'flex';
        }

        const discountRow = document.getElementById('discountRow');
        if (discountRow && (parseFloat(policyPricing.discount_amount) > 0 || parseFloat(policyPricing.discount_percentage) > 0)) {
            discountRow.style.display = 'flex';
        }

        const locumAddonRow = document.getElementById('locumAddonRow');
        if (locumAddonRow && parseFloat(policyPricing.locum_addon) > 0) {
            locumAddonRow.style.display = 'flex';
        }

        // Show pricing breakdown
        const pricingBreakdown = document.getElementById('pricingBreakdown');
        if (pricingBreakdown) pricingBreakdown.style.display = 'block';

        const amountHr = document.getElementById('amountHr');
        if (amountHr) amountHr.style.display = 'block';

        console.log('[Edit] Pricing display restored from database');
    }
    /**
     * Override submitFormData function to use update endpoint instead of create
     */
    window.submitFormData = function(formData) {
        // Show loading indicator
        const submitBtn = document.getElementById('step6NextBtn');
        const originalText = submitBtn.textContent;
        const originalDisabled = submitBtn.disabled;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...';

        // Prepare data for submission
        const submissionData = {
            application_data: formData,
            _method: 'PUT'
        };

        // Get CSRF token
        const getCsrfToken = function() {
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) {
                return metaToken.getAttribute('content');
            }
            return '';
        };

        // Send AJAX request to UPDATE endpoint
        $.ajax({
            url: `/for-your-action/${policyApplicationId}/update`,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(submissionData),
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            timeout: 30000,
            success: function(response) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Application Updated Successfully!',
                        html: '<p>Your application has been updated.</p>',
                        confirmButtonText: 'Back to Application',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = response.redirect_url || `/for-your-action/${policyApplicationId}`;
                        }
                    });
                } else {
                    alert('Application Updated Successfully!');
                    window.location.href = response.redirect_url || `/for-your-action/${policyApplicationId}`;
                }

                updateProgressBar(6);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                let errorMessage = 'An error occurred while updating your application.';
                
                if (jqXHR.status === 422) {
                    const errors = jqXHR.responseJSON.errors;
                    errorMessage = 'Validation Error:\n' + Object.values(errors).flat().join('\n');
                } else if (jqXHR.status === 401) {
                    errorMessage = 'Your session has expired. Please login again.';
                } else if (jqXHR.status === 403) {
                    errorMessage = 'You do not have permission to update this application.';
                } else if (textStatus === 'timeout') {
                    errorMessage = 'Request timeout. Please check your internet connection and try again.';
                } else if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMessage = jqXHR.responseJSON.message;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: errorMessage,
                        confirmButtonText: 'Try Again'
                    });
                } else {
                    alert('Update Failed:\n' + errorMessage);
                }
            },
            complete: function() {
                submitBtn.disabled = originalDisabled;
                submitBtn.textContent = originalText;
            }
        });
    };

    // Hide steps 7 & 8, make step 6 the final step
    $(document).ready(function() {
        // Wait for DOM and all scripts to be fully ready, then populate
        setTimeout(() => {
            console.log('[Edit] Starting field population...');
            directlyPopulateFields();
            console.log('[Edit] Field population completed');
        }, 500);
        
        // Override updateProgressBar function
        window.updateProgressBar = function(step) {
            const totalSteps = 6;
            const progressBar = document.getElementById('progressBar');
            if (progressBar) {
                const progressPercentage = (step / totalSteps) * 100;
                progressBar.style.width = progressPercentage + '%';
                progressBar.setAttribute('aria-valuenow', progressPercentage);
                progressBar.textContent = `Step ${step} of ${totalSteps}`;
                if (step === totalSteps) {
                    progressBar.classList.add('bg-success');
                }
            }
        };
        
        // Override populatePricingSummary to ensure liability limit is properly set in edit mode
        const originalPopulatePricingSummary = window.populatePricingSummary;
        window.populatePricingSummary = function() {
            // Call original function
            if (originalPopulatePricingSummary) {
                originalPopulatePricingSummary();
            }
            
            // In edit mode, check if Step 2 has been modified
            if (window.isEditMode) {
                if (window.step2Modified) {
                    // Step 2 was changed - recalculate pricing fresh from Step 2 data
                    console.log('[Edit] Step 2 modified - will recalculate pricing based on new filters');
                    
                    // Set liability limit and trigger recalculation
                    if (window.editModeData.step3 && window.editModeData.step3.liability_limit) {
                        setTimeout(() => {
                            const liabilitySelect = document.getElementById('liabilityLimit');
                            const savedValue = String(window.editModeData.step3.liability_limit);
                            
                            if (liabilitySelect) {
                                liabilitySelect.value = savedValue;
                                
                                // Trigger recalculation with new Step 2 data
                                setTimeout(() => {
                                    const policyStartDate = document.getElementById('policyStartDate');
                                    const policyExpiryDate = document.getElementById('policyExpiryDate');
                                    
                                    if (policyStartDate && policyStartDate.value && 
                                        policyExpiryDate && policyExpiryDate.value &&
                                        liabilitySelect && liabilitySelect.value) {
                                        console.log('[Edit] Triggering fresh pricing calculation');
                                        liabilitySelect.dispatchEvent(new Event('change', { bubbles: true }));
                                    }
                                }, 100);
                            }
                        }, 300);
                    }
                } else {
                    // Step 2 not modified - restore saved pricing from database
                    console.log('[Edit] Step 2 not modified - restoring saved pricing from database');
                    
                    const policyPricing = window.policyData.policyPricing || window.policyData.policy_pricing;
                    if (policyPricing) {
                        setTimeout(() => {
                            restorePricingDisplay(policyPricing);
                        }, 100);
                    }
                    
                    // Set liability limit without triggering recalculation
                    if (window.editModeData.step3 && window.editModeData.step3.liability_limit) {
                        setTimeout(() => {
                            const liabilitySelect = document.getElementById('liabilityLimit');
                            const savedValue = String(window.editModeData.step3.liability_limit);
                            
                            if (liabilitySelect) {
                                liabilitySelect.value = savedValue;
                                console.log('[Edit] Liability limit set - using saved pricing');
                            }
                        }, 300);
                    }
                }
            }
        };
        
        // Add event listeners to Step 2 fields to update Step 3 display dynamically
        // This ensures that when admin changes Step 2 filters, Step 3 updates accordingly
        const step2Fields = [
            'professional_indemnity_type',
            'employment_status', 
            'specialty_area',
            'cover_type',
            'locum_practice_location',
            'service_type',
            'practice_area'
        ];
        
        step2Fields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.addEventListener('change', function() {
                    console.log(`[Edit] Step 2 field changed: ${fieldName} = ${this.value}`);
                    
                    // Only mark as modified if we're not doing initial population
                    if (!isInitialPopulation) {
                        // Mark Step 2 as modified
                        window.step2Modified = true;
                        console.log('[Edit] Step 2 marked as modified - will use fresh pricing calculation');
                    } else {
                        console.log('[Edit] Initial population - not marking as modified');
                    }
                    
                    // Update in-memory data store
                    if (!window.editModeData.step2) {
                        window.editModeData.step2 = {};
                    }
                    window.editModeData.step2[fieldName] = this.value;
                    
                    // Update Step 3 summary display and recalculate pricing if modified
                    setTimeout(() => {
                        if (typeof window.populatePricingSummary === 'function') {
                            window.populatePricingSummary();
                        }
                        
                        // Only recalculate if Step 2 was manually modified and we're on Step 3
                        if (window.step2Modified) {
                            const step3Card = document.getElementById('step3Card');
                            if (step3Card && step3Card.style.display !== 'none') {
                                if (typeof window.calculatePremium === 'function') {
                                    window.calculatePremium();
                                }
                            }
                        }
                    }, 200);
                });
            }
        });
        
        // Override getAllSavedData to collect current form values from all 6 steps
        window.getAllSavedData = function() {
            console.log('[Edit] Collecting all form data from 6 steps...');
            
            const allData = {};
            
            // Map of form IDs for each step
            const formIds = {
                1: 'policyApplicationForm',
                2: 'healthcareServicesForm',
                3: 'pricingDetailsForm',
                4: 'declarationForm',
                5: 'insuranceHistoryForm',
                6: 'claimsExperienceForm'
            };
            
            // Collect data from each form
            for (let step = 1; step <= 6; step++) {
                const formId = formIds[step];
                const form = document.getElementById(formId);
                
                if (form) {
                    console.log(`[Edit] Collecting data from ${formId} (Step ${step})`);
                    const formData = getFormData(form);
                    Object.assign(allData, formData);
                } else {
                    console.warn(`[Edit] Form ${formId} not found for Step ${step}`);
                }
            }
            
            // Also collect data from editModeData (in case forms weren't visited)
            for (let step = 1; step <= 6; step++) {
                const stepKey = `step${step}`;
                if (window.editModeData[stepKey]) {
                    Object.keys(window.editModeData[stepKey]).forEach(key => {
                        if (!allData[key]) {
                            allData[key] = window.editModeData[stepKey][key];
                        }
                    });
                }
            }
            
            // Ensure all pricing fields are included (these may be in hidden inputs)
            const pricingFields = [
                'displayBasePremium', 'displayLoadingPercentage', 'displayLoadingAmount',
                'displayGrossPremium', 'displayLocumAddon', 'displayDiscountPercentage',
                'displayDiscountAmount', 'displaySST', 'displayStampDuty', 'displayTotalPayable',
                'voucher_code'
            ];
            
            pricingFields.forEach(fieldName => {
                const input = document.getElementById(fieldName) || 
                             document.querySelector(`input[name="${fieldName}"]`) ||
                             document.querySelector(`[name="${fieldName}"]`);
                if (input && !allData[fieldName]) {
                    allData[fieldName] = input.value || input.textContent || '';
                }
            });
            
            // Get values from display elements if inputs don't exist
            const displayElements = {
                'displayBasePremium': 'displayBasePremium',
                'displayLoadingPercentage': 'displayLoadingPercentage',
                'displayLoadingAmount': 'displayLoadingAmount',
                'displayGrossPremium': 'displayGrossPremium',
                'displayLocumAddon': 'displayLocumAddon',
                'displayDiscountPercentage': 'displayDiscountPercentage',
                'displayDiscountAmount': 'displayDiscountAmount',
                'displaySST': 'displaySST',
                'displayStampDuty': 'displayStampDuty',
                'displayTotalPayable': 'displayTotalPayable'
            };
            
            Object.keys(displayElements).forEach(key => {
                if (!allData[key] || allData[key] === '') {
                    const el = document.getElementById(displayElements[key]);
                    if (el) {
                        const value = el.textContent.replace(/[^0-9.]/g, '');
                        if (value) allData[key] = value;
                    }
                }
            });
            
            console.log('[Edit] All collected form data:', allData);
            return allData;
        };
        
        // Hide step 7 and 8 cards
        $('#step7Card').hide();
        $('#step8Card').hide();
        
        // Update progress bar to show 6 steps
        updateProgressBar(1);
        
        // Override step 6 Next button to submit
        $('#step6NextBtn').off('click').on('click', function(e) {
            e.preventDefault();
            
            const claimsExperienceForm = document.getElementById('claimsExperienceForm');
            if (!claimsExperienceForm.checkValidity()) {
                claimsExperienceForm.reportValidity();
                return;
            }
            
            // Save current step 6 data
            const formData = getFormData(claimsExperienceForm);
            saveFormData(6, formData);
            
            // Collect all form data and submit
            const allFormData = getAllSavedData();
            submitEditFormData(allFormData);
        });
    });
    
    /**
     * SUBMIT EDIT FORM DATA TO SERVER
     * Updates the existing policy application
     */
    function submitEditFormData(formData) {
        const policyApplicationId = document.getElementById('policyApplicationId').value;
        
        // Show loading indicator
        const submitBtn = document.getElementById('step6NextBtn');
        const originalText = submitBtn.textContent;
        const originalDisabled = submitBtn.disabled;

        submitBtn.disabled = true;
        submitBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...';

        // Prepare data for submission
        const submissionData = {
            application_data: formData
        };

        console.log('[Edit Submit] Updating policy application ID:', policyApplicationId);
        console.log('[Edit Submit] Submitting form data:', submissionData);

        // Send AJAX request to server
        $.ajax({
            url: `/for-your-action/${policyApplicationId}/update`,
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(submissionData),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            timeout: 30000, // 30 second timeout
            success: function(response) {
                console.log('[Edit Submit] ✅ SUCCESS:', response);

                // Show success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Application Updated Successfully!',
                        html: '<p>The policy application has been updated.</p>',
                        confirmButtonText: 'View Details',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = response.redirect_url || `/for-your-action/${policyApplicationId}`;
                        }
                    });
                } else {
                    // Fallback if SweetAlert not available
                    alert('Application Updated Successfully!');
                    window.location.href = `/for-your-action/${policyApplicationId}`;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('[Edit Submit] ❌ ERROR:', jqXHR, textStatus, errorThrown);

                let errorMessage = 'An error occurred while updating the application.';

                if (jqXHR.status === 422) {
                    // Validation errors
                    const errors = jqXHR.responseJSON.errors;
                    errorMessage = 'Validation Error:\n' + Object.values(errors).flat().join('\n');
                } else if (jqXHR.status === 401) {
                    errorMessage = 'Your session has expired. Please login again.';
                } else if (jqXHR.status === 403) {
                    errorMessage = 'You do not have permission to update this application.';
                } else if (textStatus === 'timeout') {
                    errorMessage = 'Request timeout. Please check your internet connection and try again.';
                } else if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMessage = jqXHR.responseJSON.message;
                }

                // Show error message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: errorMessage,
                        confirmButtonText: 'Try Again'
                    });
                } else {
                    alert('Update Failed: ' + errorMessage);
                }

                console.log('[Edit Submit] Error response:', jqXHR.responseJSON);
            },
            complete: function() {
                // Restore button state
                submitBtn.disabled = originalDisabled;
                submitBtn.innerHTML = originalText;
            }
        });
    }
</script>
