<script>
    /**
     * Edit Policy Application JavaScript  
     * This file directly populates form fields from server data
     * WITHOUT using localStorage to avoid conflicts
     */

    // Get the policy application ID
    const policyApplicationId = document.getElementById('policyApplicationId')?.value || null;

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

        // Step 2: Healthcare Services
        const healthcareService = getRelation(data.user, 'healthcareService', 'healthcare_service');
        setFieldValue('professional_indemnity_type', healthcareService?.professional_indemnity_type);
        setFieldValue('employment_status', healthcareService?.employment_status);
        setFieldValue('specialty_area', healthcareService?.specialty_area);
        setFieldValue('cover_type', healthcareService?.cover_type);
        setFieldValue('locum_practice_location', healthcareService?.locum_practice_location);
        setFieldValue('service_type', healthcareService?.service_type);
        setFieldValue('practice_area', healthcareService?.practice_area);

        // Step 3: Pricing Details
        const policyPricing = getRelation(data, 'policyPricing', 'policy_pricing');
        setFieldValue('policy_start_date', policyPricing?.policy_start_date);
        setFieldValue('policy_expiry_date', policyPricing?.policy_expiry_date);
        
        const liabilityLimitValue = policyPricing?.liability_limit ? String(Math.round(parseFloat(policyPricing.liability_limit))) : '';
        setFieldValue('liability_limit', liabilityLimitValue);
        
        const locumExtensionCheckbox = document.getElementById('locumExtension');
        if (locumExtensionCheckbox) {
            locumExtensionCheckbox.checked = policyPricing?.locum_extension || false;
        }

        // Step 4: Risk Management
        const riskManagement = getRelation(data.user, 'riskManagement', 'risk_management');
        setFieldValue('medical_records', riskManagement?.medical_records ? 'yes' : 'no');
        setFieldValue('informed_consent', riskManagement?.informed_consent ? 'yes' : 'no');
        setFieldValue('adverse_incidents', riskManagement?.adverse_incidents ? 'yes' : 'no');
        setFieldValue('sterilisation_facilities', riskManagement?.sterilisation_facilities ? 'yes' : 'no');

        // Step 5: Insurance History
        const insuranceHistory = getRelation(data.user, 'insuranceHistory', 'insurance_history');
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

        // Restore pricing display
        if (policyPricing) {
            restorePricingDisplay(policyPricing);
        }

        console.log('[Edit] All fields directly populated from server data');
    }

    /**
     * Restore pricing display without localStorage
     */
    function restorePricingDisplay(policyPricing) {
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

        console.log('[Edit] Pricing display restored');
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
        
        // Override getAllSavedData to collect current form values
        window.getAllSavedData = function() {
            const allData = {};
            for (let i = 1; i <= 6; i++) {
                const stepForm = document.getElementById(`step${i}Form`) || 
                               document.getElementById(`applicantDetailsForm`) ||
                               document.getElementById(`healthcareServicesForm`) ||
                               document.getElementById(`pricingDetailsForm`) ||
                               document.getElementById(`riskManagementForm`) ||
                               document.getElementById(`insuranceHistoryForm`) ||
                               document.getElementById('claimsExperienceForm');
                               
                if (stepForm) {
                    const formData = new FormData(stepForm);
                    formData.forEach((value, key) => {
                        allData[key] = value;
                    });
                }
            }
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
            
            $(this).text('Submit Application').removeClass('btn-primary').addClass('btn-success');
            
            const allFormData = getAllSavedData();
            submitFormData(allFormData);
        });
    });
</script>
