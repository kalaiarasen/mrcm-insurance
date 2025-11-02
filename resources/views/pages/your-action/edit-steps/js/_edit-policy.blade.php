<script>
    /**
     * Edit Policy Application JavaScript
     * This file extends the new-policy JavaScript to support editing existing applications
     * It pre-loads existing data and changes the submission endpoint
     */

    // Get the policy application ID
    const policyApplicationId = document.getElementById('policyApplicationId')?.value || null;

    /**
     * Pre-populate localStorage with existing policy data
     * This function runs before the new-policy script to inject existing data
     */
    function prePopulateExisting() {
        if (!window.policyData) {
            return;
        }

        const data = window.policyData;

        // Helper function to find address by type
        const findAddress = (type) => {
            return data.user?.addresses?.find(addr => addr.type === type);
        };

        // Helper function to find qualification by sequence
        const findQualification = (sequence) => {
            return data.user?.qualifications?.find(qual => qual.sequence === sequence);
        };

        // Get addresses
        const mailingAddress = findAddress('mailing');
        const primaryAddress = findAddress('primary_clinic');
        const secondaryAddress = findAddress('secondary_clinic');

        // Get qualifications
        const qual1 = findQualification(1);
        const qual2 = findQualification(2);
        const qual3 = findQualification(3);

        // Helper to get relationship data (handles both camelCase and snake_case)
        const getRelation = (obj, camelCase, snakeCase) => {
            return obj?.[camelCase] || obj?.[snakeCase];
        };

        // Step 1: Applicant Details
        const step1Data = {
            title: data.user?.applicant_profile?.title || '',
            full_name: data.user?.name || '',
            nationality_status: data.user?.applicant_profile?.nationality_status || '',
            nric_number: data.user?.applicant_profile?.nric_number || '',
            passport_number: data.user?.applicant_profile?.passport_number || '',
            gender: data.user?.applicant_profile?.gender || '',
            contact_no: data.user?.applicant_contact?.contact_no || '',
            email_address: data.user?.email || '',
            mailing_address: mailingAddress?.address || '',
            mailing_postcode: mailingAddress?.postcode || '',
            mailing_city: mailingAddress?.city || '',
            mailing_state: mailingAddress?.state || '',
            mailing_country: mailingAddress?.country || '',
            primary_clinic_type: primaryAddress?.clinic_type || '',
            primary_clinic_name: primaryAddress?.clinic_name || '',
            primary_address: primaryAddress?.address || '',
            primary_postcode: primaryAddress?.postcode || '',
            primary_city: primaryAddress?.city || '',
            primary_state: primaryAddress?.state || '',
            primary_country: primaryAddress?.country || '',
            secondary_clinic_type: secondaryAddress?.clinic_type || '',
            secondary_clinic_name: secondaryAddress?.clinic_name || '',
            secondary_address: secondaryAddress?.address || '',
            secondary_postcode: secondaryAddress?.postcode || '',
            secondary_city: secondaryAddress?.city || '',
            secondary_state: secondaryAddress?.state || '',
            secondary_country: secondaryAddress?.country || '',
            institution_1: qual1?.institution || '',
            qualification_1: qual1?.degree_or_qualification || '',
            year_obtained_1: qual1?.year_obtained || '',
            institution_2: qual2?.institution || '',
            qualification_2: qual2?.degree_or_qualification || '',
            year_obtained_2: qual2?.year_obtained || '',
            institution_3: qual3?.institution || '',
            qualification_3: qual3?.degree_or_qualification || '',
            year_obtained_3: qual3?.year_obtained || '',
            registration_council: data.user?.applicant_profile?.registration_council || '',
            other_council: data.user?.applicant_profile?.other_council || '',
            registration_number: data.user?.applicant_profile?.registration_number || '',
        };

        // Step 2: Healthcare Services
        const healthcareService = getRelation(data.user, 'healthcareService', 'healthcare_service');
        const step2Data = {
            professional_indemnity_type: healthcareService?.professional_indemnity_type || '',
            employment_status: healthcareService?.employment_status || '',
            specialty_area: healthcareService?.specialty_area || '',
            cover_type: healthcareService?.cover_type || '',
            locum_practice_location: healthcareService?.locum_practice_location || '',
            service_type: healthcareService?.service_type || '',
            practice_area: healthcareService?.practice_area || '',
        };

        // Step 3: Pricing Details
        const policyPricing = getRelation(data.user, 'policyPricing', 'policy_pricing');
        const liabilityLimitValue = policyPricing?.liability_limit ? String(Math.round(parseFloat(policyPricing.liability_limit))) : '';
        
        const step3Data = {
            policy_start_date: policyPricing?.policy_start_date || '',
            policy_expiry_date: policyPricing?.policy_expiry_date || '',
            liability_limit: liabilityLimitValue,
            locum_extension: policyPricing?.locum_extension || false,
            displayBasePremium: policyPricing?.base_premium || '0',
            displayGrossPremium: policyPricing?.gross_premium || '0',
            displayLocumAddon: policyPricing?.locum_addon || '0',
            displaySST: policyPricing?.sst || '0',
            displayStampDuty: policyPricing?.stamp_duty || '10',
            displayTotalPayable: policyPricing?.total_payable || '0',
        };

        // Step 4: Risk Management
        const riskManagement = getRelation(data.user, 'riskManagement', 'risk_management');
        const step4Data = {
            medical_records: riskManagement?.medical_records ? 'yes' : 'no',
            informed_consent: riskManagement?.informed_consent ? 'yes' : 'no',
            adverse_incidents: riskManagement?.adverse_incidents ? 'yes' : 'no',
            sterilisation_facilities: riskManagement?.sterilisation_facilities ? 'yes' : 'no',
        };

        // Step 5: Insurance History
        const insuranceHistory = getRelation(data.user, 'insuranceHistory', 'insurance_history');
        const step5Data = {
            current_insurance: insuranceHistory?.current_insurance ? 'yes' : 'no',
            insurer_name: insuranceHistory?.insurer_name || '',
            period_of_insurance: insuranceHistory?.period_of_insurance || '',
            policy_limit_myr: insuranceHistory?.policy_limit_myr || '',
            excess_myr: insuranceHistory?.excess_myr || '',
            retroactive_date: insuranceHistory?.retroactive_date || '',
            previous_claims: insuranceHistory?.previous_claims ? 'yes' : 'no',
            claims_details: insuranceHistory?.claims_details || '',
        };

        // Step 6: Claims Experience
        const claimsExperience = getRelation(data.user, 'claimsExperience', 'claims_experience');
        const step6Data = {
            claims_made: claimsExperience?.claims_made ? 'yes' : 'no',
            aware_of_errors: claimsExperience?.aware_of_errors ? 'yes' : 'no',
            disciplinary_action: claimsExperience?.disciplinary_action ? 'yes' : 'no',
            claim_date_of_claim: claimsExperience?.claim_date_of_claim || '',
            claim_notified_date: claimsExperience?.claim_notified_date || '',
            claim_claimant_name: claimsExperience?.claim_claimant_name || '',
            claim_allegations: claimsExperience?.claim_allegations || '',
            claim_amount_claimed: claimsExperience?.claim_amount_claimed || '',
            claim_status: claimsExperience?.claim_status || '',
            claim_amounts_paid: claimsExperience?.claim_amounts_paid || '',
        };

        // Save all to localStorage using the same keys as new-policy
        const userId = getUserId();
        localStorage.setItem(`policy_${userId}_step1`, JSON.stringify(step1Data));
        localStorage.setItem(`policy_${userId}_step2`, JSON.stringify(step2Data));
        localStorage.setItem(`policy_${userId}_step3`, JSON.stringify(step3Data));
        localStorage.setItem(`policy_${userId}_step4`, JSON.stringify(step4Data));
        localStorage.setItem(`policy_${userId}_step5`, JSON.stringify(step5Data));
        localStorage.setItem(`policy_${userId}_step6`, JSON.stringify(step6Data));
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
            type: 'POST', // Use POST with _method: PUT
            contentType: 'application/json',
            data: JSON.stringify(submissionData),
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            timeout: 30000,
            success: function(response) {
                // Show success message
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
                            // Clear localStorage and redirect
                            clearAllSavedData();
                            window.location.href = response.redirect_url || `/for-your-action/${policyApplicationId}`;
                        }
                    });
                } else {
                    alert('Application Updated Successfully!');
                    clearAllSavedData();
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
        // CRITICAL: Override updateProgressBar function AFTER new-policy script loads
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
        
        // Override getAllSavedData to only get 6 steps
        window.getAllSavedData = function() {
            const allData = {};
            for (let i = 1; i <= 6; i++) {
                const stepData = loadFormData(i);
                Object.assign(allData, stepData);
            }
            return allData;
        };
        
        prePopulateExisting();
        
        // Hide step 7 and 8 cards
        $('#step7Card').hide();
        $('#step8Card').hide();
        
        // Re-update progress bar to ensure it shows 6 steps
        updateProgressBar(1);
        
        // Override step 6 Next button behavior to submit instead of going to next step
        $('#step6NextBtn').off('click').on('click', function(e) {
            e.preventDefault();
            
            // Validate step 6 form
            const claimsExperienceForm = document.getElementById('claimsExperienceForm');
            if (!claimsExperienceForm.checkValidity()) {
                claimsExperienceForm.reportValidity();
                return;
            }
            
            // Save step 6 data
            const formData = getFormData(claimsExperienceForm);
            saveFormData(6, formData);
            
            // Change button text and style
            $(this).text('Submit Application').removeClass('btn-primary').addClass('btn-success');
            
            // Collect all form data from steps 1-6
            const allFormData = getAllSavedData();
            
            // Submit data
            submitFormData(allFormData);
        });
    });
</script>
