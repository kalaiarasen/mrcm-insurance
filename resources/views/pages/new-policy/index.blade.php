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
                            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                Step 1 of 2
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Step 1: Applicant Details</small>
                            <small class="text-muted">Step 2: Healthcare Services</small>
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
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm Password" required>
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
                                        <button type="submit" class="btn btn-primary" id="step2NextBtn">Submit Application</button>
                                    </div>
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
<script>
// Generate a unique user ID for this session if not exists
function getUserId() {
    let userId = localStorage.getItem('policyUserId');
    if (!userId) {
        userId = 'user_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('policyUserId', userId);
    }
    return userId;
}

// Save form data to localStorage
function saveFormData(step, formData) {
    const userId = getUserId();
    const key = `policy_${userId}_step${step}`;
    localStorage.setItem(key, JSON.stringify(formData));
    console.log(`Data saved for step ${step}:`, formData);
}

// Load form data from localStorage
function loadFormData(step) {
    const userId = getUserId();
    const key = `policy_${userId}_step${step}`;
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : {};
}

// Clear all saved data for current user
function clearAllSavedData() {
    const userId = getUserId();
    for (let i = 1; i <= totalSteps; i++) {
        const key = `policy_${userId}_step${i}`;
        localStorage.removeItem(key);
    }
    console.log('All saved data cleared for user:', userId);
}

// Get all saved data
function getAllSavedData() {
    const allData = {};
    for (let i = 1; i <= totalSteps; i++) {
        const stepData = loadFormData(i);
        Object.assign(allData, stepData);
    }
    return allData;
}

// Debug function to view all saved data
function debugSavedData() {
    const userId = getUserId();
    console.log('Current User ID:', userId);
    for (let i = 1; i <= totalSteps; i++) {
        const data = loadFormData(i);
        console.log(`Step ${i} data:`, data);
    }
    console.log('All combined data:', getAllSavedData());
}

// Toggle debug panel
function toggleDebugPanel() {
    const panel = document.getElementById('debugPanel');
    if (panel) {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }
}

// Populate form with saved data
function populateForm(step, data) {
    Object.keys(data).forEach(name => {
        const element = document.querySelector(`[name="${name}"]`);
        if (element) {
            if (element.type === 'checkbox' || element.type === 'radio') {
                element.checked = data[name];
            } else {
                element.value = data[name];
            }
        }
    });
    
    // For step 2, restore the cascading dropdown state
    if (step === 2) {
        restoreHealthcareServicesState(data);
    }
}

// Restore healthcare services cascading dropdown state
function restoreHealthcareServicesState(data) {
    setTimeout(() => {
        // Trigger professional indemnity type change if set
        if (data.professional_indemnity_type) {
            const professionalIndemnitySelect = document.getElementById('professionalIndemnityType');
            if (professionalIndemnitySelect) {
                professionalIndemnitySelect.dispatchEvent(new Event('change'));
                
                // Chain the subsequent selections
                setTimeout(() => {
                    if (data.employment_status) {
                        const employmentStatusSelect = document.getElementById('employmentStatus');
                        if (employmentStatusSelect) {
                            employmentStatusSelect.dispatchEvent(new Event('change'));
                            
                            setTimeout(() => {
                                if (data.specialty_area) {
                                    const specialtyAreaSelect = document.getElementById('specialtyArea');
                                    if (specialtyAreaSelect) {
                                        specialtyAreaSelect.dispatchEvent(new Event('change'));
                                        
                                        setTimeout(() => {
                                            if (data.cover_type) {
                                                const coverTypeSelect = document.getElementById('coverType');
                                                if (coverTypeSelect) {
                                                    coverTypeSelect.dispatchEvent(new Event('change'));
                                                    
                                                    setTimeout(() => {
                                                        if (data.locum_practice_location) {
                                                            const locumSelect = document.getElementById('locumPracticeLocation');
                                                            if (locumSelect) {
                                                                locumSelect.dispatchEvent(new Event('change'));
                                                            }
                                                        }
                                                        if (data.service_type) {
                                                            const serviceTypeSelect = document.getElementById('serviceTypeSelection');
                                                            if (serviceTypeSelect) {
                                                                serviceTypeSelect.dispatchEvent(new Event('change'));
                                                            }
                                                        }
                                                    }, 100);
                                                }
                                            }
                                        }, 100);
                                    }
                                }
                            }, 100);
                        }
                    }
                }, 100);
            }
        }
    }, 100);
}

// Get form data as object
function getFormData(formElement) {
    const formData = new FormData(formElement);
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    return data;
}

// Step navigation
let currentStep = 1;
const totalSteps = 2;

function updateProgressBar(step) {
    const progressBar = document.getElementById('progressBar');
    const progressPercentage = (step / totalSteps) * 100;
    
    if (progressBar) {
        progressBar.style.width = progressPercentage + '%';
        progressBar.setAttribute('aria-valuenow', progressPercentage);
        progressBar.textContent = `Step ${step} of ${totalSteps}`;
        
        // Change color based on completion
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
    // Hide all steps
    for (let i = 1; i <= totalSteps; i++) {
        const stepCard = document.getElementById(`step${i}Card`);
        if (stepCard) {
            stepCard.style.display = 'none';
        }
    }
    
    // Show current step
    const currentCard = document.getElementById(`step${step}Card`);
    if (currentCard) {
        currentCard.style.display = 'block';
    }
    
    // Update progress bar
    updateProgressBar(step);
    
    // Load saved data for this step
    const savedData = loadFormData(step);
    if (Object.keys(savedData).length > 0) {
        populateForm(step, savedData);
    }
    
    currentStep = step;
}

function nextStep() {
    if (currentStep < totalSteps) {
        showStep(currentStep + 1);
    }
}

function prevStep() {
    if (currentStep > 1) {
        showStep(currentStep - 1);
    }
}

$(document).ready(function() {
    // Initialize - show first step and load any saved data
    showStep(1);
    
    // Handle nationality status change
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

    // Handle registration council change
    $('#registrationCouncil').on('change', function() {
        const value = $(this).val();
        const otherField = $('#otherCouncilField');
        const otherInput = $('#otherCouncil');
        const registrationLabel = $('#registrationNumberLabel');
        const registrationInput = $('#registrationNumber');
        
        // Reset fields
        otherInput.val('');
        registrationInput.val('');
        
        if (value === 'mmc') {
            // Malaysian Medical Council
            otherField.hide();
            otherInput.prop('required', false);
            registrationLabel.html('MMC Number <span class="text-danger">*</span>');
            registrationInput.attr('placeholder', 'Enter MMC Number');
            registrationInput.prop('required', true);
        } else if (value === 'mdc') {
            // Malaysian Dental Council
            otherField.hide();
            otherInput.prop('required', false);
            registrationLabel.html('MDC Number <span class="text-danger">*</span>');
            registrationInput.attr('placeholder', 'Enter MDC Number');
            registrationInput.prop('required', true);
        } else if (value === 'others') {
            // Others - show specify field
            otherField.show();
            otherInput.prop('required', true);
            registrationLabel.html('Registration Number <span class="text-danger">*</span>');
            registrationInput.attr('placeholder', 'Enter Registration Number');
            registrationInput.prop('required', true);
        } else {
            // No selection
            otherField.hide();
            otherInput.prop('required', false);
            registrationLabel.html('Registration Number <span class="text-danger">*</span>');
            registrationInput.attr('placeholder', 'Registration Number');
            registrationInput.prop('required', false);
        }
    });

    // Step 1 form submission
    $('#policyApplicationForm').on('submit', function(e) {
        e.preventDefault();
        
        // Check if passwords match
        const password = $('#password').val();
        const confirmPassword = $('#confirmPassword').val();
        
        if (password !== confirmPassword) {
            alert('Passwords do not match. Please check and try again.');
            $('#confirmPassword').focus();
            return;
        }
        
        // Basic validation
        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }
        
        // Save form data
        const formData = getFormData(this);
        saveFormData(1, formData);
        
        // Show success message and move to next step
        // alert('Step 1 data saved successfully!');
        nextStep();
    });        // Step 2 form submission
        $('#healthcareServicesForm').on('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            if (!this.checkValidity()) {
                e.stopPropagation();
                $(this).addClass('was-validated');
                return;
            }
            
            // Save form data
            const formData = getFormData(this);
            saveFormData(2, formData);
            
            // Get all saved data
            const allData = getAllSavedData();
            
            // Show success message with data summary
            let dataSummary = 'Application submitted successfully!\n\nData Summary:\n';
            dataSummary += `User ID: ${getUserId()}\n`;
            dataSummary += `Total fields saved: ${Object.keys(allData).length}\n\n`;
            dataSummary += 'Check browser console for complete data details.';
            
            alert(dataSummary);
            console.log('Complete application data:', allData);
            
            // Update progress to show completion
            updateProgressBar(totalSteps);
            
            // Here you would normally submit all data to the server
            // Example: 
            // fetch('/api/submit-policy', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify(allData)
            // });
        });

        // Navigation button handlers
        $('#step1NextBtn').on('click', function() {
            $('#policyApplicationForm').trigger('submit');
        });

        $('#step2PrevBtn').on('click', function() {
            // Save current step 2 data before going back
            const formData = getFormData(document.getElementById('healthcareServicesForm'));
            saveFormData(2, formData);
            prevStep();
        });

        $('#step2NextBtn').on('click', function() {
            $('#healthcareServicesForm').trigger('submit');
        });

        // Real-time password confirmation validation
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

        // Auto-save on form field changes (optional - saves data as user types)
        $('#policyApplicationForm input, #policyApplicationForm select').on('change', function() {
            const formData = getFormData(document.getElementById('policyApplicationForm'));
            saveFormData(1, formData);
        });

        $('#healthcareServicesForm input, #healthcareServicesForm select').on('change', function() {
            const formData = getFormData(document.getElementById('healthcareServicesForm'));
            saveFormData(2, formData);
        });
    });
</script>
<script>
    // Healthcare Services Form Logic (Step 2)
    document.addEventListener('DOMContentLoaded', function() {
        const professionalIndemnityType = document.getElementById('professionalIndemnityType');
        const employmentStatusSection = document.getElementById('employmentStatusSection');
        const employmentStatus = document.getElementById('employmentStatus');
        const specialtySection = document.getElementById('specialtySection');
        const specialtyArea = document.getElementById('specialtyArea');
        const coverTypeSection = document.getElementById('coverTypeSection');
        const coverType = document.getElementById('coverType');
        const practiceAreaSection = document.getElementById('practiceAreaSection');
        const practiceArea = document.getElementById('practiceArea');
        const locumPracticeSection = document.getElementById('locumPracticeSection');
        const locumPracticeLocation = document.getElementById('locumPracticeLocation');
        const serviceTypeSection = document.getElementById('serviceTypeSection');
        const serviceTypeSelection = document.getElementById('serviceTypeSelection');
        const serviceDefinitionSection = document.getElementById('serviceDefinitionSection');

        // Step 1: Professional Indemnity Type Change
        professionalIndemnityType.addEventListener('change', function() {
            const selectedValue = this.value;
            
            // Reset ALL subsequent fields and hide ALL subsequent sections
            resetAllFields();
            hideAllSections();
            
            if (selectedValue === 'medical_practice' || selectedValue === 'dental_practice') {
                employmentStatusSection.style.display = 'block';
                employmentStatus.required = true;
                updateEmploymentStatusOptions(selectedValue);
            }
        });

        // Step 2: Employment Status Change
        employmentStatus.addEventListener('change', function() {
            const professionalType = professionalIndemnityType.value;
            const employmentType = this.value;
            
            // Reset subsequent fields and hide subsequent sections
            resetFieldsFromSpecialty();
            hideAllSectionsFromEmployment();
            
            if (employmentType) {
                specialtySection.style.display = 'block';
                specialtyArea.required = true;
                updateSpecialtyOptions(professionalType, employmentType);
            }
        });

        // Step 3: Specialty Change
        specialtyArea.addEventListener('change', function() {
            const selectedValue = this.value;
            const professionalType = professionalIndemnityType.value;
            const employmentType = employmentStatus.value;
            
            // Reset subsequent fields and hide subsequent sections
            resetFieldsFromCoverType();
            hideAllSectionsFromSpecialty();
            
            if (selectedValue) {
                // If lecturer_trainee, form is complete
                if (selectedValue === 'lecturer_trainee') {
                    // Form complete for lecturer/trainee - no more sections needed
                    alert('Form completed!');

                } else {
                    coverTypeSection.style.display = 'block';
                    coverType.required = true;
                    updateCoverTypeOptions(professionalType, employmentType, selectedValue);
                }
            }
        });

        // Step 4: Cover Type Change
        coverType.addEventListener('change', function() {
            const selectedValue = this.value;
            const employmentType = employmentStatus.value;
            const specialtyType = specialtyArea.value;
            const professionalType = professionalIndemnityType.value;
            
            // Reset subsequent fields and hide subsequent sections
            resetFieldsFromPracticeArea();
            hideAllSectionsFromCoverType();
            
            if (selectedValue) {
                // Handle Dental Specialist practicing Oral and Maxillofacial Surgery
                if (professionalType === 'dental_practice' && specialtyType === 'dentist_specialist' && selectedValue === 'dental_specialist_oral_maxillofacial_surgery') {
                    // Show service type selection for field of practice
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    updateDentalSpecialistFieldOptions();
                }
                // Handle other Dental Specialist selections
                else if (professionalType === 'dental_practice' && specialtyType === 'dentist_specialist' && selectedValue === 'dental_specialists') {
                    // Form complete for general dental specialists
                    alert('Form completed for Dental Specialists!');
                }
                // Handle Medical Practice - Private General Practitioner
                else if (professionalType === 'medical_practice' && employmentType === 'private' && specialtyType === 'general_practitioner') {
                    const servicesWithDefinitions = [
                        'core_services',
                        'core_services_with_procedures',
                        'general_practitioner_with_obstetrics',
                        'cosmetic_aesthetic_non_invasive',
                        'cosmetic_aesthetic_non_surgical_invasive'
                    ];
                    
                    if (servicesWithDefinitions.includes(selectedValue)) {
                        showServiceDefinition(selectedValue);
                    }
                    
                    // Form complete after showing definition
                    alert('Form completed!');
                }
                // Handle Dental Practice - General Dentist
                else if (professionalType === 'dental_practice' && specialtyType === 'general_dentist') {
                    // Show definition directly based on cover type, then go to coverage details
                    
                    // Check if cover type needs definition
                    const servicesWithDefinitions = [
                        'general_dental_practice',
                        'general_dental_practitioners'
                    ];
                    
                    if (servicesWithDefinitions.includes(selectedValue)) {
                        showServiceDefinition(selectedValue);
                    }
                    
                    // Form complete after showing definition
                    setTimeout(() => {
                        alert('Form completed for Dental Practice!');
                    }, 100);
                }
                // Handle Medical Practice - Locum Cover
                else if (selectedValue === 'locum_cover') {
                    locumPracticeSection.style.display = 'block';
                    locumPracticeLocation.required = true;
                } else if (selectedValue === 'general_cover') {
                    // Show service type selection for General Cover
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    updateServiceTypeOptions('general_cover');
                } else if (selectedValue === 'low_risk_specialist' || selectedValue === 'medium_risk_specialist') {
                    // Show service type selection for Specialists
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    updateSpecialistServiceOptions(selectedValue);
                } else {
                    // For other cover types, go directly to Practice Area
                    practiceAreaSection.style.display = 'block';
                    practiceArea.required = true;
                }
            }
        });

        // Step 5: Locum Practice Location Change
        locumPracticeLocation.addEventListener('change', function() {
            const selectedValue = this.value;
            
            // Reset subsequent fields and hide subsequent sections
            resetFieldsFromServiceType();
            hideAllSectionsFromServiceType();
            
            if (selectedValue) {
                // Show service type selection for Locum Cover with location-specific options
                serviceTypeSection.style.display = 'block';
                serviceTypeSelection.required = true;
                updateServiceTypeOptions('locum_cover', selectedValue);
            }
        });

        // Step 6: Service Type Selection Change
        serviceTypeSelection.addEventListener('change', function() {
            const selectedValue = this.value;
            const coverTypeValue = coverType.value;
            const professionalType = professionalIndemnityType.value;
            const specialtyType = specialtyArea.value;
            
            // Reset subsequent fields and hide subsequent sections - BUT KEEP SERVICE TYPE SECTION VISIBLE
            resetFieldsFromServiceType();
            hideAllSectionsFromServiceType();
            
            if (selectedValue) {
                // Handle dental specialist field selections
                if (professionalType === 'dental_practice' && specialtyType === 'dentist_specialist' && 
                    coverTypeValue === 'dental_specialist_oral_maxillofacial_surgery') {
                    // Form complete for dental specialist fields
                    alert('Form completed for Dental Specialist Oral and Maxillofacial Surgery!');
                } else {
                    // Check if it's a service type that needs definition
                    const servicesWithDefinitions = [
                        'office_clinical_orthopaedics',
                        'cosmetic_aesthetic_surgical_invasive', 
                        'ophthalmology_surgeries_non_ga',
                        'core_services',
                        'core_services_with_procedures',
                        'general_practitioner_with_obstetrics',
                        'cosmetic_aesthetic_non_invasive',
                        'cosmetic_aesthetic_non_surgical_invasive',
                        'general_practice',
                        'general_practice_with_specialized_procedures'
                    ];
                    
                    if (servicesWithDefinitions.includes(selectedValue)) {
                        // Show definition for these services
                        showServiceDefinition(selectedValue);
                    }
                    
                    // Go directly to form completion
                    alert('Form completed for selected service!');
                }
            }
        });

        // Step 7: Practice Area Change (for other paths)
        practiceArea.addEventListener('change', function() {
            const selectedValue = this.value;
            
            // Reset subsequent fields and hide subsequent sections
            resetFieldsFromCoverageDetails();
            hideAllSectionsFromCoverageDetails();
            
            if (selectedValue) {
                alert('Form completed for selected practice area!');
            }
        });

        // Function to update service type options based on cover type
         function updateServiceTypeOptions(coverType, locumLocation = null) {
            const serviceSelect = document.getElementById('serviceTypeSelection');
            
            if (coverType === 'locum_cover') {
                if (locumLocation === 'private_clinic') {
                    // Only 2 options for Locum Cover at Private Clinic
                    serviceSelect.innerHTML = `
                        <option value="">Select</option>
                        <option value="core_services">Core Services</option>
                        <option value="core_services_with_procedures">Core Services with procedures</option>
                    `;
                } else if (locumLocation === 'private_hospital') {
                    // Different options for Locum Cover at Private Hospital
                    serviceSelect.innerHTML = `
                        <option value="">Select</option>
                        <option value="general_practitioner_private_hospital_outpatient">Outpatient Service</option>
                        <option value="general_practitioner_private_hospital_emergency">Emergency Department</option>
                    `;
                }
            } else if (coverType === 'general_cover') {
                // All options for General Cover
                serviceSelect.innerHTML = `
                    <option value="">Select</option>
                    <option value="core_services">Core Services</option>
                    <option value="core_services_with_procedures">Core Services with procedures</option>
                    <option value="general_practitioner_private_hospital_outpatient">General Practitioner in Private Hospital - Outpatient Services</option>
                    <option value="general_practitioner_private_hospital_emergency">General Practitioner in Private Hospital– Emergency Department</option>
                    <option value="general_practitioner_with_obstetrics">General Practitioner with Obstetrics</option>
                    <option value="cosmetic_aesthetic_non_invasive">Cosmetic & Aesthetic – Non - Invasive Elective Topical Enhancement</option>
                    <option value="cosmetic_aesthetic_non_surgical_invasive">Cosmetic & Aesthetic – Non - Surgical Invasive Elective Topical Enhancement</option>
                `;
            }
        }

        // Function to show service definition
        function showServiceDefinition(serviceType) {
            const definitionContent = document.getElementById('definitionContent');
            
            if (serviceType === 'core_services') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <ol>
                        <li>History taking, Examination, and Diagnosis.</li>
                        <li>Prescription, Injections IM & IV, and IV Drips in Emergencies.</li>
                        <li>Immunizations, I&D, T&S under Local Anesthesia. Simple Removal of Foreign Bodies from ENT & Eye</li>
                        <li>Neubelisation.</li>
                        <li>CPR.</li>
                        <li>Medical Examinations / Screenings.</li>
                        <li>Urine and Blood Testings / Analysis.</li>
                        <li>Plain Xrays.</li>
                        <li>Ante-Natal Screenings up to 24 weeks. Pap Smears.</li>
                        <li>Family Planning Advice and Prescriptions.</li>
                        <li>Other similar services traditionally carried out by General Practitioners</li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_dental_practice') {
                // For General Dental Practice cover type
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <ol>
                        <li>Complete dental examinations and diagnosis of disease including x-rays</li>
                        <li>Preventive dentistry (e.g cleanings, oral hygiene instruction, fluoride treatments, fissure sealants, scaling)</li>
                        <li>Extractions, fillings, crowns, veneers, bridges, dentures</li>
                        <li>Minor oral surgeries (e.g. laserations, gum injuries, broken tooths, simple root canal treatment and wisdom tooth extractions)<br />
                            <strong style="font-weight:bold">*Please choose 2 million cover if you do minor oral surgeries.</strong>
                        </li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_dental_practitioners') {
                // For General Dental Practitioners, practising accredited specialised procedures
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <strong class="text-dark">Accredited specialized procedures includes:</strong>
                    <ol>
                        <li>Braces</li>
                        <li>Periodontics</li>
                        <li>Endodontics</li>
                        <li>Implants</li>
                        <li>Orthodontics</li>
                        <li>Oral Surgeries</li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_practice') {
                // For Government dental services
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <ol>
                        <li>Complete dental examinations and diagnosis of disease including x-rays</li>
                        <li>Preventive dentistry (e.g cleanings, oral hygiene instruction, fluoride treatments, fissure sealants, scaling)</li>
                        <li>Extractions, fillings, crowns, veneers, bridges, dentures</li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_practice_with_specialized_procedures') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <ol>
                        <li>Braces</li>
                        <li>Periodontics</li>
                        <li>Endodontics</li>
                        <li>Implants</li>
                        <li>Orthodontics</li>
                        <li>Oral Surgeries</li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            }
            // ... rest of existing medical definitions ...
            else if (serviceType === 'core_services_with_procedures') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <ol>
                        <li>Removal of Ingrowing Toe Nails.</li>
                        <li>Excisions of Lumps & Bumps (Non-Facial Warts, Cysts, Lipomas, Granulomas)</li>
                        <li>Insertions and Removals of IUCDs</li>
                        <li>Cortisone Injections. ( Tendonitis, Teno-synovitis, Plantar Fascitis )</li>
                        <li>Immobilizations of Undisplaced Fractures of Metacarpal and Phalangeal Joints.</li>
                        <li>Circumcision</li>
                        <li>Other similar procedures traditionally carried out by General Practitioners</li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_practitioner_with_obstetrics') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p>36 weeks (full term of pregnancy) exclude deliveries</p>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'cosmetic_aesthetic_non_invasive') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p>Non-invasive procedures: External applications or treatment procedures that are carried out without creating a break in the skin or penetration of the integument. They target the epidermis only.</p>
                    <ul>
                        <li>Superficial chemical peels</li>
                        <li>Microdermabrasion</li>
                        <li>Intense pulsed light</li>
                    </ul>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'cosmetic_aesthetic_non_surgical_invasive') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p>Minimally invasive procedures: Treatment procedures that induce minimal damage to the tissues at the point of entry of instruments. These procedures involve penetration or transgression of integument but are limited to the sub-dermis and subcutaneous fat; not extending beyond the superficial musculo- aponeurotic layer of the face and neck, or beyond the superficial fascial layer of the torso and limbs.</p>
                    <strong class="text-dark">They are limited to the following procedures:</strong>
                    <ul>
                        <li>Chemical peel (Medium depth)</li>
                        <li>Botulinum toxin injection</li>
                        <li>Filler injection - excluding silicone and fat</li>
                        <li>Skin tightening procedures-up to upper dermis (radiofrequency, infrared, ultrasound and other devices)</li>
                        <li>Superficial sclerotherapy</li>
                        <li>Lasers for treating skin pigmentation</li>
                        <li>Lasers for treating benign skin lesions</li>
                        <li>Lasers for skin rejuvenation (including non-ablative)</li>
                        <li>Lasers for hair removal</li>
                    </ul>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'office_clinical_orthopaedics') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p><strong>Jobscope:</strong></p>
                    <ul>
                        <li>All excision biopsy of lumps under local</li>
                        <li>All nail/ nail bed procedures</li>
                        <li>administration of local anesthesia</li>
                        <li>Arthrocentesis and joint or soft tissue injections</li>
                        <li>Application of splints or casts</li>
                        <li>Simple amputation under local</li>
                        <li>Tendon/ nerve entrapment release</li>
                        <li>Incision and drainage of soft tissue infection</li>
                        <li>Closed reduction and immobilization of fracture and dislocation</li>
                        <li>Debridement of soft tissue and closer of wound</li>
                        <li>Removal of foreign bodies under local</li>
                        <li>Repair of muscle/ tendon under local</li>
                        <li>Use of fluoroscopy (sedation or local anesthesia procedures)</li>
                        <li>Tissue flap under local</li>
                        <li>Manipulation of joint under sedation/local anesthesia</li>
                    </ul>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'cosmetic_aesthetic_surgical_invasive') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p>Surgical includes excisions of warts, mole, scars and other External Cosmetic Surgery under L.A.</p>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'ophthalmology_surgeries_non_ga') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p>Cataract etc under L.A. (Non G.A.)</p>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_practitioner_private_hospital_outpatient' || serviceType === 'general_practitioner_private_hospital_emergency') {
                // NO DEFINITION for private hospital services - hide the section
                serviceDefinitionSection.style.display = 'none';
            } else {
                serviceDefinitionSection.style.display = 'none';
            }
        }

        // Reset Functions
        function resetAllFields() {
            employmentStatus.value = '';
            specialtyArea.value = '';
            coverType.value = '';
            serviceTypeSelection.value = '';
            locumPracticeLocation.value = '';
            practiceArea.value = '';
        }

        function resetFieldsFromSpecialty() {
            specialtyArea.value = '';
            coverType.value = '';
            serviceTypeSelection.value = '';
            locumPracticeLocation.value = '';
            practiceArea.value = '';
        }

        function resetFieldsFromCoverType() {
            coverType.value = '';
            serviceTypeSelection.value = '';
            locumPracticeLocation.value = '';
            practiceArea.value = '';
        }

        // NEW: Reset function for service type (doesn't reset service type itself)
        function resetFieldsFromServiceType() {
            practiceArea.value = '';
        }

        function resetFieldsFromPracticeArea() {
            practiceArea.value = '';
        }

        function resetFieldsFromCoverageDetails() {
            // No more coverage detail fields to reset - using alerts for completion
        }

        // Hide Functions - Cascading Hide Logic
        function hideAllSections() {
            employmentStatusSection.style.display = 'none';
            specialtySection.style.display = 'none';
            coverTypeSection.style.display = 'none';
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            
            // Remove required attributes
            employmentStatus.required = false;
            specialtyArea.required = false;
            coverType.required = false;
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
        }

        function hideAllSectionsFromEmployment() {
            specialtySection.style.display = 'none';
            coverTypeSection.style.display = 'none';
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            
            specialtyArea.required = false;
            coverType.required = false;
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
        }

        function hideAllSectionsFromSpecialty() {
            coverTypeSection.style.display = 'none';
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            
            coverType.required = false;
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
        }

        function hideAllSectionsFromCoverType() {
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
        }

        // NEW: Hide function for service type (doesn't hide service type section itself)
        function hideAllSectionsFromServiceType() {
            serviceDefinitionSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            
            practiceArea.required = false;
        }

        function hideAllSectionsFromPracticeArea() {
            serviceDefinitionSection.style.display = 'none';
        }

        function hideAllSectionsFromCoverageDetails() {
            // No more sections to hide - form completion alerts are used instead
        }

        // Update Options Functions (keeping existing functions...)
        function updateEmploymentStatusOptions(indemnityType) {
            const employmentSelect = document.getElementById('employmentStatus');
            
            employmentSelect.innerHTML = '<option value="">Select Employment Status</option>';
            
            if (indemnityType === 'medical_practice') {
                employmentSelect.innerHTML += `
                    <option value="government">Government</option>
                    <option value="private">Private</option>
                    <option value="non_practicing">Non-Practicing</option>
                `;
            } else if (indemnityType === 'dental_practice') {
                employmentSelect.innerHTML += `
                    <option value="government">Government</option>
                    <option value="private">Private</option>
                `;
            }
        }

        function updateSpecialtyOptions(professionalType, employmentType) {
            const specialtySelect = document.getElementById('specialtyArea');
            
            specialtySelect.innerHTML = '<option value="">Select Specialty</option>';
            
            if (professionalType === 'medical_practice') {
                if (employmentType === 'government') {
                    specialtySelect.innerHTML += `
                        <option value="medical_officer">Medical Officer</option>
                        <option value="medical_specialist">Medical Specialist</option>
                    `;
                } else if (employmentType === 'private') {
                    specialtySelect.innerHTML += `
                        <option value="general_practitioner">General Practitioner</option>
                        <option value="medical_specialist">Medical Specialist</option>
                    `;
                } else if (employmentType === 'non_practicing') {
                    specialtySelect.innerHTML += `
                        <option value="lecturer_trainee">Lecturer/Trainee</option>
                    `;
                }
            } else if (professionalType === 'dental_practice') {
                if (employmentType === 'government' || employmentType === 'private') {
                    specialtySelect.innerHTML += `
                        <option value="general_dentist">General Dentist</option>
                        <option value="dentist_specialist">Dentist Specialist</option>
                    `;
                }
            }
        }

        function updateCoverTypeOptions(professionalType, employmentType, specialtyType) {
            const coverTypeSelect = document.getElementById('coverType');
            const coverTypeLabel = document.querySelector('label[for="coverType"]') || document.querySelector('#coverTypeSection p');
            
            // Update the label/title based on specialty type and professional type
            if (specialtyType === 'dentist_specialist') {
                if (coverTypeLabel) {
                    coverTypeLabel.innerHTML = 'Your type of service <span class="text-danger">*</span>';
                }
                coverTypeSelect.innerHTML = '<option value="">Select Specialty</option>';
            } else if (professionalType === 'dental_practice') {
                if (coverTypeLabel) {
                    coverTypeLabel.innerHTML = 'Type of cover <span class="text-danger">*</span>';
                }
                coverTypeSelect.innerHTML = '<option value="">Select Type of Cover</option>';
            } else if (specialtyType === 'medical_specialist') {
                if (coverTypeLabel) {
                    coverTypeLabel.innerHTML = 'Select Your Specialist Category <span class="text-danger">*</span>';
                }
                coverTypeSelect.innerHTML = '<option value="">Select Your Specialist Category</option>';
            } else {
                if (coverTypeLabel) {
                    coverTypeLabel.innerHTML = 'Your type of service <span class="text-danger">*</span>';
                }
                coverTypeSelect.innerHTML = '<option value="">Select</option>';
            }
            
            if (professionalType === 'medical_practice') {
                if (employmentType === 'government') {
                    if (specialtyType === 'medical_officer') {
                        coverTypeSelect.innerHTML += `
                            <option value="locum_cover">Locum Cover</option>
                            <option value="general_cover">General Cover</option>
                        `;
                    } else if (specialtyType === 'medical_specialist') {
                        coverTypeSelect.innerHTML += `
                            <option value="low_risk_specialist">Low Risk Specialist</option>
                            <option value="medium_risk_specialist">Medium Risk Specialist</option>
                        `;
                    }
                } else if (employmentType === 'private') {
                    if (specialtyType === 'general_practitioner') {
                        coverTypeSelect.innerHTML += `
                            <option value="core_services">Core Services</option>
                            <option value="core_services_with_procedures">Core Services with procedures</option>
                            <option value="general_practitioner_private_hospital_outpatient">General Practitioner in Private Hospital - Outpatient Services</option>
                            <option value="general_practitioner_private_hospital_emergency">General Practitioner in Private Hospital– Emergency Department</option>
                            <option value="general_practitioner_with_obstetrics">General Practitioner with Obstetrics</option>
                            <option value="cosmetic_aesthetic_non_invasive">Cosmetic & Aesthetic – Non - Invasive Elective Topical Enhancement</option>
                            <option value="cosmetic_aesthetic_non_surgical_invasive">Cosmetic & Aesthetic – Non - Surgical Invasive Elective Topical Enhancement</option>
                        `;
                    } else if (specialtyType === 'medical_specialist') {
                        coverTypeSelect.innerHTML += `
                            <option value="low_risk_specialist">Low Risk Specialist</option>
                            <option value="medium_risk_specialist">Medium Risk Specialist</option>
                        `;
                    }
                }
            } else if (professionalType === 'dental_practice') {
                if (specialtyType === 'dentist_specialist') {
                    // For Dental Specialist, show specialty options directly
                    coverTypeSelect.innerHTML += `
                        <option value="dental_specialists">Dental Specialists</option>
                        <option value="dental_specialist_oral_maxillofacial_surgery">Dental Specialist practicing Oral and Maxillofacial Surgery</option>
                    `;
                } else if (specialtyType === 'general_dentist') {
                    if (employmentType === 'government') {
                        coverTypeSelect.innerHTML += `
                            <option value="locum_cover_only">Locum cover only</option>
                            <option value="general_cover">General Cover</option>
                        `;
                    } else if (employmentType === 'private') {
                        coverTypeSelect.innerHTML += `
                            <option value="general_dental_practice">General Dental Practice</option>
                            <option value="general_dental_practitioners">General Dental Practitioners, practising accredited specialised procedures</option>
                        `;
                    }
                }
            }
        }

        function updateSpecialistServiceOptions(specialistCategory) {
            const serviceSelect = document.getElementById('serviceTypeSelection');
            
            if (specialistCategory === 'low_risk_specialist') {
                serviceSelect.innerHTML = `
                    <option value="">Select</option>
                    <option value="occupational_health_physicians">Occupational Health Physicians / Family Physicians</option>
                    <option value="general_physicians">General Physicians</option>
                    <option value="dermatology_non_cosmetic">Dermatology - Non - Cosmetic</option>
                    <option value="infections_diseases">Infections Diseases</option>
                    <option value="pathology">Pathology</option>
                    <option value="psychiatry">Psychiatry</option>
                    <option value="endocrinology">Endocrinology</option>
                    <option value="rehab_medicine">Rehab, medicine</option>
                    <option value="paediatrics_non_neonatal">Paediatrics - (Non Neonatal)</option>
                    <option value="geriatrics">Geriatrics</option>
                    <option value="haemotology">Haemotology</option>
                    <option value="immunology">Immunology</option>
                    <option value="nephrology">Nephrology</option>
                    <option value="nuclear_medicine">Nuclear medicine</option>
                    <option value="neurology">Neurology</option>
                    <option value="radiology_non_interventional">Radiology(Non Interventional)</option>
                `;
            } else if (specialistCategory === 'medium_risk_specialist') {
                serviceSelect.innerHTML = `
                    <option value="">Select</option>
                    <option value="ophthalmology_office_procedures">Ophthalmology / Office procedures</option>
                    <option value="office_ent_clinic_based">Office ENT(Clinic based)</option>
                    <option value="ophthalmology_surgeries_non_ga">Ophthalmology Surgeries (Non G.A.)</option>
                    <option value="ent_surgeries_non_ga">ENT Surgeries(Non G.A.)</option>
                    <option value="radiology_interventional">Radiology - Interventional</option>
                    <option value="gastroenterology">Gastroenterology</option>
                    <option value="office_clinical_orthopaedics">Office / Clinical Orthopaedics</option>
                    <option value="office_clinical_gynaecology">Office / Clinical Gynaecology</option>
                    <option value="cosmetic_aesthetic_non_surgical_invasive">Cosmetic and Aesthetic (Non-surgical Invasive elective topical enhancement)</option>
                    <option value="cosmetic_aesthetic_surgical_invasive">Cosmetic and Aesthetic ( Surgical, Invasive)</option>
                `;
            }
        }

        function updateDentalServiceOptions(coverType) {
            const serviceSelect = document.getElementById('serviceTypeSelection');
            
            if (coverType === 'general_cover' || coverType === 'locum_cover_only') {
                serviceSelect.innerHTML = `
                    <option value="">Select</option>
                    <option value="general_practice">General Practice</option>
                    <option value="general_practice_with_specialized_procedures">General Practice with Specialized Procedures</option>
                `;
            }
        }

        function updateDentalSpecialistFieldOptions() {
            const serviceSelect = document.getElementById('serviceTypeSelection');
            const serviceTypeLabel = document.querySelector('label[for="serviceTypeSelection"]') || document.querySelector('#serviceTypeSection p');
            
            // Update the label to "Please select your field of practice"
            if (serviceTypeLabel) {
                serviceTypeLabel.innerHTML = 'Please select your field of practice <span class="text-danger">*</span>';
            }
            
            serviceSelect.innerHTML = `
                <option value="">Select</option>
                <option value="clinic_based_non_general_anaesthetic">Clinic based Non-General Anaesthetic Dental only procedures</option>
                <option value="hospital_based_full_fledged_omfs">Hospital-based full-fledged OMFS</option>
            `;
        }
    });
</script>
@endsection
