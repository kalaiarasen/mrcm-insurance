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
        console.log(`[populateForm] Step ${step}, Data:`, data);
        
        Object.keys(data).forEach(name => {
            console.log(`[populateForm] Processing field: ${name} = ${data[name]}`);
            
            const elements = document.querySelectorAll(`[name="${name}"]`);
            console.log(`[populateForm] Found ${elements.length} elements with name="${name}"`);
            
            if (elements.length === 0) {
                console.warn(`[populateForm] No elements found for name="${name}"`);
                return;
            }
            
            const element = elements[0];
            
            if (element.type === 'checkbox') {
                console.log(`[populateForm] Processing checkbox: ${name}`);
                elements.forEach(el => {
                    el.checked = el.value === data[name];
                    console.log(`[populateForm] Checkbox ${name}=${el.value}, should be checked: ${el.value === data[name]}, actual: ${el.checked}`);
                });
            } else if (element.type === 'radio') {
                console.log(`[populateForm] Processing radio: ${name}, looking for value: ${data[name]}`);
                const radioToCheck = document.querySelector(`[name="${name}"][value="${data[name]}"]`);
                if (radioToCheck) {
                    radioToCheck.checked = true;
                    console.log(`[populateForm] Found and checked radio: ${name}[value="${data[name]}"]`);
                } else {
                    console.warn(`[populateForm] Radio not found: ${name}[value="${data[name]}"]`);
                }
            } else if (element.type === 'password') {
                // Keep timeout only for password fields to handle browser autofill issues
                setTimeout(() => {
                    element.value = data[name] || '';
                }, 100);
            } else {
                element.value = data[name];
                console.log(`[populateForm] Set ${element.type} ${name} = ${data[name]}`);
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
        const step3Data = loadFormData(3);

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

        // Restore liability limit selection if it was previously saved
        // Do this after setting up event listeners
        if (step3Data && step3Data.liability_limit) {
            const liabilitySelect = document.getElementById('liabilityLimit');
            if (liabilitySelect) {
                liabilitySelect.value = step3Data.liability_limit;
                // Trigger change to recalculate premium with saved value
                setTimeout(() => {
                    const evt = new Event('change', { bubbles: true });
                    liabilitySelect.dispatchEvent(evt);
                }, 100);
            }
        }
    }

    function setLiabilityOptionsForStep2(step2Data) {
        const select = document.getElementById('liabilityLimit');
        if (!select) return;

        // Services that should only show RM 2,000,000 (GP services)
        const twoMillionOnlyServices = [
            'core_services_with_procedures',
            'general_practitioner_private_hospital_outpatient',
            'general_practitioner_private_hospital_emergency',
            'general_practitioner_with_obstetrics',
            'cosmetic_aesthetic_non_invasive',
            'cosmetic_aesthetic_non_surgical_invasive'
        ];

        // Low risk specialists that should show RM 1,000,000 & RM 2,000,000
        const lowRiskSpecialists = [
            'occupational_health_physicians',
            'general_physicians',
            'dermatology_non_cosmetic',
            'infections_diseases',
            'pathology',
            'psychiatry',
            'endocrinology',
            'rehab_medicine',
            'paediatrics_non_neonatal',
            'geriatrics',
            'haemotology',
            'immunology',
            'nephrology',
            'nuclear_medicine',
            'neurology',
            'radiology_non_interventional'
        ];

        // Medium risk specialists that should only show RM 2,000,000
        const mediumRiskSpecialists = [
            'ophthalmology_office_procedures',
            'office_ent_clinic_based',
            'ophthalmology_surgeries_non_ga',
            'ent_surgeries_non_ga',
            'radiology_interventional',
            'gastroenterology',
            'office_clinical_orthopaedics',
            'office_clinical_gynaecology',
            'cosmetic_aesthetic_non_surgical_invasive',
            'cosmetic_aesthetic_surgical_invasive'
        ];

        // Check conditions
        const isLocumOnlyGov = step2Data && (step2Data.employment_status === 'government') && 
            (step2Data.cover_type === 'locum_cover' || step2Data.cover_type === 'locum_cover_only');
        
        const isTwoMillionOnlyService = step2Data && twoMillionOnlyServices.includes(step2Data.service_type);
        
        const isMediumRiskSpecialist = step2Data && mediumRiskSpecialists.includes(step2Data.service_type);
        
        const isLowRiskSpecialist = step2Data && lowRiskSpecialists.includes(step2Data.service_type);

        const isLecturerTrainee = step2Data && step2Data.specialty_area === 'lecturer_trainee';

        // Build options
        let html = '';

        // Always show a placeholder option that is disabled & selected by default
        html += '<option value="" disabled selected>Choose Liability Limit</option>';

        if (isLocumOnlyGov) {
            // Government Locum only: RM 1,000,000 only
            html += '<option value="1000000">RM 1,000,000</option>';
        } else if (isLecturerTrainee) {
            // Lecturer/Trainee: RM 1,000,000 & RM 2,000,000
            html += '<option value="1000000">RM 1,000,000</option>';
            html += '<option value="2000000">RM 2,000,000</option>';
        } else if (isTwoMillionOnlyService || isMediumRiskSpecialist) {
            // Specific GP services + Medium risk specialists: RM 2,000,000 only
            html += '<option value="2000000">RM 2,000,000</option>';
        } else if (isLowRiskSpecialist) {
            // Low risk specialists: RM 1,000,000 & RM 2,000,000
            html += '<option value="1000000">RM 1,000,000</option>';
            html += '<option value="2000000">RM 2,000,000</option>';
        } else {
            // Default: All options
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
    const totalSteps = 8;

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

    // Helper function to restore conditional sections on page load
    function restoreConditionalSections() {
        console.log('[restoreConditionalSections] Starting restoration');
        
        // Step 5
        const currentInsuranceVal = $('input[name="current_insurance"]:checked').val();
        console.log('[restoreConditionalSections] current_insurance value:', currentInsuranceVal);
        if (currentInsuranceVal === 'yes') {
            const section = document.getElementById('currentInsuranceDetailsSection');
            if (section) {
                section.style.display = 'block';
                console.log('[restoreConditionalSections] Showing currentInsuranceDetailsSection');
            } else {
                console.warn('[restoreConditionalSections] currentInsuranceDetailsSection not found');
            }
        }
        
        const previousClaimsVal = $('input[name="previous_claims"]:checked').val();
        console.log('[restoreConditionalSections] previous_claims value:', previousClaimsVal);
        if (previousClaimsVal === 'yes') {
            const section = document.getElementById('previousClaimsDetailsSection');
            if (section) {
                section.style.display = 'block';
                console.log('[restoreConditionalSections] Showing previousClaimsDetailsSection');
            } else {
                console.warn('[restoreConditionalSections] previousClaimsDetailsSection not found');
            }
        }

        // Step 6: Show details section if ANY question is "yes"
        const claimsMadeVal = $('input[name="claims_made"]:checked').val();
        const awareOfErrorsVal = $('input[name="aware_of_errors"]:checked').val();
        const disciplinaryActionVal = $('input[name="disciplinary_action"]:checked').val();
        
        console.log('[restoreConditionalSections] Step 6 values:', {
            claims_made: claimsMadeVal,
            aware_of_errors: awareOfErrorsVal,
            disciplinary_action: disciplinaryActionVal
        });

        if (claimsMadeVal === 'yes' || awareOfErrorsVal === 'yes' || disciplinaryActionVal === 'yes') {
            const section = document.getElementById('claimsDetailsSection');
            if (section) {
                section.style.display = 'block';
                console.log('[restoreConditionalSections] Showing claimsDetailsSection');
            } else {
                console.warn('[restoreConditionalSections] claimsDetailsSection not found');
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
            
            // Restore conditional sections visibility after form is populated
            setTimeout(() => {
                if (step === 5 || step === 6) {
                    restoreConditionalSections();
                }
                if (step === 8) {
                    initSignatureCanvas();
                }
            }, 50);
        } else {
            console.log(`No saved data found for step ${step}`);
            // Initialize signature canvas even if no saved data
            if (step === 8) {
                setTimeout(() => {
                    initSignatureCanvas();
                }, 50);
            }
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

    // Step 8: Signature Canvas Variables and Functions
    let signatureCanvas = null;
    let signatureContext = null;
    let isDrawing = false;

    function initSignatureCanvas() {
        signatureCanvas = document.getElementById('signatureCanvas');
        if (!signatureCanvas) {
            console.warn('Signature canvas not found');
            return;
        }

        signatureContext = signatureCanvas.getContext('2d');
        
        // Set canvas resolution to match display size
        const dpr = window.devicePixelRatio || 1;
        const rect = signatureCanvas.getBoundingClientRect();
        
        signatureCanvas.width = rect.width * dpr;
        signatureCanvas.height = rect.height * dpr;
        
        if (signatureContext) {
            signatureContext.scale(dpr, dpr);
            // Set white background
            signatureContext.fillStyle = 'white';
            signatureContext.fillRect(0, 0, rect.width, rect.height);
        }

        console.log('Signature canvas initialized:', signatureCanvas.width, 'x', signatureCanvas.height);

        // Mouse events
        signatureCanvas.addEventListener('mousedown', startDrawing, false);
        signatureCanvas.addEventListener('mousemove', draw, false);
        signatureCanvas.addEventListener('mouseup', stopDrawing, false);
        signatureCanvas.addEventListener('mouseout', stopDrawing, false);

        // Touch events
        signatureCanvas.addEventListener('touchstart', startDrawing, false);
        signatureCanvas.addEventListener('touchmove', draw, false);
        signatureCanvas.addEventListener('touchend', stopDrawing, false);
    }

    function startDrawing(e) {
        e.preventDefault();
        isDrawing = true;
        const rect = signatureCanvas.getBoundingClientRect();
        const x = (e.clientX || (e.touches && e.touches[0].clientX)) - rect.left;
        const y = (e.clientY || (e.touches && e.touches[0].clientY)) - rect.top;
        signatureContext.beginPath();
        signatureContext.moveTo(x, y);
        console.log('Drawing started at:', x, y);
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        const rect = signatureCanvas.getBoundingClientRect();
        const x = (e.clientX || (e.touches && e.touches[0].clientX)) - rect.left;
        const y = (e.clientY || (e.touches && e.touches[0].clientY)) - rect.top;
        signatureContext.lineWidth = 2;
        signatureContext.lineCap = 'round';
        signatureContext.lineJoin = 'round';
        signatureContext.strokeStyle = '#333';
        signatureContext.lineTo(x, y);
        signatureContext.stroke();
    }

    function stopDrawing(e) {
        if (isDrawing) {
            console.log('Drawing stopped');
        }
        isDrawing = false;
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
            
            console.log('Step 3 completed, moving to step 4. Current step:', currentStep);
            nextStep();
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
            console.log('Step 3 Next button clicked');
            $('#pricingDetailsForm').trigger('submit');
        });

        $('#declarationForm').on('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                $(this).addClass('was-validated');
                return;
            }
            
            const formData = getFormData(this);
            saveFormData(4, formData);
            
            console.log('Step 4 completed, moving to step 5. Current step:', currentStep);
            nextStep();
        });

        $('#step4PrevBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 4 Previous button clicked');
            // Save current form data before moving back
            const declarationForm = document.getElementById('declarationForm');
            if (declarationForm) {
                const formData = getFormData(declarationForm);
                saveFormData(4, formData);
                console.log('Step 4 data saved before moving back:', formData);
            }
            prevStep();
        });

        $('#step4NextBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 4 Next button clicked');
            $('#declarationForm').trigger('submit');
        });

        $('#insuranceHistoryForm').on('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                $(this).addClass('was-validated');
                return;
            }
            
            const formData = getFormData(this);
            saveFormData(5, formData);
            
            console.log('Step 5 completed, moving to step 6. Current step:', currentStep);
            nextStep();
        });

        $('#step5PrevBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 5 Previous button clicked');
            // Save current form data before moving back
            const insuranceHistoryForm = document.getElementById('insuranceHistoryForm');
            if (insuranceHistoryForm) {
                const formData = getFormData(insuranceHistoryForm);
                saveFormData(5, formData);
                console.log('Step 5 data saved before moving back:', formData);
            }
            prevStep();
        });

        $('#step5NextBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 5 Next button clicked');
            $('#insuranceHistoryForm').trigger('submit');
        });

        $('#claimsExperienceForm').on('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                $(this).addClass('was-validated');
                return;
            }
            
            const formData = getFormData(this);
            saveFormData(6, formData);
            
            console.log('Step 6 completed, moving to step 7. Current step:', currentStep);
            nextStep();
        });

        $('#step6PrevBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 6 Previous button clicked');
            // Save current form data before moving back
            const claimsExperienceForm = document.getElementById('claimsExperienceForm');
            if (claimsExperienceForm) {
                const formData = getFormData(claimsExperienceForm);
                saveFormData(6, formData);
                console.log('Step 6 data saved before moving back:', formData);
            }
            prevStep();
        });

        $('#step6NextBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 6 Submit button clicked');
            $('#claimsExperienceForm').trigger('submit');
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

        $('#declarationForm input, #declarationForm select').on('change', function() {
            console.log('Declaration form field changed:', this.name, '=', this.value);
            const formData = getFormData(document.getElementById('declarationForm'));
            saveFormData(4, formData);
        });

        $('#insuranceHistoryForm input, #insuranceHistoryForm select, #insuranceHistoryForm textarea').on('change input', function() {
            console.log('Insurance history form field changed:', this.name, '=', this.value);
            const formData = getFormData(document.getElementById('insuranceHistoryForm'));
            saveFormData(5, formData);
        });

        $('#claimsExperienceForm input, #claimsExperienceForm select').on('change', function() {
            console.log('Claims experience form field changed:', this.name, '=', this.value);
            const formData = getFormData(document.getElementById('claimsExperienceForm'));
            saveFormData(6, formData);
        });

        // Step 7: Data Protection Form
        $('#dataProtectionForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Data Protection form submitted');
            
            const agreeDeclaration = document.getElementById('agreeDeclaration');
            if (!agreeDeclaration || !agreeDeclaration.checked) {
                alert('Please read and agree to the Data Protection Notice declaration');
                return;
            }

            const dataProtectionForm = document.getElementById('dataProtectionForm');
            if (dataProtectionForm) {
                const formData = getFormData(dataProtectionForm);
                saveFormData(7, formData);
                console.log('Data saved for step 7');
            }

            console.log('Step 7 completed, moving to step 8. Current step:', currentStep);
            nextStep();
        });

        $('#dataProtectionForm input').on('change', function() {
            console.log('Data protection form field changed:', this.name, '=', this.value);
            const formData = getFormData(document.getElementById('dataProtectionForm'));
            saveFormData(7, formData);
        });

        $('#step7PrevBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 7 Previous button clicked');
            // Save current form data before moving back
            const dataProtectionForm = document.getElementById('dataProtectionForm');
            if (dataProtectionForm) {
                const formData = getFormData(dataProtectionForm);
                saveFormData(7, formData);
                console.log('Step 7 data saved before moving back:', formData);
            }
            prevStep();
        });

        $('#step7NextBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 7 Next button clicked');
            $('#dataProtectionForm').trigger('submit');
        });

        // Step 8: Declaration & Signature Event Handlers
        $('#clearSignatureBtn').on('click', function(e) {
            e.preventDefault();
            if (signatureCanvas && signatureContext) {
                const rect = signatureCanvas.getBoundingClientRect();
                signatureContext.fillStyle = 'white';
                signatureContext.fillRect(0, 0, rect.width, rect.height);
                console.log('Signature cleared');
            }
        });

        $('#declarationSignatureForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Declaration & Signature form submitted');
            
            // Step 1: Validate all required declarations are agreed
            const agreeDeclarationFinal = document.getElementById('agreeDeclarationFinal');
            if (!agreeDeclarationFinal || !agreeDeclarationFinal.checked) {
                alert('Please read and agree to the declaration');
                return;
            }

            // Step 2: Validate signature is drawn
            let hasSignature = false;
            if (signatureCanvas) {
                const imageData = signatureContext.getImageData(0, 0, signatureCanvas.width, signatureCanvas.height);
                const data = imageData.data;
                // Check if any pixel is not white (255, 255, 255, 255)
                for (let i = 0; i < data.length; i += 4) {
                    if (data[i] !== 255 || data[i+1] !== 255 || data[i+2] !== 255) {
                        hasSignature = true;
                        break;
                    }
                }
            }

            if (!hasSignature) {
                alert('Please provide a signature before submitting');
                return;
            }

            // Step 3: Validate all required fields across all steps
            const allStepsValid = validateAllSteps();
            if (!allStepsValid) {
                alert('Some required fields are missing. Please go back and complete all steps.');
                return;
            }

            // Step 4: Save the final step data
            const declarationSignatureForm = document.getElementById('declarationSignatureForm');
            if (declarationSignatureForm) {
                const formData = getFormData(declarationSignatureForm);
                if (signatureCanvas) {
                    formData.signature = signatureCanvas.toDataURL('image/png');
                }
                saveFormData(8, formData);
                console.log('Data saved for step 8');
            }

            // Step 5: Collect all form data from all steps
            const allFormData = getAllSavedData();
            
            // Step 6: Submit data to server
            submitFormData(allFormData);
        });

        $('#declarationSignatureForm input').on('change', function() {
            console.log('Declaration signature form field changed:', this.name, '=', this.value);
            const formData = getFormData(document.getElementById('declarationSignatureForm'));
            saveFormData(8, formData);
        });

        $('#step8PrevBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 8 Previous button clicked');
            const declarationSignatureForm = document.getElementById('declarationSignatureForm');
            if (declarationSignatureForm) {
                const formData = getFormData(declarationSignatureForm);
                saveFormData(8, formData);
                console.log('Step 8 data saved before moving back:', formData);
            }
            prevStep();
        });

        $('#step8NextBtn').on('click', function(e) {
            e.preventDefault();
            console.log('Step 8 Submit button clicked');
            $('#declarationSignatureForm').trigger('submit');
        });

        // Step 5: Conditional display for insurance history sections
        $('input[name="current_insurance"]').on('change', function() {
            const currentInsuranceDetailsSection = document.getElementById('currentInsuranceDetailsSection');
            if (this.value === 'yes') {
                currentInsuranceDetailsSection.style.display = 'block';
            } else {
                currentInsuranceDetailsSection.style.display = 'none';
            }
        });

        $('input[name="previous_claims"]').on('change', function() {
            const previousClaimsDetailsSection = document.getElementById('previousClaimsDetailsSection');
            if (this.value === 'yes') {
                previousClaimsDetailsSection.style.display = 'block';
            } else {
                previousClaimsDetailsSection.style.display = 'none';
            }
        });

        // Step 6: Conditional display for claims experience sections
        // Show details section if ANY question has "yes" selected
        $('input[name="claims_made"], input[name="aware_of_errors"], input[name="disciplinary_action"]').on('change', function() {
            const claimsMadeVal = $('input[name="claims_made"]:checked').val();
            const awareOfErrorsVal = $('input[name="aware_of_errors"]:checked').val();
            const disciplinaryActionVal = $('input[name="disciplinary_action"]:checked').val();

            const claimsDetailsSection = document.getElementById('claimsDetailsSection');
            
            // Show details if ANY question is answered "yes"
            if (claimsMadeVal === 'yes' || awareOfErrorsVal === 'yes' || disciplinaryActionVal === 'yes') {
                claimsDetailsSection.style.display = 'block';
            } else {
                claimsDetailsSection.style.display = 'none';
            }

            saveFormData(6, getFormData(document.getElementById('claimsExperienceForm')));
        });

        // Call this function after form data is restored on page load
        setTimeout(restoreConditionalSections, 100);
    });

    /**
     * COMPREHENSIVE VALIDATION FUNCTION
     * Validates all required fields across all 8 steps
     */
    function validateAllSteps() {
        const step1Fields = ['title', 'full_name', 'nationality_status', 'gender', 'contact_no', 'email_address', 'password', 'confirm_password', 'mailing_address', 'mailing_postcode', 'mailing_city', 'mailing_state', 'mailing_country', 'primary_clinic_type', 'primary_clinic_name', 'primary_address', 'primary_postcode', 'primary_city', 'primary_state', 'primary_country', 'institution_1', 'qualification_1', 'year_obtained_1', 'registration_council', 'registration_number'];
        
        // service_type is optional - some paths don't need it
        const step2Fields = ['professional_indemnity_type', 'employment_status', 'specialty_area', 'cover_type'];
        
        const step3Fields = ['policy_start_date', 'liability_limit'];
        
        const step4Fields = ['medical_records', 'informed_consent', 'adverse_incidents', 'sterilisation_facilities'];
        
        const step5Fields = ['current_insurance', 'previous_claims'];
        
        const step6Fields = ['claims_made', 'aware_of_errors', 'disciplinary_action'];
        
        const step7Fields = ['agree_declaration'];
        
        const step8Fields = ['agree_declaration_final'];

        let isValid = true;
        let missingFields = [];

        // Check Step 1
        const step1Data = loadFormData(1);
        console.log('[Validation] Step 1 Data:', step1Data);
        step1Fields.forEach(field => {
            const value = step1Data[field];
            if (!value || value === '') {
                isValid = false;
                missingFields.push(`Step 1: ${field}`);
                console.warn(`[Validation] Missing Step 1 field: ${field}`);
            }
        });

        // Validate password and confirm password match
        if (step1Data['password'] && step1Data['confirm_password']) {
            if (step1Data['password'] !== step1Data['confirm_password']) {
                isValid = false;
                missingFields.push('Step 1: Passwords do not match');
                console.warn('[Validation] Passwords do not match');
            }
            if (step1Data['password'].length < 8) {
                isValid = false;
                missingFields.push('Step 1: Password must be at least 8 characters');
                console.warn('[Validation] Password too short');
            }
        }

        // Check Step 2 (service_type is optional)
        const step2Data = loadFormData(2);
        console.log('[Validation] Step 2 Data:', step2Data);
        step2Fields.forEach(field => {
            const value = step2Data[field];
            if (!value || value === '') {
                isValid = false;
                missingFields.push(`Step 2: ${field}`);
                console.warn(`[Validation] Missing Step 2 field: ${field}`);
            }
        });

        // Check Step 3
        const step3Data = loadFormData(3);
        console.log('[Validation] Step 3 Data:', step3Data);
        step3Fields.forEach(field => {
            const value = step3Data[field];
            if (!value || value === '') {
                isValid = false;
                missingFields.push(`Step 3: ${field}`);
                console.warn(`[Validation] Missing Step 3 field: ${field}`);
            }
        });

        // Check Step 4
        const step4Data = loadFormData(4);
        console.log('[Validation] Step 4 Data:', step4Data);
        step4Fields.forEach(field => {
            const value = step4Data[field];
            if (!value || value === '') {
                isValid = false;
                missingFields.push(`Step 4: ${field}`);
                console.warn(`[Validation] Missing Step 4 field: ${field}`);
            }
        });

        // Check Step 5
        const step5Data = loadFormData(5);
        console.log('[Validation] Step 5 Data:', step5Data);
        step5Fields.forEach(field => {
            const value = step5Data[field];
            if (!value || value === '') {
                isValid = false;
                missingFields.push(`Step 5: ${field}`);
                console.warn(`[Validation] Missing Step 5 field: ${field}`);
            }
        });

        // Check Step 6
        const step6Data = loadFormData(6);
        console.log('[Validation] Step 6 Data:', step6Data);
        step6Fields.forEach(field => {
            const value = step6Data[field];
            if (!value || value === '') {
                isValid = false;
                missingFields.push(`Step 6: ${field}`);
                console.warn(`[Validation] Missing Step 6 field: ${field}`);
            }
        });

        // Check Step 7
        const step7Data = loadFormData(7);
        console.log('[Validation] Step 7 Data:', step7Data);
        const agreeDeclaration = document.getElementById('agreeDeclaration');
        if (!agreeDeclaration || !agreeDeclaration.checked) {
            isValid = false;
            missingFields.push('Step 7: Declaration agreement');
            console.warn('[Validation] Step 7 agreement not checked');
        }

        // Check Step 8 signature
        console.log('[Validation] Checking Step 8 signature...');
        if (!signatureCanvas) {
            isValid = false;
            missingFields.push('Step 8: Signature canvas not initialized');
            console.warn('[Validation] Signature canvas not initialized');
        }

        if (isValid) {
            console.log('[Validation] ✅ ALL STEPS VALIDATED SUCCESSFULLY');
        } else {
            console.warn('[Validation] ❌ VALIDATION FAILED - Missing fields:', missingFields);
            console.log(missingFields.join('\n'));
        }

        return isValid;
    }

    /**
     * SUBMIT FORM DATA TO SERVER
     * Best practice implementation with error handling and user feedback
     */
    function submitFormData(formData) {
        // Show loading indicator
        const submitBtn = document.getElementById('step8NextBtn');
        const originalText = submitBtn.textContent;
        const originalDisabled = submitBtn.disabled;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...';

        // Prepare data for submission
        const submissionData = {
            application_data: formData
        };

        console.log('[Submit] Submitting form data:', submissionData);

        // Get CSRF token - works for both authenticated and guest users
        const getCsrfToken = function() {
            // Try to get from meta tag first (works for guests)
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) {
                return metaToken.getAttribute('content');
            }
            
            // Fallback to blade template (works for authenticated users)
            // In Blade: {{ csrf_token() }}
            const templateToken = document.querySelector('[data-csrf-token]');
            if (templateToken) {
                return templateToken.getAttribute('data-csrf-token');
            }
            
            return '';
        };

        // Send AJAX request to server
        $.ajax({
            url: '{{ route("policies.submit") }}',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(submissionData),
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            timeout: 30000, // 30 second timeout
            success: function(response) {
                console.log('[Submit] ✅ SUCCESS:', response);
                
                // Show success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Application Submitted Successfully!',
                        html: '<p>Thank you for submitting your application.</p>' +
                              '<p>Reference Number: <strong>' + (response.reference_number || 'N/A') + '</strong></p>' +
                              '<p>We will review your application and contact you shortly.</p>',
                        confirmButtonText: 'Go to Dashboard',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Clear localStorage and redirect
                            clearAllSavedData();
                            window.location.href = '{{ route("dashboard") }}';
                        }
                    });
                } else {
                    // Fallback if SweetAlert not available
                    alert('Application Submitted Successfully!\n\nReference Number: ' + (response.reference_number || 'N/A') + '\n\nWe will review your application and contact you shortly.');
                    clearAllSavedData();
                    window.location.href = '{{ route("dashboard") }}';
                }

                // Update progress bar
                updateProgressBar(totalSteps);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('[Submit] ❌ ERROR:', jqXHR, textStatus, errorThrown);
                
                let errorMessage = 'An error occurred while submitting your application.';
                
                if (jqXHR.status === 422) {
                    // Validation errors
                    const errors = jqXHR.responseJSON.errors;
                    errorMessage = 'Validation Error:\n' + Object.values(errors).flat().join('\n');
                } else if (jqXHR.status === 401) {
                    errorMessage = 'Your session has expired. Please login again.';
                } else if (jqXHR.status === 403) {
                    errorMessage = 'You do not have permission to submit this application.';
                } else if (textStatus === 'timeout') {
                    errorMessage = 'Request timeout. Please check your internet connection and try again.';
                } else if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMessage = jqXHR.responseJSON.message;
                }

                // Show error message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Failed',
                        text: errorMessage,
                        confirmButtonText: 'Try Again'
                    });
                } else {
                    // Fallback if SweetAlert not available
                    alert('Submission Failed:\n' + errorMessage);
                }

                console.log('[Submit] Error response:', jqXHR.responseJSON);
            },
            complete: function() {
                // Restore button state
                submitBtn.disabled = originalDisabled;
                submitBtn.textContent = originalText;
            }
        });
    }
</script>