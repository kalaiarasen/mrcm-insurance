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
                            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">
                                Step 1 of 3
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Step 1: Applicant Details</small>
                            <small class="text-muted">Step 2: Healthcare Services</small>
                            <small class="text-muted">Step 3: Pricing Details</small>
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
                        <span class="badge bg-primary" style="position: absolute; top: 15px; right: 15px;">Great Eastern</span>
                    </div>
                    <div class="card-body">
                        <form id="pricingDetailsForm">
                            <!-- Quote Summary Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="mb-3">Policy Summary</h6>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <h6>Cover Type</h6>
                                    <p id="summaryCoverType" class="text-muted">-</p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Professional Type</h6>
                                    <p id="summaryProfessionalType" class="text-muted">-</p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Employment Status</h6>
                                    <p id="summaryEmploymentStatus" class="text-muted">-</p>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h6>Specialty</h6>
                                    <p id="summarySpecialty" class="text-muted">-</p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Service Type</h6>
                                    <p id="summaryServiceType" class="text-muted">-</p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Applicant Name</h6>
                                    <p id="summaryApplicantName" class="text-muted">-</p>
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
                                    <div class="col-md-6"><span id="sstLabel">6% SST</span></div>
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
                                
                                <!-- Locum Extension Option -->
                                <div id="locumExtensionSection" style="display: none;">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Locum Extension Add-on</h6>
                                                    <p class="mb-3 small">We extend the coverage afforded under this policy for any claim made against you during the period of insurance arising out of malpractice committed or allegedly committed by a locum officer practicing at your clinic.</p>
                                                    
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="locumExtensionCheckbox" name="locum_extension">
                                                        <label class="form-check-label" for="locumExtensionCheckbox">
                                                            Add Locum Extension (Additional premium applies)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-light me-2" id="step3PrevBtn">Previous Step</button>
                                    <button type="submit" class="btn btn-success" id="step3NextBtn">Submit Application</button>
                                </div>
                            </div>
                        </form>
                    </div>
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
