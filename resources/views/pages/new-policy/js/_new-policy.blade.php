<script>
    function getUserId() {
        let userId = localStorage.getItem('policyUserId');
        if (!userId) {
            userId = 'user_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('policyUserId', userId);
        }
        return userId;
    }

    function saveFormData(step, formData) {
        const userId = getUserId();
        const key = `policy_${userId}_step${step}`;
        localStorage.setItem(key, JSON.stringify(formData));
        console.log(`Data saved for step ${step}:`, formData);
    }

    function loadFormData(step) {
        const userId = getUserId();
        const key = `policy_${userId}_step${step}`;
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : {};
    }

    function clearAllSavedData() {
        const userId = getUserId();
        for (let i = 1; i <= totalSteps; i++) {
            const key = `policy_${userId}_step${i}`;
            localStorage.removeItem(key);
        }
        console.log('All saved data cleared for user:', userId);
    }

    function getAllSavedData() {
        const allData = {};
        for (let i = 1; i <= totalSteps; i++) {
            const stepData = loadFormData(i);
            Object.assign(allData, stepData);
        }
        return allData;
    }

    function debugSavedData() {
        const userId = getUserId();
        console.log('Current User ID:', userId);
        for (let i = 1; i <= totalSteps; i++) {
            const data = loadFormData(i);
            console.log(`Step ${i} data:`, data);
        }
        console.log('All combined data:', getAllSavedData());
    }

    function toggleDebugPanel() {
        const panel = document.getElementById('debugPanel');
        if (panel) {
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }
    }

    function populateForm(step, data) {
        Object.keys(data).forEach(name => {
            const element = document.querySelector(`[name="${name}"]`);
            if (element) {
                if (element.type === 'checkbox' || element.type === 'radio') {
                    element.checked = data[name];
                } else if (element.type === 'password') {
                    // Keep timeout only for password fields to handle browser autofill issues
                    setTimeout(() => {
                        element.value = data[name] || '';
                    }, 100);
                } else {
                    element.value = data[name];
                }
            }
        });
        
        // Enhanced restoration for step 2 (Healthcare Services) - No timeout needed
        if (step === 2) {
            // Use the enhanced restoration function from _health-care.blade.php
            if (typeof window.restoreHealthcareServicesState === 'function') {
                window.restoreHealthcareServicesState();
            } else {
                // Fallback to the old method if the new function isn't available
                restoreHealthcareServicesState(data);
            }
        }
        
        if (step === 3) {
            populatePricingSummary();
        }
    }

    function populatePricingSummary() {
        const step1Data = loadFormData(1);
        const step2Data = loadFormData(2);

        // Populate the top display fields (Cover Type, Medical Status, Class)
        const coverTypeText = getCoverTypeText(step2Data);
        const coverEl = document.getElementById('displayCoverType');
        if (coverEl) coverEl.textContent = coverTypeText;

        const medicalStatusText = getMedicalStatusText(step2Data);
        const medEl = document.getElementById('displayMedicalStatus');
        if (medEl) medEl.textContent = medicalStatusText;

        const classText = getClassText(step2Data);
        const classEl = document.getElementById('displayClass');
        if (classEl) classEl.textContent = classText;

        // Set liability limit options based on step 2 selections
        setLiabilityOptionsForStep2(step2Data);

        setupPricingCalculations();
    }

    function setLiabilityOptionsForStep2(step2Data) {
        const select = document.getElementById('liabilityLimit');
        if (!select) return;

        // Condition: when step2 indicates Government Medical Officers with Locum only
        // (employment_status === 'government' and cover_type === 'locum_cover' OR locum_cover_only)
        const isLocumOnlyGov = step2Data && (step2Data.employment_status === 'government') && 
            (step2Data.cover_type === 'locum_cover' || step2Data.cover_type === 'locum_cover_only');

        // Build options
        let html = '';

        // Always show a placeholder option that is disabled & selected by default
        html += '<option value="" disabled selected>Choose Liability Limit</option>';

        if (isLocumOnlyGov) {
            html += '<option value="1000000">RM 1,000,000</option>';
        } else {
            html += '<option value="1000000">RM 1,000,000</option>';
            html += '<option value="2000000">RM 2,000,000</option>';
            html += '<option value="5000000">RM 5,000,000</option>';
            html += '<option value="10000000">RM 10,000,000</option>';
        }

        select.innerHTML = html;

        // Always enable select so the user can actively choose an option (even if only one real option exists)
        select.disabled = false;

        // Trigger change so pricing recalculates
        const evt = new Event('change', { bubbles: true });
        select.dispatchEvent(evt);
        // Also explicitly call calculatePremium in case listeners are not yet attached
        try {
            calculatePremium();
        } catch (e) {
            console.warn('calculatePremium not available yet:', e);
        }
    }

    function getCoverTypeText(step2Data) {
        // Construct Cover Type display text
        if (step2Data.professional_indemnity_type) {
            let text = 'Professional Indemnity - ';
            if (step2Data.professional_indemnity_type === 'medical_practice') {
                text += 'Medical Practitioner';
            } else if (step2Data.professional_indemnity_type === 'dental_practice') {
                text += 'Dental Practitioner';
            } else {
                text += formatDisplayText(step2Data.professional_indemnity_type);
            }
            return text;
        }
        return '-';
    }
    
    function getMedicalStatusText(step2Data) {
        // Construct Medical Status from employment status and specialty
        let parts = [];
        
        if (step2Data.employment_status) {
            if (step2Data.employment_status === 'government') {
                parts.push('Government');
            } else if (step2Data.employment_status === 'private') {
                parts.push('Private');
            } else {
                parts.push(formatDisplayText(step2Data.employment_status));
            }
        }
        
        if (step2Data.specialty_area) {
            if (step2Data.specialty_area === 'medical_officer') {
                parts.push('Medical Officers');
            } else if (step2Data.specialty_area === 'general_practitioner') {
                parts.push('General Practitioner');
            } else {
                parts.push(formatDisplayText(step2Data.specialty_area));
            }
        }
        
        if (step2Data.cover_type && step2Data.cover_type === 'locum_cover') {
            parts.push('Locum only');
        }
        
        return parts.length > 0 ? parts.join(' ') : '-';
    }
    
    function getClassText(step2Data) {
        // Map service type or cover type to class text
        if (step2Data.service_type) {
            return formatDisplayText(step2Data.service_type);
        } else if (step2Data.cover_type) {
            return formatDisplayText(step2Data.cover_type);
        }
        return '-';
    }

    function formatDisplayText(value) {
        if (value === '-' || !value) return '-';
        
        return value.replace(/_/g, ' ')
                    .replace(/\b\w/g, l => l.toUpperCase());
    }

    function setupPricingCalculations() {
        const policyStartDate = document.getElementById('policyStartDate');
        const policyExpiryDate = document.getElementById('policyExpiryDate');
        const liabilityLimit = document.getElementById('liabilityLimit');
        
        if (!policyStartDate.value) {
            const today = new Date();
            policyStartDate.value = today.toISOString().split('T')[0];
            updateExpiryDate();
        }
        
        policyStartDate.addEventListener('change', updateExpiryDate);
        liabilityLimit.addEventListener('change', calculatePremium);
    }

    function updateExpiryDate() {
        const startDate = document.getElementById('policyStartDate').value;
        if (startDate) {
            const expiryDate = new Date(startDate);
            expiryDate.setFullYear(expiryDate.getFullYear() + 1);
            document.getElementById('policyExpiryDate').value = expiryDate.toISOString().split('T')[0];
        }
    }

    function calculatePremium() {
        const liabilityLimit = document.getElementById('liabilityLimit').value;
        
        if (!liabilityLimit) {
            document.getElementById('pricingBreakdown').style.display = 'none';
            return;
        }
        
        const basePremium = getBasePremium(liabilityLimit);
        // Gross Premium = Premium × 1.2027 (approximately 20.27% markup)
        const grossPremium = basePremium * 1.2027;
        const sstPercentage = 0.08;
        const sst = grossPremium * sstPercentage;
        const stampDuty = 10.00;
        const totalPayable = grossPremium + sst + stampDuty;
        
        // Format amounts with thousands separator
        const formatCurrency = (value) => {
            return parseFloat(value).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        };
        
        document.getElementById('displayLiabilityLimit').textContent = formatCurrency(liabilityLimit);
        document.getElementById('displayBasePremium').textContent = formatCurrency(basePremium);
        document.getElementById('displayGrossPremium').textContent = formatCurrency(grossPremium);
        document.getElementById('displayLocumAddon').textContent = formatCurrency(0);
        document.getElementById('displaySST').textContent = formatCurrency(sst);
        document.getElementById('displayStampDuty').textContent = formatCurrency(stampDuty);
        document.getElementById('displayTotalPayable').textContent = formatCurrency(totalPayable);
        
        document.getElementById('locumAddonRow').style.display = 'none';
        
        document.getElementById('pricingBreakdown').style.display = 'block';
        const hr = document.getElementById('amountHr');
        if (hr) hr.style.display = 'block';
    }

    function getBasePremium(liabilityLimit) {
        const step2Data = loadFormData(2);
        
        const premiumRates = {
            '1000000': {
                'medical_practice': { 'government': 700, 'private': 1200 },
                'dental_practice': { 'government': 600, 'private': 900 }
            },
            '2000000': {
                'medical_practice': { 'government': 1200, 'private': 1800 },
                'dental_practice': { 'government': 900, 'private': 1350 }
            },
            '5000000': {
                'medical_practice': { 'government': 2000, 'private': 3000 },
                'dental_practice': { 'government': 1500, 'private': 2250 }
            },
            '10000000': {
                'medical_practice': { 'government': 3500, 'private': 5250 },
                'dental_practice': { 'government': 2625, 'private': 3937.5 }
            }
        };
        
        const professionalType = step2Data.professional_indemnity_type || 'medical_practice';
        const employmentStatus = step2Data.employment_status || 'government';
        
        return premiumRates[liabilityLimit]?.[professionalType]?.[employmentStatus] || 1000;
    }

    function shouldShowLocumExtension(step2Data) {
        const professionalType = step2Data.professional_indemnity_type;
        const employmentStatus = step2Data.employment_status;
        const coverType = step2Data.cover_type;
        
        return employmentStatus === 'private' || 
            coverType === 'locum_cover' || 
            coverType === 'general_cover';
    }

    function getFormData(formElement) {
        const formData = new FormData(formElement);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        return data;
    }

    let currentStep = 1;
    const totalSteps = 3;

    function updateProgressBar(step) {
        const progressBar = document.getElementById('progressBar');
        const progressPercentage = (step / totalSteps) * 100;
        
        if (progressBar) {
            progressBar.style.width = progressPercentage + '%';
            progressBar.setAttribute('aria-valuenow', progressPercentage);
            progressBar.textContent = `Step ${step} of ${totalSteps}`;
            
            if (step === totalSteps) {
                progressBar.classList.remove('bg-primary');
                progressBar.classList.add('bg-success');
            } else {
                progressBar.classList.remove('bg-success');
                progressBar.classList.add('bg-primary');
            }
        }
    }

    function showStep(step) {
        console.log('showStep called with step:', step, 'currentStep before:', currentStep);
        
        for (let i = 1; i <= totalSteps; i++) {
            const stepCard = document.getElementById(`step${i}Card`);
            if (stepCard) {
                stepCard.style.display = 'none';
            }
        }
        
        const currentCard = document.getElementById(`step${step}Card`);
        if (currentCard) {
            currentCard.style.display = 'block';
            console.log('Showing step card:', `step${step}Card`);
        } else {
            console.error('Step card not found:', `step${step}Card`);
        }
        
        updateProgressBar(step);
        
        // Load and populate saved data for this step
        const savedData = loadFormData(step);
        console.log(`Loading saved data for step ${step}:`, savedData);
        
        if (Object.keys(savedData).length > 0) {
            console.log(`Populating form for step ${step} with saved data`);
            populateForm(step, savedData);
        } else {
            console.log(`No saved data found for step ${step}`);
        }
        
        currentStep = step;
        console.log('currentStep updated to:', currentStep);
    }

    function nextStep() {
        console.log('nextStep called. Current step:', currentStep, 'Total steps:', totalSteps);
        if (currentStep < totalSteps) {
            const newStep = currentStep + 1;
            console.log('Moving to step:', newStep);
            showStep(newStep);
        } else {
            console.log('Already at last step');
        }
    }

    function prevStep() {
        console.log('prevStep called. Current step:', currentStep);
        if (currentStep > 1) {
            const newStep = currentStep - 1;
            console.log('Moving to step:', newStep);
            showStep(newStep);
        } else {
            console.log('Already at first step');
        }
    }

    $(document).ready(function() {
        showStep(1);
        
        $('#nationalityStatus').on('change', function() {
            const value = $(this).val();
            const nricField = $('#nricNumber');
            const passportField = $('#passportNumber');
            const nricRequired = $('#nricRequired');
            const passportRequired = $('#passportRequired');
            
            if (value === 'malaysian') {
                nricField.prop('required', true);
                passportField.prop('required', false);
                nricRequired.show();
                passportRequired.hide();
            } else if (value === 'non_malaysian') {
                nricField.prop('required', false);
                passportField.prop('required', true);
                nricRequired.hide();
                passportRequired.show();
            } else {
                nricField.prop('required', false);
                passportField.prop('required', false);
                nricRequired.hide();
                passportRequired.hide();
            }
        });

        $('#registrationCouncil').on('change', function() {
            const value = $(this).val();
            const otherField = $('#otherCouncilField');
            const otherInput = $('#otherCouncil');
            const registrationLabel = $('#registrationNumberLabel');
            const registrationInput = $('#registrationNumber');
            
            otherInput.val('');
            registrationInput.val('');
            
            if (value === 'mmc') {
                otherField.hide();
                otherInput.prop('required', false);
                registrationLabel.html('MMC Number <span class="text-danger">*</span>');
                registrationInput.attr('placeholder', 'Enter MMC Number');
                registrationInput.prop('required', true);
            } else if (value === 'mdc') {
                otherField.hide();
                otherInput.prop('required', false);
                registrationLabel.html('MDC Number <span class="text-danger">*</span>');
                registrationInput.attr('placeholder', 'Enter MDC Number');
                registrationInput.prop('required', true);
            } else if (value === 'others') {
                otherField.show();
                otherInput.prop('required', true);
                registrationLabel.html('Registration Number <span class="text-danger">*</span>');
                registrationInput.attr('placeholder', 'Enter Registration Number');
                registrationInput.prop('required', true);
            } else {
                otherField.hide();
                otherInput.prop('required', false);
                registrationLabel.html('Registration Number <span class="text-danger">*</span>');
                registrationInput.attr('placeholder', 'Registration Number');
                registrationInput.prop('required', false);
            }
        });

        $('#policyApplicationForm').on('submit', function(e) {
            e.preventDefault();
            
            const password = $('#password').val();
            const confirmPassword = $('#confirmPassword').val();
            
            if (password !== confirmPassword) {
                alert('Passwords do not match. Please check and try again.');
                $('#confirmPassword').focus();
                return;
            }
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                $(this).addClass('was-validated');
                return;
            }
            
            const formData = getFormData(this);
            saveFormData(1, formData);
            
            console.log('Step 1 completed, moving to step 2. Current step:', currentStep);
            nextStep();
        });
        
        $('#healthcareServicesForm').on('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                $(this).addClass('was-validated');
                return;
            }
            
            const formData = getFormData(this);
            saveFormData(2, formData);
            
            console.log('Step 2 completed, moving to step 3. Current step:', currentStep);
            nextStep();
        });
        $('#pricingDetailsForm').on('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                $(this).addClass('was-validated');
                return;
            }
            
            const formData = getFormData(this);
            saveFormData(3, formData);
            
            const allData = getAllSavedData();
            
            let dataSummary = 'Application submitted successfully!\n\nData Summary:\n';
            dataSummary += `User ID: ${getUserId()}\n`;
            dataSummary += `Total fields saved: ${Object.keys(allData).length}\n\n`;
            dataSummary += 'Check browser console for complete data details.';
            
            alert(dataSummary);
            console.log('Complete application data:', allData);
            
            updateProgressBar(totalSteps);
            
        });

        $('#step1NextBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 1 Next button clicked');
            $('#policyApplicationForm').trigger('submit');
        });

        $('#step2PrevBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 2 Previous button clicked');
            // Save current form data before moving back
            const healthcareForm = document.getElementById('healthcareServicesForm');
            if (healthcareForm) {
                const formData = getFormData(healthcareForm);
                saveFormData(2, formData);
                console.log('Step 2 data saved before moving back:', formData);
            }
            prevStep();
        });

        $('#step2NextBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 2 Next button clicked');
            $('#healthcareServicesForm').trigger('submit');
        });

        $('#step3PrevBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 3 Previous button clicked');
            // Save current form data before moving back
            const pricingForm = document.getElementById('pricingDetailsForm');
            if (pricingForm) {
                const formData = getFormData(pricingForm);
                saveFormData(3, formData);
                console.log('Step 3 data saved before moving back:', formData);
            }
            prevStep();
        });

        $('#step3NextBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 3 Submit button clicked');
            $('#pricingDetailsForm').trigger('submit');
        });

        $('#confirmPassword').on('input', function() {
            const password = $('#password').val();
            const confirmPassword = $(this).val();
            
            if (password && confirmPassword) {
                if (password === confirmPassword) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }
            } else {
                $(this).removeClass('is-valid is-invalid');
            }
        });

        $('#policyApplicationForm input, #policyApplicationForm select').on('change', function() {
            const formData = getFormData(document.getElementById('policyApplicationForm'));
            saveFormData(1, formData);
        });

        $('#healthcareServicesForm input, #healthcareServicesForm select').on('change input', function() {
            console.log('Healthcare services form field changed:', this.name, '=', this.value);
            const formData = getFormData(document.getElementById('healthcareServicesForm'));
            saveFormData(2, formData);
        });

        $('#pricingDetailsForm input, #pricingDetailsForm select, #pricingDetailsForm input[type="checkbox"]').on('change input', function() {
            console.log('Pricing details form field changed:', this.name, '=', this.value);
            const formData = getFormData(document.getElementById('pricingDetailsForm'));
            saveFormData(3, formData);
        });
    });
</script>