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
    </div>
    
    <div class="container-fluid default-dashboard">
        <div class="row widget-grid">
            <div class="col-md-12">
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
                                    <button type="button" class="btn btn-light me-2">Previous Step</button>
                                    <button type="submit" class="btn btn-primary">Next Step</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
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
                                <div class="row mb-3" style="display: none;" id="coverageDetailsSection">
                                    <div class="col-md-12">
                                        <h5>Coverage Details</h5>
                                        <label for="coverageLimit" class="form-label">Limit of Indemnity <span class="text-danger">*</span></label>
                                        <select class="form-select" id="coverageLimit" name="coverage_limit">
                                            <option value="">Select Coverage Limit</option>
                                            <option value="1000000">RM 1,000,000</option>
                                            <option value="2000000">RM 2,000,000</option>
                                            <option value="3000000">RM 3,000,000</option>
                                            <option value="5000000">RM 5,000,000</option>
                                            <option value="10000000">RM 10,000,000</option>
                                        </select>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="policyPeriod" class="form-label">Policy Period <span class="text-danger">*</span></label>
                                                <select class="form-select" id="policyPeriod" name="policy_period">
                                                    <option value="">Select Policy Period</option>
                                                    <option value="1">1 Year</option>
                                                    <option value="2">2 Years</option>
                                                    <option value="3">3 Years</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="policyStartDate" class="form-label">Policy Start Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="policyStartDate" name="policy_start_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Services Section -->
                                <div class="row mb-3" style="display: none;" id="additionalServicesSection">
                                    <div class="col-md-12">
                                        <h5>Additional Services</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="serviceSelection" class="form-label">Select Additional Services</label>
                                            <select class="form-select" id="serviceSelection" name="service_selection">
                                                <option value="">Select Additional Services (Optional)</option>
                                                <option value="locum_coverage">Locum Coverage</option>
                                                <option value="extended_coverage">Extended Coverage</option>
                                                <option value="international_coverage">International Coverage</option>
                                                <option value="legal_defense">Legal Defense</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Premium Calculation Display -->
                                <div class="row mb-4" style="display: none;" id="premiumCalculationSection">
                                    <div class="col-md-12">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title">Premium Calculation</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Base Premium:</strong> <span id="basePremium">RM 0.00</span></p>
                                                        <p class="mb-1"><strong>Additional Services:</strong> <span id="additionalServices">RM 0.00</span></p>
                                                        <p class="mb-1"><strong>Service Tax (6%):</strong> <span id="serviceTax">RM 0.00</span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Stamp Duty:</strong> <span id="stampDuty">RM 10.00</span></p>
                                                        <hr class="my-2">
                                                        <h6><strong>Total Payable:</strong> <span id="totalPayable" class="text-primary">RM 0.00</span></h6>
                                                        <small class="text-muted">Including all taxes & service charges</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- Form Actions -->
                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-light me-2" id="previousStepBtn">Previous Step</button>
                                    <button type="submit" class="btn btn-primary" id="nextStepBtn">Next Step</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>3. Details of Employment</h5>
                    </div>
                    <div class="card-body">
                        <form id="Element2" data-parsley-validate="">
                            <div ng-show="EditSelectedPolicy  || savedpolicies.PolicyDetail[0].IsExpired == 'true'">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Type of Professional Indemnity<span class="astric">*</span></h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select class="form-control" ng-required="EditSelectedPolicy" ng-model="drpProfessionalIndemnityType" ng-options="item.ID as item.Name for item in filteredProfessionalIndemnityType" ng-change="filterEmpStatus()">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" ng-hide="(filteredProfessionalIndemnityType.length > 0 && drpProfessionalIndemnityType == '') || filteredEmpStatus.length == 0">
                                    <div class="col-md-12">
                                        <h3>Employment Status<span class="astric">*</span></h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select class="form-control" ng-model="drpEmpStatus" ng-options="item.ID as item.Name for item in filteredEmpStatus" ng-required="filteredEmpStatus.length > 0 && EditSelectedPolicy" ng-change="filterSpecialty()">
                                                <option value="" selected="selected">Select Employment Status</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" ng-hide="(filteredProfessionalIndemnityType.length > 0 && drpProfessionalIndemnityType == '') || (filteredEmpStatus.length > 0 && drpEmpStatus == '') || filteredSpecialty.length == 0">
                                    <div class="col-md-12">
                                        <h3>Specialty<span class="astric">*</span></h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select class="form-control" ng-model="drpSpecialty" ng-options="item.ID as item.Name for item in filteredSpecialty" ng-required="filteredSpecialty.length > 0" ng-change="filterTypeOfCover()">
                                                <option value="" selected="selected">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" ng-hide="(filteredProfessionalIndemnityType.length > 0 && drpProfessionalIndemnityType == '') || (filteredEmpStatus.length > 0 && drpEmpStatus == '') || (filteredSpecialty.length > 0 && drpSpecialty == '')  || filteredTypeOfCover.length == 0 || filteredTypeOfCover == false">
                                    <div class="col-md-12">
                                        <h3>Type of cover<span class="astric">*</span></h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select class="form-control" ng-model="drpTypeOfCover" ng-options="item.ID as item.Name for item in filteredTypeOfCover" ng-required="filteredTypeOfCover.length > 0" ng-change="filterPracticeArea()">
                                                <option value="" selected="selected">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" ng-hide="(filteredProfessionalIndemnityType.length > 0 && drpProfessionalIndemnityType == '') || (filteredEmpStatus.length > 0 && drpEmpStatus == '') || (filteredSpecialty.length > 0 && drpSpecialty == '')  || (filteredTypeOfCover.length > 0 && drpTypeOfCover == '') || filteredPracticeArea.length == 0">
                                    <div class="col-md-12">
                                        <h3>
                                            <span class="astric">*</span>
                                        </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select class="form-control" ng-model="drpPracticeArea" ng-options="item.ID as item.Name for item in filteredPracticeArea" ng-required="filteredPracticeArea.length > 0" ng-change="filterPolicy()">
                                                <option value="" selected="selected">Select Specialty</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" ng-hide="(filteredProfessionalIndemnityType.length > 0 && drpProfessionalIndemnityType == '') || (filteredEmpStatus.length > 0 && drpEmpStatus == '') || (filteredSpecialty.length > 0 && drpSpecialty == '')  || (filteredTypeOfCover.length > 0 && drpTypeOfCover == '')  || (filteredPracticeArea.length > 0 && drpPracticeArea == '') || filteredPolicy.length == 0 ">
                                    <div class="col-md-12">

                                        <h3><span class="astric">*</span></h3>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select class="form-control" ng-show="drpPracticeArea == 'Dental Specialist practicing Oral and Maxillofacial Surgery'" ng-model="drpCoveredArea" ng-options="item as item.CoveredAreaText for item in filteredPolicy" ng-required="filteredPolicy.length > 0 && drpPracticeArea == 'Dental Specialist practicing Oral and Maxillofacial Surgery'" ng-change="SetPolicy(drpCoveredArea)">
                                                <option value="" selected="selected">Select</option>
                                            </select>
                                            <select class="form-control" ng-hide="drpPracticeArea == 'Dental Specialist practicing Oral and Maxillofacial Surgery'" ng-model="drpCoveredArea" ng-options="item as item.CoveredArea for item in filteredPolicy" ng-required="filteredPolicy.length > 0 && EditSelectedPolicy" ng-change="SetPolicy(drpCoveredArea)">
                                                <option value="" selected="selected">Select</option>
                                            </select>
                                            <input class="form-control" type="text" ng-show="drpPracticeArea == 'Dental Specialist practicing Oral and Maxillofacial Surgery' && SelectedPolicy.CoveredArea != ''" value="" disabled />
                                        </div>
                                    </div>
                                </div>
                                <div class="row" ng-show="SelectedPolicy.CoveredArea =='General Practice'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <ol>
                                            <li>Complete dental examinations and diagnosis of disease including x-rays</li>
                                            <li>Preventive dentistry (e.g cleanings, oral hygiene instruction, fluoride treatments, fissure sealants, scaling)</li>
                                            <li>Extractions, fillings, crowns, veneers, bridges, dentures</li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="row" ng-show="SelectedPolicy.CoveredArea =='General Practice with Specialized Procedures'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <ol>
                                            <li>Braces        </li>
                                            <li>Periodontics  </li>
                                            <li>Endodontics   </li>
                                            <li>Implants      </li>
                                            <li>Orthodontics  </li>
                                            <li>Oral Surgeries</li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="row" ng_show="SelectedPolicy.CoveredArea =='Core Services'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <ol>
                                            <li>History taking, Examination, and Diagnosis.                                                        </li>
                                            <li>Prescription, Injections IM & IV, and IV Drips in Emergencies.                                     </li>
                                            <li>Immunizations, I&D, T&S under Local Anesthesia. Simple Removal of Foreign Bodies from ENT & Eye    </li>
                                            <li>Neubelisation.                                                                                     </li>
                                            <li>CPR.                                                                                               </li>
                                            <li>Medical Examinations / Screenings.                                                                 </li>
                                            <li>Urine and Blood Testings / Analysis.                                                               </li>
                                            <li>Plain Xrays.                                                                                       </li>
                                            <li>Ante-Natal Screenings up to 24 weeks. Pap Smears.                                                  </li>
                                            <li>Family Planning Advice and Prescriptions.                                                          </li>
                                            <li>Other similar services traditionally carried out by General Practitioners                          </li>
                                        </ol>
                                    </div>
                                </div>


                                <div class="row" ng_show="SelectedPolicy.CoveredArea =='Core Services with procedures'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <ol>
                                            <li>Removal of Ingrowing Toe Nails.                                               </li>
                                            <li>Excisions of Lumps & Bumps (Non-Facial Warts, Cysts, Lipomas, Granulomas)     </li>
                                            <li>Insertions and Removals of IUCDs                                              </li>
                                            <li>Cortisone Injections. ( Tendonitis, Teno-synovitis, Plantar Fascitis )        </li>
                                            <li>Immobilizations of Undisplaced Fractures of Metacarpal and Phalangeal Joints. </li>
                                            <li>Circumcision                                                                  </li>
                                            <li>Other similar procedures traditionally carried out by General Practitioners   </li>
                                        </ol>
                                    </div>
                                </div>


                                <div class="row" ng_show="SelectedPolicy.CoveredArea =='General Practitioner with Obstetrics'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <p>
                                            36 weeks (full term of pregnancy) exclude deliveries
                                        </p>
                                    </div>
                                </div>


                                <div class="row" ng_show="SelectedPolicy.CoveredArea =='Cosmetic & Aesthetic – Non - Invasive Elective Topical Enhancement'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <p>
                                            Non-invasive procedures: External applications or treatment procedures that are carried out without creating a break in the skin or penetration of the integument. They target the epidermis only.
                                        </p>
                                        <ul>
                                            <li>Superficial chemical peels</li>
                                            <li>Microdermabrasion         </li>
                                            <li>Intense pulsed light      </li>
                                        </ul>
                                    </div>
                                </div>


                                <div class="row" ng_show="SelectedPolicy.CoveredArea =='Cosmetic & Aesthetic – Non - Surgical Invasive Elective Topical Enhancement' && drpCoveredArea.MedicalStatus =='General Practitoner'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <p>Minimally invasive procedures: Treatment procedures that induce minimal damage to the tissues at the point of entry of instruments. These procedures involve penetration or transgression of integument but are limited to the sub-dermis and subcutaneous fat; not extending beyond the superficial musculo- aponeurotic layer of the face and neck, or beyond the superficial fascial layer of the torso and limbs.</p>
                                        <strong>They are limited to the following procedures:</strong>
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
                                    </div>
                                </div>


                                <div class="row" ng_show="SelectedPolicy.CoveredArea =='Office / Clinical Orthopaedics'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Jobscope:</strong>
                                        <ul>
                                            <li>All excision biopsy of lumps under local                        </li>
                                            <li>All nail/ nail bed procedures                                   </li>
                                            <li>administration of local anesthesia                              </li>
                                            <li>Arthrocentesis and joint or soft tissue injections              </li>
                                            <li>Application of splints or casts                                 </li>
                                            <li>Simple amputation under local                                   </li>
                                            <li>Tendon/ nerve entrapment release                                </li>
                                            <li>Incision and drainage of soft tissue infection                  </li>
                                            <li>Closed reduction and immobilization of fracture and dislocation </li>
                                            <li>Debridement of soft tissue and closer of wound                  </li>
                                            <li>Removal of foreign bodies under local                           </li>
                                            <li>Repair of muscle/ tendon under local                            </li>
                                            <li>Use of fluoroscopy (sedation or local anesthesia procedures)    </li>
                                            <li>Tissue flap under local                                         </li>
                                            <li>Manipulation of joint under sedation/local anesthesia           </li>
                                        </ul>
                                    </div>
                                </div>


                                <div class="row" ng_show="SelectedPolicy.CoveredArea =='Ophthalmology Surgeries (Non G.A.)'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <p>Cataract etc under L.A. (Non G.A.)</p>
                                    </div>
                                </div>


                                <div class="row" ng_show="SelectedPolicy.CoveredArea =='Cosmetic and Aesthetic ( Surgical, Invasive)'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <p>Surgical includes excisions of warts, mole, scars and other External Cosmotic Surgery under L.A.</p>
                                    </div>
                                </div>

                                <div class="row" ng_show="SelectedPolicy.CoveredArea =='General Dental Practice'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <!--<ol>
                                            <li>
                                                <strong>million limit of indemnity covers following services:</strong>
                                                <ol>
                                                    <li>Complete dental examinations and diagnosis of disease including x-rays</li>
                                                    <li>Preventive dentistry (e.g cleanings, oral hygiene instruction, fluoride treatments, fissure sealants, scaling)</li>
                                                    <li>Extractions, fillings, crowns, veneers, bridges, dentures</li>
                                                </ol>
                                            </li>
                                            <li>
                                                <strong>million limit of indemnity additionally covers following services:</strong>
                                                <ol>
                                                    <li>
                                                        Minor oral surgeries (e.g. laserations, gum injuries, broken tooths, simple root canal treatment and wisdom tooth extractions)<br />
                                                        <strong style="font-weight:bold">*Please choose 2 million cover if you do minor oral surgeries.</strong>
                                                    </li>
                                                </ol>
                                            </li>
                                        </ol>-->
                                        <ol>
                                            <li>Complete dental examinations and diagnosis of disease including x-rays                                                           </li>
                                            <li>Preventive dentistry (e.g cleanings, oral hygiene instruction, fluoride treatments, fissure sealants, scaling)                   </li>
                                            <li>Extractions, fillings, crowns, veneers, bridges, dentures                                                                        </li>
                                            <li>
                                                Minor oral surgeries (e.g. laserations, gum injuries, broken tooths, simple root canal treatment and wisdom tooth extractions)<br />
                                                <strong style="font-weight:bold">*Please choose 2 million cover if you do minor oral surgeries.</strong>
                                            </li>
                                        </ol>

                                    </div>
                                </div>


                                <div class="row" ng_show="SelectedPolicy.CoveredArea =='General Dental Practitioners, practising accredited specialised procedures'">
                                    <div class="col-md-12">
                                        <h3>Definition / Information </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Accredited specialized procedures includes:</strong>
                                        <ol>
                                            <li>Braces        </li>
                                            <li>Periodontics  </li>
                                            <li>Endodontics   </li>
                                            <li>Implants      </li>
                                            <li>Orthodontics  </li>
                                            <li>Oral Surgeries</li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="clearfix mb-5"></div>
                                <button type="submit" ng-click="next()" class="btn btn-primary pull-right">Next Step</button>
                                <button type="submit" ng-click="prev()" class="btn btn-default pull-right mr-3">Previous Step</button>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
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

    // Handle form submission
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
        
        // Show success message
        alert('Application details saved successfully!');
        
        // Here you would normally submit the form data to the server
        // For now, just simulate moving to next step
        setTimeout(() => {
            // window.location.href = '/next-step'; // Replace with actual next step URL
        }, 1500);
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
});
</script>
<script>
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
        const coverageDetailsSection = document.getElementById('coverageDetailsSection');
        const additionalServicesSection = document.getElementById('additionalServicesSection');
        const premiumCalculationSection = document.getElementById('premiumCalculationSection');
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
                // If lecturer_trainee, skip Type of Cover and Practice Area, go directly to Coverage Details
                if (selectedValue === 'lecturer_trainee') {
                    // Go directly to coverage details for lecturer/trainee
                    coverageDetailsSection.style.display = 'block';
                    document.getElementById('coverageLimit').required = true;
                    document.getElementById('policyPeriod').required = true;
                    document.getElementById('policyStartDate').required = true;
                    
                    additionalServicesSection.style.display = 'block';
                    premiumCalculationSection.style.display = 'block';
                    
                    // Type of Cover and Practice Area sections remain hidden for lecturer_trainee
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
                // Handle Medical Practice - Private General Practitioner
                if (professionalType === 'medical_practice' && employmentType === 'private' && specialtyType === 'general_practitioner') {
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
                    
                    coverageDetailsSection.style.display = 'block';
                    document.getElementById('coverageLimit').required = true;
                    document.getElementById('policyPeriod').required = true;
                    document.getElementById('policyStartDate').required = true;
                    
                    additionalServicesSection.style.display = 'block';
                    premiumCalculationSection.style.display = 'block';
                }
                // Handle Dental Practice
                else if (professionalType === 'dental_practice' && specialtyType === 'general_dentist') {
                    // Show service type selection for Dental General Practice
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    updateDentalServiceOptions(selectedValue);
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
            
            // Reset subsequent fields and hide subsequent sections - BUT KEEP SERVICE TYPE SECTION VISIBLE
            resetFieldsFromServiceType();
            hideAllSectionsFromServiceType();
            
            if (selectedValue) {
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
                
                // Go directly to coverage details
                coverageDetailsSection.style.display = 'block';
                document.getElementById('coverageLimit').required = true;
                document.getElementById('policyPeriod').required = true;
                document.getElementById('policyStartDate').required = true;
                
                additionalServicesSection.style.display = 'block';
                premiumCalculationSection.style.display = 'block';
            }
        });

        // Step 7: Practice Area Change (for other paths)
        practiceArea.addEventListener('change', function() {
            const selectedValue = this.value;
            
            // Reset subsequent fields and hide subsequent sections
            resetFieldsFromCoverageDetails();
            hideAllSectionsFromCoverageDetails();
            
            if (selectedValue) {
                coverageDetailsSection.style.display = 'block';
                document.getElementById('coverageLimit').required = true;
                document.getElementById('policyPeriod').required = true;
                document.getElementById('policyStartDate').required = true;
                
                additionalServicesSection.style.display = 'block';
                premiumCalculationSection.style.display = 'block';
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
            } else if (serviceType === 'general_practice') {
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
            // ... existing medical definitions ...
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
            document.getElementById('coverageLimit').value = '';
            document.getElementById('policyPeriod').value = '';
            document.getElementById('policyStartDate').value = '';
            document.getElementById('serviceSelection').value = '';
        }

        function resetFieldsFromSpecialty() {
            specialtyArea.value = '';
            coverType.value = '';
            serviceTypeSelection.value = '';
            locumPracticeLocation.value = '';
            practiceArea.value = '';
            document.getElementById('coverageLimit').value = '';
            document.getElementById('policyPeriod').value = '';
            document.getElementById('policyStartDate').value = '';
            document.getElementById('serviceSelection').value = '';
        }

        function resetFieldsFromCoverType() {
            coverType.value = '';
            serviceTypeSelection.value = '';
            locumPracticeLocation.value = '';
            practiceArea.value = '';
            document.getElementById('coverageLimit').value = '';
            document.getElementById('policyPeriod').value = '';
            document.getElementById('policyStartDate').value = '';
            document.getElementById('serviceSelection').value = '';
        }

        // NEW: Reset function for service type (doesn't reset service type itself)
        function resetFieldsFromServiceType() {
            practiceArea.value = '';
            document.getElementById('coverageLimit').value = '';
            document.getElementById('policyPeriod').value = '';
            document.getElementById('policyStartDate').value = '';
            document.getElementById('serviceSelection').value = '';
        }

        function resetFieldsFromPracticeArea() {
            practiceArea.value = '';
            document.getElementById('coverageLimit').value = '';
            document.getElementById('policyPeriod').value = '';
            document.getElementById('policyStartDate').value = '';
            document.getElementById('serviceSelection').value = '';
        }

        function resetFieldsFromCoverageDetails() {
            document.getElementById('coverageLimit').value = '';
            document.getElementById('policyPeriod').value = '';
            document.getElementById('policyStartDate').value = '';
            document.getElementById('serviceSelection').value = '';
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
            coverageDetailsSection.style.display = 'none';
            additionalServicesSection.style.display = 'none';
            premiumCalculationSection.style.display = 'none';
            
            // Remove required attributes
            employmentStatus.required = false;
            specialtyArea.required = false;
            coverType.required = false;
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
            document.getElementById('coverageLimit').required = false;
            document.getElementById('policyPeriod').required = false;
            document.getElementById('policyStartDate').required = false;
        }

        function hideAllSectionsFromEmployment() {
            specialtySection.style.display = 'none';
            coverTypeSection.style.display = 'none';
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            coverageDetailsSection.style.display = 'none';
            additionalServicesSection.style.display = 'none';
            premiumCalculationSection.style.display = 'none';
            
            specialtyArea.required = false;
            coverType.required = false;
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
            document.getElementById('coverageLimit').required = false;
            document.getElementById('policyPeriod').required = false;
            document.getElementById('policyStartDate').required = false;
        }

        function hideAllSectionsFromSpecialty() {
            coverTypeSection.style.display = 'none';
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            coverageDetailsSection.style.display = 'none';
            additionalServicesSection.style.display = 'none';
            premiumCalculationSection.style.display = 'none';
            
            coverType.required = false;
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
            document.getElementById('coverageLimit').required = false;
            document.getElementById('policyPeriod').required = false;
            document.getElementById('policyStartDate').required = false;
        }

        function hideAllSectionsFromCoverType() {
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            coverageDetailsSection.style.display = 'none';
            additionalServicesSection.style.display = 'none';
            premiumCalculationSection.style.display = 'none';
            
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
            document.getElementById('coverageLimit').required = false;
            document.getElementById('policyPeriod').required = false;
            document.getElementById('policyStartDate').required = false;
        }

        // NEW: Hide function for service type (doesn't hide service type section itself)
        function hideAllSectionsFromServiceType() {
            serviceDefinitionSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            coverageDetailsSection.style.display = 'none';
            additionalServicesSection.style.display = 'none';
            premiumCalculationSection.style.display = 'none';
            
            practiceArea.required = false;
            document.getElementById('coverageLimit').required = false;
            document.getElementById('policyPeriod').required = false;
            document.getElementById('policyStartDate').required = false;
        }

        function hideAllSectionsFromPracticeArea() {
            serviceDefinitionSection.style.display = 'none';
            coverageDetailsSection.style.display = 'none';
            additionalServicesSection.style.display = 'none';
            premiumCalculationSection.style.display = 'none';
            
            document.getElementById('coverageLimit').required = false;
            document.getElementById('policyPeriod').required = false;
            document.getElementById('policyStartDate').required = false;
        }

        function hideAllSectionsFromCoverageDetails() {
            additionalServicesSection.style.display = 'none';
            premiumCalculationSection.style.display = 'none';
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
            if (professionalType === 'dental_practice') {
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
                if (employmentType === 'government') {
                    if (specialtyType === 'general_dentist') {
                        coverTypeSelect.innerHTML += `
                            <option value="locum_cover_only">Locum cover only</option>
                            <option value="general_cover">General Cover</option>
                        `;
                    } else if (specialtyType === 'dentist_specialist') {
                        coverTypeSelect.innerHTML += `
                            <option value="basic_coverage">Basic Coverage</option>
                            <option value="comprehensive_coverage">Comprehensive Coverage</option>
                            <option value="premium_coverage">Premium Coverage</option>
                        `;
                    }
                } else if (employmentType === 'private') {
                    if (specialtyType === 'general_dentist') {
                        coverTypeSelect.innerHTML += `
                            <option value="locum_cover_only">Locum cover only</option>
                            <option value="general_cover">General Cover</option>
                        `;
                    } else if (specialtyType === 'dentist_specialist') {
                        coverTypeSelect.innerHTML += `
                            <option value="basic_coverage">Basic Coverage</option>
                            <option value="comprehensive_coverage">Comprehensive Coverage</option>
                            <option value="premium_coverage">Premium Coverage</option>
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
    });
</script>
@endsection
