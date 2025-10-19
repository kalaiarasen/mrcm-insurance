 @extends('layouts.main')

@section('title', 'New Policy')

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>New Policy</h3>
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
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">New Policy</li>
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
                            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 12.5%;" aria-valuenow="12.5" aria-valuemin="0" aria-valuemax="100">
                                Step 1 of 8
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Step 1: Applicant Details</small>
                            <small class="text-muted">Step 2: Healthcare Services</small>
                            <small class="text-muted">Step 3: Pricing Details</small>
                            <small class="text-muted">Step 4: Risk Management</small>
                            <small class="text-muted">Step 5: Insurance History</small>
                            <small class="text-muted">Step 6: Claims Experience</small>
                            <small class="text-muted">Step 7: Data Protection</small>
                            <small class="text-muted">Step 8: Declaration</small>
                        </div>
                        
                        <!-- Debug Panel (for testing) -->
                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleDebugPanel()">Toggle Debug Panel</button>
                        </div>
                        <div id="debugPanel" class="mt-3" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Debug Tools</h6>
                                    <button type="button" class="btn btn-sm btn-info me-2" onclick="debugSavedData()">View Saved Data</button>
                                    <button type="button" class="btn btn-sm btn-warning me-2" onclick="clearAllSavedData(); alert('All data cleared!');">Clear All Data</button>
                                    <button type="button" class="btn btn-sm btn-success" onclick="console.log('User ID:', getUserId())">Show User ID</button>
                                    <div class="mt-2">
                                        <small class="text-muted">Use browser console (F12) to see debug output</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid default-dashboard">
        <div class="row widget-grid">
            <!-- Card 1: Details of the Applicant -->
            <div class="col-md-12" id="step1Card">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>1. Details of the Applicant</h5>
                    </div>
                    <div class="card-body">
                        <form id="policyApplicationForm">
                            <!-- Personal Information Row -->
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="applicantTitle" class="form-label">Title <span class="text-danger">*</span></label>
                                        <select class="form-select" id="applicantTitle" name="title" required>
                                            <option value="">Select</option>
                                            <option value="dr">DR.</option>
                                            <option value="mr">MR.</option>
                                            <option value="ms">MS.</option>
                                            <option value="prof">PROF.</option>
                                            <option value="dato">DATO</option>
                                            <option value="datin">DATIN</option>
                                            <option value="datuk">DATUK</option>
                                            <option value="datuk_seri">DATUK SERI</option>
                                            <option value="tan_sri">TAN SRI</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="fullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="fullName" name="full_name" placeholder="Full Name" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="nationalityStatus" class="form-label">Nationality Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="nationalityStatus" name="nationality_status" required>
                                            <option value="">Select Nationality Status</option>
                                            <option value="malaysian">Malaysian</option>
                                            <option value="non_malaysian">Non-Malaysian</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="nricNumber" class="form-label">NRIC Number (XXXXXX-XX-XXXX) <span class="text-danger" id="nricRequired">*</span></label>
                                        <input type="text" class="form-control" id="nricNumber" name="nric_number" placeholder="NRIC Number (XXXXXX-XX-XXXX)">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="passportNumber" class="form-label">Passport Number <span class="text-danger" id="passportRequired" style="display:none;">*</span></label>
                                        <input type="text" class="form-control" id="passportNumber" name="passport_number" placeholder="Passport Number">
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Row -->
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="contactNo" class="form-label">Contact No <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="contactNo" name="contact_no" placeholder="Contact No" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="emailAddress" class="form-label">Email address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="emailAddress" name="email_address" placeholder="Email address" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="new-password" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm Password" autocomplete="new-password" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Mailing Address Section -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5>Mailing Address</h5>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="mailingAddress" class="form-label">Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="mailingAddress" name="mailing_address" placeholder="Address" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="mailingPostCode" class="form-label">PostCode <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="mailingPostCode" name="mailing_postcode" placeholder="PostCode" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="mailingCity" class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="mailingCity" name="mailing_city" placeholder="City" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="mailingState" class="form-label">State <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="mailingState" name="mailing_state" placeholder="State" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="mailingCountry" class="form-label">Country <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="mailingCountry" name="mailing_country" placeholder="Country" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Primary Practicing Address Section -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5>Primary Practicing Address:</h5>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="primary_clinic_type" id="primaryGovernment" value="government" required>
                                            <label class="form-check-label" for="primaryGovernment">
                                                Government
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="primary_clinic_type" id="primaryPrivate" value="private" required>
                                            <label class="form-check-label" for="primaryPrivate">
                                                Private
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="primaryClinicName" class="form-label">Name of clinic/hospital <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="primaryClinicName" name="primary_clinic_name" placeholder="Name of clinic/hospital" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label for="primaryAddress" class="form-label">Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="primaryAddress" name="primary_address" placeholder="Address" required>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mb-3">
                                        <label for="primaryPostCode" class="form-label">PostCode <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="primaryPostCode" name="primary_postcode" placeholder="PostCode" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="primaryCity" class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="primaryCity" name="primary_city" placeholder="City" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="primaryState" class="form-label">State <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="primaryState" name="primary_state" placeholder="State" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="primaryCountry" class="form-label">Country <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="primaryCountry" name="primary_country" placeholder="Country" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Secondary Practicing Address Section -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5>Secondary Practicing Address:</h5>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="secondary_clinic_type" id="secondaryGovernment" value="government">
                                            <label class="form-check-label" for="secondaryGovernment">
                                                Government
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="secondary_clinic_type" id="secondaryPrivate" value="private">
                                            <label class="form-check-label" for="secondaryPrivate">
                                                Private
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="secondaryClinicName" class="form-label">Name of clinic/hospital</label>
                                        <input type="text" class="form-control" id="secondaryClinicName" name="secondary_clinic_name" placeholder="Name of clinic/hospital">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label for="secondaryAddress" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="secondaryAddress" name="secondary_address" placeholder="Address">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mb-3">
                                        <label for="secondaryPostCode" class="form-label">PostCode</label>
                                        <input type="text" class="form-control" id="secondaryPostCode" name="secondary_postcode" placeholder="PostCode">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="secondaryCity" class="form-label">City</label>
                                        <input type="text" class="form-control" id="secondaryCity" name="secondary_city" placeholder="City">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="secondaryState" class="form-label">State</label>
                                        <input type="text" class="form-control" id="secondaryState" name="secondary_state" placeholder="State">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="secondaryCountry" class="form-label">Country</label>
                                        <input type="text" class="form-control" id="secondaryCountry" name="secondary_country" placeholder="Country">
                                    </div>
                                </div>
                            </div>

                            <!-- Qualifications Section -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5>Please indicate your qualification(s):</h5>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="institution1" class="form-label">1. Institution <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="institution1" name="institution_1" placeholder="1. Institution" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="qualification1" class="form-label">1. Degree or Qualification <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="qualification1" name="qualification_1" placeholder="1. Degree or Qualification" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="yearObtained1" class="form-label">1. Year Obtained <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="yearObtained1" name="year_obtained_1" placeholder="1. Year Obtained" min="1950" max="2030" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="institution2" class="form-label">2. Institution</label>
                                        <input type="text" class="form-control" id="institution2" name="institution_2" placeholder="2. Institution">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="qualification2" class="form-label">2. Degree or Qualification</label>
                                        <input type="text" class="form-control" id="qualification2" name="qualification_2" placeholder="2. Degree or Qualification">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="yearObtained2" class="form-label">2. Year Obtained</label>
                                        <input type="number" class="form-control" id="yearObtained2" name="year_obtained_2" placeholder="2. Year Obtained" min="1950" max="2030">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="institution3" class="form-label">3. Institution</label>
                                        <input type="text" class="form-control" id="institution3" name="institution_3" placeholder="3. Institution">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="qualification3" class="form-label">3. Degree or Qualification</label>
                                        <input type="text" class="form-control" id="qualification3" name="qualification_3" placeholder="3. Degree or Qualification">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="yearObtained3" class="form-label">3. Year Obtained</label>
                                        <input type="number" class="form-control" id="yearObtained3" name="year_obtained_3" placeholder="3. Year Obtained" min="1950" max="2030">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5>Please provide the details of your registration below:</h5>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="registrationCouncil" class="form-label">Select your Licensing / Registration Body <span class="text-danger">*</span></label>
                                        <select class="form-select" id="registrationCouncil" name="registration_council" required>
                                            <option value="">Select your Licensing / Registration Body</option>
                                            <option value="mmc">Malaysian Medical Council</option>
                                            <option value="mdc">Malaysian Dental Council</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" id="otherCouncilField" style="display:none;">
                                    <div class="mb-3">
                                        <label for="otherCouncil" class="form-label">Please specify <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="otherCouncil" name="other_council" placeholder="Please specify">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="registrationNumber" class="form-label" id="registrationNumberLabel">Registration Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="registrationNumber" name="registration_number" placeholder="Registration Number" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-light me-2" id="step1PrevBtn" style="display: none;">Previous Step</button>
                                    <button type="submit" class="btn btn-primary" id="step1NextBtn">Next Step</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Card 2: Details of Healthcare Services Business -->
            <div class="col-md-12" id="step2Card" style="display: none;">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>2. Details of Healthcare Services Business</h5>
                    </div>
                    <div class="card-body">
                        <form id="healthcareServicesForm">
                            <!-- Type of Professional Indemnity Section -->
                            <div class="row mb-3">
                                <div class="col-md-12 mb-3">
                                    <p style="margin-bottom:2px">Type of Professional Indemnity <span class="text-danger">*</span></p>
                                    <select class="form-select" id="professionalIndemnityType" name="professional_indemnity_type" required>
                                        <option value="">Select Professional Indemnity Type</option>
                                        <option value="medical_practice">Medical Practice</option>
                                        <option value="dental_practice">Dental Practice</option>
                                        <option value="pharmacist">Pharmacist</option>
                                        {{-- <option value="specialist_practice">Specialist Practice</option>
                                        <option value="allied_health">Allied Health</option> --}}
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3" id="employmentStatusSection" style="display: none;">
                                    <p style="margin-bottom:2px">Employment Status <span class="text-danger">*</span></p>
                                    <select class="form-select" id="employmentStatus" name="employment_status">
                                        <option value="">Select Employment Status</option>
                                        <option value="government">Government</option>
                                        <option value="private">Private</option>
                                        <option value="self_employed">Self-Employed</option>
                                        <option value="non_practicing">Non-Practicing</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3" style="display: none;" id="specialtySection">
                                    <p style="margin-bottom:2px">Specialty <span class="text-danger">*</span></p>
                                    <select class="form-select" id="specialtyArea" name="specialty_area">
                                        <option value="">Select Specialty</option>
                                        <option value="general_practice">General Practice</option>
                                        <option value="medical_officer">Medical Officer</option>
                                        <option value="specialist">Specialist</option>
                                        <option value="dental">Dental</option>
                                        <option value="dental_specialist">Dental Specialist</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3" style="display: none;" id="coverTypeSection">
                                    <p style="margin-bottom:2px">Type of Cover <span class="text-danger">*</span></p>
                                    <select class="form-select" id="coverType" name="cover_type">
                                        <option value="">Select Type of Cover</option>
                                        <option value="basic_coverage">Basic Coverage</option>
                                        <option value="comprehensive_coverage">Comprehensive Coverage</option>
                                        <option value="premium_coverage">Premium Coverage</option>
                                    </select>
                                </div>

                                
                                <div class="col-md-12 mb-3" style="display: none;" id="locumPracticeSection">
                                    <p style="margin-bottom:2px">Where do you practice your locum? <span class="text-danger">*</span></p>
                                    <select class="form-select" id="locumPracticeLocation" name="locum_practice_location">
                                        <option value="">Select Practice Location</option>
                                        <option value="private_clinic">Private Clinic</option>
                                        <option value="private_hospital">Private Hospital</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-12 mb-3" style="display: none;" id="serviceTypeSection">
                                    <p style="margin-bottom:2px">Your type of service <span class="text-danger">*</span></p>
                                    <select class="form-select" id="serviceTypeSelection" name="service_type">
                                        <option value="">Select</option>
                                        <option value="core_services">Core Services</option>
                                        <option value="core_services_with_procedures">Core Services with procedures</option>
                                        <option value="general_practitioner_private_hospital_outpatient">General Practitioner in Private Hospital - Outpatient Services</option>
                                        <option value="general_practitioner_private_hospital_emergency">General Practitioner in Private Hospital– Emergency Department</option>
                                        <option value="general_practitioner_with_obstetrics">General Practitioner with Obstetrics</option>
                                        <option value="cosmetic_aesthetic_non_invasive">Cosmetic & Aesthetic – Non - Invasive Elective Topical Enhancement</option>
                                        <option value="cosmetic_aesthetic_non_surgical_invasive">Cosmetic & Aesthetic – Non - Surgical Invasive Elective Topical Enhancement</option>
                                    </select>
                                </div>

                                <div class="row mb-3" style="display: none;" id="serviceDefinitionSection">
                                    <div class="col-md-12">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div id="definitionContent">
                                                    <!-- Dynamic content will be inserted here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 mb-3" style="display: none;" id="practiceAreaSection">
                                    <p style="margin-bottom:2px">Practice Area <span class="text-danger">*</span></p>
                                    <select class="form-select" id="practiceArea" name="practice_area">
                                        <option value="">Select Practice Area</option>
                                        <option value="general_practice">General Practice</option>
                                        <option value="general_practice_with_specialized_procedures">General Practice with Specialized Procedures</option>
                                        <option value="core_services">Core Services</option>
                                        <option value="core_services_with_procedures">Core Services with procedures</option>
                                        <option value="general_practitioner_with_obstetrics">General Practitioner with Obstetrics</option>
                                        <option value="cosmetic_aesthetic_non_invasive">Cosmetic & Aesthetic – Non - Invasive</option>
                                        <option value="cosmetic_aesthetic_non_surgical_invasive">Cosmetic & Aesthetic – Non - Surgical Invasive</option>
                                        <option value="office_clinical_orthopaedics">Office / Clinical Orthopaedics</option>
                                        <option value="ophthalmology_surgeries_non_ga">Ophthalmology Surgeries (Non G.A.)</option>
                                        <option value="cosmetic_aesthetic_surgical_invasive">Cosmetic and Aesthetic ( Surgical, Invasive)</option>
                                        <option value="general_dental_practice">General Dental Practice</option>
                                        <option value="general_dental_practitioners_accredited_specialised_procedures">General Dental Practitioners, practising accredited specialised procedures</option>
                                    </select>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn btn-light me-2" id="step2PrevBtn">Previous Step</button>
                                        <button type="submit" class="btn btn-primary" id="step2NextBtn">Next Step</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Card 3: Pricing Details -->
            <div class="col-md-12" id="step3Card" style="display: none;">
                <div class="card">
                    <div class="card-header pb-0 card-no-border" style="position: relative;">
                        <h5>3. Pricing Details</h5>
                    </div>
                    <div class="card-body">
                        <form id="pricingDetailsForm">
                            <!-- Healthcare Services Information Display -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <h6>Cover Type</h6>
                                    <p id="displayCoverType" class="text-muted">Professional Indemnity - Medical Practitioner</p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Medical Status</h6>
                                    <p id="displayMedicalStatus" class="text-muted">Government Medical Officers - Locum only</p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Class</h6>
                                    <p id="displayClass" class="text-muted">Core Services</p>
                                </div>
                            </div>

                            <hr>

                            <!-- Policy Details Section -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="policyStartDate" class="form-label">Policy Starting Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="policyStartDate" name="policy_start_date" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="policyExpiryDate" class="form-label">Policy Expiry Date</label>
                                    <input type="date" class="form-control" id="policyExpiryDate" name="policy_expiry_date" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="liabilityLimit" class="form-label">Select Liability Limit <span class="text-danger">*</span></label>
                                    <select class="form-select" id="liabilityLimit" name="liability_limit" required>
                                        <option value="">Select Liability Limit</option>
                                        <option value="1000000">RM 1,000,000</option>
                                        <option value="2000000">RM 2,000,000</option>
                                        <option value="5000000">RM 5,000,000</option>
                                        <option value="10000000">RM 10,000,000</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Pricing Breakdown Section -->
                            <hr id="amountHr" style="display: none;">
                            <div id="pricingBreakdown" style="display: none;">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h6>Amount Details</h6>
                                    </div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-6"><span>Limit of Indemnity</span></div>
                                    <div class="col-md-6"><span>: RM <span id="displayLiabilityLimit">0.00</span></span></div>
                                </div>
                                
                                <hr>
                                
                                <div class="row mb-2">
                                    <div class="col-md-6"><span>Premium Per Annum</span></div>
                                    <div class="col-md-6"><span>: RM <span id="displayBasePremium">0.00</span></span></div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-6"><span>Gross Premium</span></div>
                                    <div class="col-md-6"><span>: RM <span id="displayGrossPremium">0.00</span></span></div>
                                </div>
                                
                                <div class="row mb-2" id="locumAddonRow" style="display: none;">
                                    <div class="col-md-6"><span>Locum Extension</span></div>
                                    <div class="col-md-6"><span>: RM <span id="displayLocumAddon">0.00</span></span></div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-6"><span id="sstLabel">8% SST</span></div>
                                    <div class="col-md-6"><span>: RM <span id="displaySST">0.00</span></span></div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-6"><span>Stamp Duty</span></div>
                                    <div class="col-md-6"><span>: RM <span id="displayStampDuty">10.00</span></span></div>
                                </div>
                                
                                <hr>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6"><strong>Total Payable</strong></div>
                                    <div class="col-md-6"><strong>: RM <span id="displayTotalPayable">0.00</span></strong></div>
                                </div>
                                
                                <hr>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-light me-2" id="step3PrevBtn">Previous Step</button>
                                    <button type="submit" class="btn btn-primary" id="step3NextBtn">Next Step</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Card 4: Risk Management -->
            <div class="col-md-12" id="step4Card" style="display: none;">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>4. Risk Management</h5>
                    </div>
                    <div class="card-body">
                        <form id="declarationForm">
                            <!-- Declaration Questions -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <p style="margin-bottom: 15px;">
                                            <strong>Do you maintain accurate records of medical services rendered?</strong>
                                            <span class="text-danger">*</span>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="medical_records" id="medicalRecordsYes" value="yes" required>
                                            <label class="form-check-label" for="medicalRecordsYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="medical_records" id="medicalRecordsNo" value="no" required>
                                            <label class="form-check-label" for="medicalRecordsNo">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <p style="margin-bottom: 15px;">
                                            <strong>Is consent/informed consent obtained and recorded as and when indicated?</strong>
                                            <span class="text-danger">*</span>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="informed_consent" id="informedConsentYes" value="yes" required>
                                            <label class="form-check-label" for="informedConsentYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="informed_consent" id="informedConsentNo" value="no" required>
                                            <label class="form-check-label" for="informedConsentNo">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <p style="margin-bottom: 15px;">
                                            <strong>Do you have procedures for reporting adverse incidents and events?</strong>
                                            <span class="text-danger">*</span>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="adverse_incidents" id="adverseIncidentsYes" value="yes" required>
                                            <label class="form-check-label" for="adverseIncidentsYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="adverse_incidents" id="adverseIncidentsNo" value="no" required>
                                            <label class="form-check-label" for="adverseIncidentsNo">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <p style="margin-bottom: 15px;">
                                            <strong>Do you have facilities for sterilisation of instruments in accordance with relevant guidelines/standards applying to your industry?</strong>
                                            <span class="text-danger">*</span>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sterilisation_facilities" id="sterilisationYes" value="yes" required>
                                            <label class="form-check-label" for="sterilisationYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sterilisation_facilities" id="sterilisationNo" value="no" required>
                                            <label class="form-check-label" for="sterilisationNo">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-light me-2" id="step4PrevBtn">Previous Step</button>
                                    <button type="submit" class="btn btn-primary" id="step4NextBtn">Next Step</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Card 5: Insurance History -->
            <div class="col-md-12" id="step5Card" style="display: none;">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>5. Insurance History</h5>
                    </div>
                    <div class="card-body">
                        <form id="insuranceHistoryForm">
                            <!-- Current Insurance Question -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <p style="margin-bottom: 15px;">
                                            <strong>Do you currently hold medical malpractice insurance (in other insurer company)? If YES, please provide details.</strong>
                                            <span class="text-danger">*</span>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="current_insurance" id="currentInsuranceYes" value="yes" required>
                                            <label class="form-check-label" for="currentInsuranceYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="current_insurance" id="currentInsuranceNo" value="no" required>
                                            <label class="form-check-label" for="currentInsuranceNo">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Current Insurance Details (conditionally shown) -->
                            <div id="currentInsuranceDetailsSection" style="display: none;">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="insurerName" class="form-label">Insurer <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="insurerName" name="insurer_name" placeholder="Insurer Name">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="periodOfInsurance" class="form-label">Period of Insurance</label>
                                        <input type="text" class="form-control" id="periodOfInsurance" name="period_of_insurance" placeholder="Period of Insurance">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="policyLimitMyr" class="form-label">Policy Limit(MYR)</label>
                                        <input type="text" class="form-control" id="policyLimitMyr" name="policy_limit_myr" placeholder="Policy Limit (MYR)">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="excessMyr" class="form-label">Excess(MYR)</label>
                                        <input type="text" class="form-control" id="excessMyr" name="excess_myr" placeholder="Excess (MYR)">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="retroactiveDate" class="form-label">Retroactive Date</label>
                                        <input type="text" class="form-control" id="retroactiveDate" name="retroactive_date">
                                    </div>
                                </div>
                            </div>

                            <!-- Previous Claims Question -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <p style="margin-bottom: 15px;">
                                            <strong>Have you ever had any application for medical malpractice insurance refused, or had any medical malpractice insurance coverage rescinded or cancelled? (If YES, please provide details on a separate sheet, noting the Section number)</strong>
                                            <span class="text-danger">*</span>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="previous_claims" id="previousClaimsYes" value="yes" required>
                                            <label class="form-check-label" for="previousClaimsYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="previous_claims" id="previousClaimsNo" value="no" required>
                                            <label class="form-check-label" for="previousClaimsNo">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Previous Claims Details (conditionally shown) -->
                            <div id="previousClaimsDetailsSection" style="display: none;">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="claimsDetails" class="form-label">Enter details <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="claimsDetails" name="claims_details" placeholder="Please provide details here..." rows="4"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-light me-2" id="step5PrevBtn">Previous Step</button>
                                    <button type="submit" class="btn btn-primary" id="step5NextBtn">Next Step</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Card 6: Claims Experience -->
            <div class="col-md-12" id="step6Card" style="display: none;">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>6. Claims Experience</h5>
                    </div>
                    <div class="card-body">
                        <form id="claimsExperienceForm">
                            <!-- Question 1: Claims Made -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <p style="margin-bottom: 15px;">
                                            <strong>Have any claims ever been made, or lawsuits been brought against you?</strong>
                                            <span class="form-check" style="display: inline-block; margin-left: 20px;">
                                                <input class="form-check-input" type="radio" name="claims_made" id="claimsMadeYes" value="yes">
                                                <label class="form-check-label" for="claimsMadeYes" style="display: inline;">
                                                    Yes
                                                </label>
                                            </span>
                                            <span class="form-check" style="display: inline-block; margin-left: 20px;">
                                                <input class="form-check-input" type="radio" name="claims_made" id="claimsMadeNo" value="no">
                                                <label class="form-check-label" for="claimsMadeNo" style="display: inline;">
                                                    No
                                                </label>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 2: Aware of Errors -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <p style="margin-bottom: 15px;">
                                            <strong>Are you aware of any errors, omissions, offences, circumstances or allegations which might result in a claim being made against you?</strong>
                                            <span class="form-check" style="display: inline-block; margin-left: 20px;">
                                                <input class="form-check-input" type="radio" name="aware_of_errors" id="awareOfErrorsYes" value="yes">
                                                <label class="form-check-label" for="awareOfErrorsYes" style="display: inline;">
                                                    Yes
                                                </label>
                                            </span>
                                            <span class="form-check" style="display: inline-block; margin-left: 20px;">
                                                <input class="form-check-input" type="radio" name="aware_of_errors" id="awareOfErrorsNo" value="no">
                                                <label class="form-check-label" for="awareOfErrorsNo" style="display: inline;">
                                                    No
                                                </label>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 3: Disciplinary Action -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <p style="margin-bottom: 15px;">
                                            <strong>Have you ever been the subject of disciplinary action or investigation by any authority or regulator or professional body?</strong>
                                            <span class="form-check" style="display: inline-block; margin-left: 20px;">
                                                <input class="form-check-input" type="radio" name="disciplinary_action" id="disciplinaryActionYes" value="yes">
                                                <label class="form-check-label" for="disciplinaryActionYes" style="display: inline;">
                                                    Yes
                                                </label>
                                            </span>
                                            <span class="form-check" style="display: inline-block; margin-left: 20px;">
                                                <input class="form-check-input" type="radio" name="disciplinary_action" id="disciplinaryActionNo" value="no">
                                                <label class="form-check-label" for="disciplinaryActionNo" style="display: inline;">
                                                    No
                                                </label>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Claims Details Section (shown if any YES is selected) -->
                            <div id="claimsDetailsSection" style="display: none;">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <p style="margin-bottom: 15px; font-weight: bold;">
                                            If you had answered Yes to any of the questions in this section, please provide full details overleaf and the status of each claim, lawsuits, allegation or matter, including
                                        </p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="claimDateOfClaim" class="form-label">The date of the claim, suit or allegation <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="claimDateOfClaim" name="claim_date_of_claim" placeholder="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="claimNotifiedDate" class="form-label">The date you notified your previous insurers <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="claimNotifiedDate" name="claim_notified_date" placeholder="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="claimClaimantName" class="form-label">The name of the claimant(s) and the services rendered <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="claimClaimantName" name="claim_claimant_name" placeholder="">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="claimAllegations" class="form-label">The allegations made against you <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="claimAllegations" name="claim_allegations" placeholder="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="claimAmountClaimed" class="form-label">The amount claimed by the claimant(s) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="claimAmountClaimed" name="claim_amount_claimed" placeholder="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="claimStatus" class="form-label">Whether the status is outstanding or finalised <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="claimStatus" name="claim_status" placeholder="">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="claimAmountsPaid" class="form-label">The amounts paid for claims and defence costs to date <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="claimAmountsPaid" name="claim_amounts_paid" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-light me-2" id="step6PrevBtn">Previous Step</button>
                                    <button type="submit" class="btn btn-primary" id="step6NextBtn">Next Step</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card 7: Data Protection Notice & Declaration -->
            <div class="col-md-12" id="step7Card" style="display: none;">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>7. Data Protection Notice & Declaration</h5>
                    </div>
                    <div class="card-body">
                        <form id="dataProtectionForm">
                            <!-- Data Protection Notice Content -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="mb-3">DATA PROTECTION NOTICE</h6>
                                    <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; background-color: #f9f9f9;">
                                        <p>By interacting with Great Eastern General Insurance (Malaysia) Berhad ("Company"), submitting information to the Company, enrolling or signing up for any products or services offered by the Company, you are providing personal information to the Company.</p>

                                        <p><strong>"Personal information"</strong> means any information which relates to you and which has been provided by you to the Company, including but not limited to your name, bio-data or personal profile, National Registration Identity Card number, passport number, address, telephone number, email address, images, your personal preferences, particulars of any third party insured person or beneficiary, and financial and banking account information and any information which may identify you, any insured person, assignee, trustee or beneficiary, that has been or may be collected, stored, used and processed by the Company from time to time. The term "personal information" also includes sensitive personal data which means any personal data consisting of information as to physical or mental health or condition, political opinions, religious beliefs or other beliefs of a similar nature, the commission or alleged commission of any offence.</p>

                                        <p>If you provide us with any personal information relating to a third party, including where you have named them as an insured person, assignee, trustee or beneficiary, or where you refer a third party to us for the purposes of us offering our products and/or services to that third party, by submitting such information to us, you represent to us that you have obtained the consent of the third party to you providing us with their personal information for the purposes set out herein. References to "your personal information" shall include the personal information of third parties provided by you.</p>

                                        <p><strong>Your personal information may be used, recorded, stored, archived, disclosed or otherwise processed by or on behalf of the Company (and its successors in title) for the following purposes:</strong></p>
                                        <p>
                                            <strong>A.</strong> to carry on insurance business, as may be applicable and to carry out any activity or duty as an insurer, including but not limited to any operational or internal management purposes;<br><br>
                                            <strong>B.</strong> to assess or process any proposals or applications made for any of the Company's products and services, including any future underwriting;<br><br>
                                            <strong>C.</strong> any claim or investigation or analysis of such claim, including to ascertain your claims history in order to improve claims processing and prevent fraudulent claims, including any future claims assessment;<br><br>
                                            <strong>D.</strong> to manage and service the Company's relationship with you and to provide you with improved customer service;<br><br>
                                            <strong>E.</strong> to match and update any personal information held by the Company and the Great Eastern group of companies ("Great Eastern") relating to you from time to time (for more information on Great Eastern, log on to greateasterngeneral.com);<br><br>
                                            <strong>F.</strong> to offer and/or process any alterations, variations, cancellation or renewal of products or services by the Company or by Great Eastern;<br><br>
                                            <strong>G.</strong> direct marketing and general marketing of insurance and takaful products and services of the Company and Great Eastern, and of third party products, that may be of interest to you. Please be assured that marketing information in respect of third party products and services will only be sent to you if you have expressly consented to the same;<br><br>
                                            <strong>H.</strong> research and audit including but not limited to historical and statistical purposes;<br><br>
                                            <strong>I.</strong> to exercise any right of subrogation or recovery;<br><br>
                                            <strong>J.</strong> to prevent, investigate, or report any actual or suspected money laundering, terrorist financing, bribery, corruption, actual or suspected fraud including but not limited to insurance fraud, evasion of tax or of economic or trade sanctions, and other criminal or unlawful activities;<br><br>
                                            <strong>K.</strong> for reinsurance;<br><br>
                                            <strong>L.</strong> for litigation or potential litigation; and<br><br>
                                            <strong>M.</strong> if required by law or in good faith, if such action is necessary:<br>
                                            <span style="margin-left: 40px; display: block;">○ to comply with any law enforcement, court orders or legal process, and/or<br>
                                            ○ to protect and defend the rights or property of the Company and Great Eastern (for information, log on to greateasterngeneral.com).</span>
                                        </p>

                                        <p>The Company may also collect and/or verify your personal information from third parties, such as a policyholder who has taken up a policy on you or for your benefit, agents, brokers, business partners and third parties from whom you have been referred to the Company, or third parties from whom we seek or receive information on you in connection with your policy, policy applications, or claims.</p>

                                        <p>The Company may retain your personal information for such time as deemed to be necessary for the purpose of fulfilling any operational, audit, investigation, legal, regulatory, tax or accounting requirements, including but not limited to any potential litigation and future underwriting and claims assessment purposes.</p>

                                        <p><strong>The information that you have provided to the Company is necessary.</strong> If you do not provide the Company with such information, the Company may not be able to provide you with insurance or to respond to any claim.</p>

                                        <p><strong>The Company may disclose and/or provide your personal information to the following parties (within and outside Malaysia) for the purposes stated above:</strong></p>
                                        <p>
                                            <strong>A.</strong> the authorised representatives of the Company;<br><br>
                                            <strong>B.</strong> in relation to third party policies, the policy owner;<br><br>
                                            <strong>C.</strong> in relation to group policies, the policyholder and/or its brokers;<br><br>
                                            <strong>D.</strong> third party service providers (who provide administrative, telecommunications, computer, payment, data processing or storage, or other services to the Company in connection with the operation of our business) to fulfill the Company's obligations to you;<br><br>
                                            <strong>E.</strong> banks and financial institutions;<br><br>
                                            <strong>F.</strong> insurers or takaful providers, fraud detection and prevention services, reinsurance companies, insurance associations or takaful associations and insurance industry regulatory authorities;<br><br>
                                            <strong>G.</strong> any credit reference agencies or, in the event of default, any debt collection agencies;<br><br>
                                            <strong>H.</strong> any insurance rating organisations that collect information about credit history, accident fault, injury description and amounts paid and share it with other insurance companies or takaful providers and others entitled to see it;<br><br>
                                            <strong>I.</strong> any person, who is under a duty of confidentiality and has undertaken to keep such data confidential, which the Company has engaged to fulfil its obligations to you;<br><br>
                                            <strong>J.</strong> any actual or proposed assignee, transferee, participant or sub-participant of the Company's rights or business;<br><br>
                                            <strong>K.</strong> any person to whom the Company is under an obligation to make disclosure under the requirements of any law, rules, regulations, codes of practice or guidelines binding on the Company including, without limitation, any applicable regulators, governmental bodies, or insurance associations, and where otherwise required by law;<br><br>
                                            <strong>L.</strong> other companies in Great Eastern, and the Company's affiliates; and<br><br>
                                            <strong>M.</strong> any business or strategic partners.
                                        </p>

                                        <p>You may access your personal information at any time by contacting our Customer Service Care or visiting our Customer Portal.</p>

                                        <p><strong>You may access certain personal information held by the Company based on the applicable data protection laws of Malaysia.</strong></p>

                                        <p>You may access your personal information at any time by calling Customer Service Care or visiting our Customer Portal. If you have any inquiries such as limiting the processing of certain information, including the withdrawal of consent to receive marketing information, you may contact our Customer Service Care, or write to the Company.</p>

                                        <p>If you have any complaints in respect of your personal information, you may contact our Privacy Officer.<br>
                                        For more information on how the Company deals with your personal information, please log on to our website and read the</p>

                                        <h6 class="mt-4 mb-3"><strong>Client Charter Privacy Policy, as set out below:</strong></h6>
                                        <table class="table table-sm" style="background-color: #f9f9f9;">
                                            <tbody>
                                                <tr style="background-color: #e8e8e8; border-bottom: 1px solid #ddd;">
                                                    <td style="padding: 12px; font-weight: bold; width: 40%;"><strong>Website Laman Sesawang</strong></td>
                                                    <td style="padding: 12px; text-align: right;">greateasterngeneral.com</td>
                                                </tr>
                                                <tr style="background-color: #ffffff; border-bottom: 1px solid #ddd;">
                                                    <td style="padding: 12px; font-weight: bold; width: 40%;"><strong>Customer Portal Portal Pelanggan</strong></td>
                                                    <td style="padding: 12px; text-align: right;">https://econnect-my.greateasternlife.com</td>
                                                </tr>
                                                <tr style="background-color: #e8e8e8; border-bottom: 1px solid #ddd;">
                                                    <td style="padding: 12px; font-weight: bold; width: 40%;"><strong>Customer Service Care Pusat Perkhidmatan Pelanggan</strong></td>
                                                    <td style="padding: 12px; text-align: right;">1300 1300 88 (Press 2 for General Insurance)</td>
                                                </tr>
                                                <tr style="background-color: #ffffff; border-bottom: 1px solid #ddd;">
                                                    <td style="padding: 12px; font-weight: bold; width: 40%;"><strong>Email Address Alamat email</strong></td>
                                                    <td style="padding: 12px; text-align: right;">gicare-my@greateasterngeneral.com</td>
                                                </tr>
                                                <tr style="background-color: #e8e8e8; border-bottom: 1px solid #ddd;">
                                                    <td style="padding: 12px; font-weight: bold; width: 40%;"><strong>Privacy Officer Pegawai Privasi</strong></td>
                                                    <td style="padding: 12px; text-align: right;">+603 - 2786 1162</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <p class="mt-3"><strong>The Company may charge a reasonable fee for access.</strong> If you can show that the personal information held by the Company is not accurate, complete and up to date, the Company will take reasonable steps to ensure it is accurate, complete and up to date upon receiving your verification or feedback.</p>

                                        <p>The Company may review and update this Personal Data Protection Notice from time to time to reflect changes in the law, changes in the business practices, procedures and structure of our Company and Great Eastern, and changes in the community's privacy expectations. It is not generally feasible to notify you of changes to this Personal Data Protection Notice and as such, you can log on to our website to obtain the latest version of the Personal Data Protection Notice at any time.</p>

                                        <p>By interacting with the Company, submitting information to the Company, enrolling or signing up for any products or services offered by the Company, you consent (and where required, explicitly consent) to such use of your personal information including sensitive personal data, in the manner set out in this notice. Such consent and authorisation herein shall extend to any information obtained from any of the insurance policy(ies) presently provided to you, any new application to the Company for insurance, and claim processing, such historical financial or credit records, data or information whether or not provided personally.</p>

                                        <p><em>In the event of any inconsistencies between the English version and the Bahasa Malaysia version of this notice, the English version shall prevail.</em></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Declaration Checkbox -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agreeDeclaration" name="agree_declaration" value="yes" required>
                                        <label class="form-check-label" for="agreeDeclaration">
                                            <strong>I have read and agreed the above declaration</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-light me-2" id="step7PrevBtn">Previous Step</button>
                                    <button type="submit" class="btn btn-primary" id="step7NextBtn">Next Step</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card 8: Declaration & Signature -->
            <div class="col-md-12" id="step8Card" style="display: none;">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>8. Declaration & Signature</h5>
                    </div>
                    <div class="card-body">
                        <form id="declarationSignatureForm">
                            <!-- Declaration Header -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="mb-3"><strong>Declaration</strong></h6>
                                    <p class="mb-3"><strong>Please read and accept the following details</strong></p>
                                </div>
                            </div>

                            <!-- Declaration Content -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; background-color: #f9f9f9;">
                                        <p>I/We hereby declare and agree to the following on behalf of my self/ourselves and any person or entity who may have or claim any interest in the policy issued pursuant to this proposal form.</p>

                                        <p><strong>1. Accuracy of Information</strong><br>
                                        All the foregoing statements and answers in this proposal form together with any other documents or questionnaires submitted in connection with this proposal form and all statements made and answers given to the Company's medical examiner(s), are complete and accurate ("the Information") and I understand that the Information given by me is relevant to the Company in deciding whether to accept my proposal or not and the rates and terms to be applied. The Company may terminate or void the policy contract (if issued), deny or reduce my claim, or change or vary the terms of the policy contract, if there is any non-disclosure, misrepresentation, misstatement, inaccuracy or omission.</p>

                                        <p><strong>2. Marketing Updates</strong><br>
                                        I/We would like to receive updates and information about the company, products, services, promotions, charitable causes or other marketing information from relevant third parties of the Company.</p>

                                        <p><strong>3. Proposal Form Acknowledgment</strong><br>
                                        I/We have fully read and understood all the contents of, and the warnings and advice contained in this proposal form.</p>

                                        <p><strong>4. Data Protection Notice</strong><br>
                                        I/We have fully read and understood the Data Protection Notice above and I/we agree that the Company may process the personal information in the manner set out in the said Notice.</p>

                                        <p><strong>5. Tax Compliance Declaration</strong><br>
                                        I/We declare that any funds and/or assets I/we place with the Company, as well as any profits that they generate, comply with the tax laws of the country(ies) where I/we am/are resident(s), as well as the tax laws of the country(ies) of which I/we am/are citizen(s).</p>

                                        <p><strong>6. Prohibited Persons (Insured)</strong><br>
                                        In the event the Company becomes aware that I/we and/or any other named insured(s) am/are or have become a prohibited person, meaning a person/entity who is subject to any laws, regulations and/or sanctions administered by any governmental or regulatory authorities or any competent authority or law enforcement in any country, which have the effect of prohibiting the Company from providing insurance coverage or otherwise offering any benefits to me/us or any other named insured(s) under the policy or proposal submitted or any cover note issued, whichever applicable, I/we agree that the Company may suspend, terminate or void the policy or my/our insurance coverage under the policy, whichever applicable, with effect from the appropriate date or from inception, as appropriate and at the sole discretion of the Company, and shall not be required to transact any business with me/us in connection with the policy, including but not limited to, making or receiving any payments under the policy or proposal submitted or any cover note issued, whichever applicable.</p>

                                        <p><strong>7. Prohibited Persons (Related Parties)</strong><br>
                                        Further, in the event the Company becomes aware that any of the Life Assured, Trustee, Assignee, Beneficiary, Beneficial Owner and/or Nominee and/or Mortgagee/Financier named in or connected with the policy is or has become a prohibited person, I/we agree that the Company may suspend, terminate, or void the policy or my/our insurance coverage under the policy, whichever applicable, with effect from the appropriate date or from inception, as appropriate and at the sole discretion of the Company, and shall not be required to transact any business in connection with the policy, including but not limited to, making or receiving any payments under the policy or proposal submitted or any cover note issued, whichever applicable.</p>

                                        <p><strong>8. Liability Limitation</strong><br>
                                        Under any of the above circumstances, the Company shall not be deemed to provide cover and/or be liable to pay any claim or benefits under the policy or proposal submitted or any cover note issued, whichever applicable.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Checkbox Agreement -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="agreeDeclarationFinal" name="agree_declaration_final" value="yes" required>
                                        <label class="form-check-label" for="agreeDeclarationFinal">
                                            <strong>I have read and agreed the above declaration</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Signature Section -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label mb-3"><strong>Signature:</strong></label>
                                    <div style="border: 2px solid #999; height: 150px; margin-bottom: 10px; position: relative;">
                                        <canvas id="signatureCanvas" style="width: 100%; height: 100%; cursor: crosshair;"></canvas>
                                    </div>
                                    <button type="button" class="btn btn-secondary w-100" id="clearSignatureBtn">
                                        <i class="fas fa-redo"></i> Clear Signature
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-light me-2" id="step8PrevBtn">Previous Step</button>
                                    <button type="submit" class="btn btn-success" id="step8NextBtn">Submit Application</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection

@section('scripts')
    @include('pages.new-policy.js._new-policy')
    @include('pages.new-policy.js._health-care')
@endsection
