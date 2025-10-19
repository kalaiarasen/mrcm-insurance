# Database Schema Coverage Review - COMPLETE AUDIT

## ✅ STEP 1: Details of the Applicant (41 fields)

### Personal Information (5 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| title | ✅ | applicant_profiles | title | Covered |
| full_name | ✅ | users | name | Covered |
| nationality_status | ✅ | applicant_profiles | nationality_status | Covered |
| nric_number | ✅ | applicant_profiles | nric_number | Covered, unique |
| passport_number | ✅ | applicant_profiles | passport_number | Covered, unique |

### Contact Information (5 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| gender | ✅ | applicant_profiles | gender | Covered |
| contact_no | ✅ | users | contact_no | Covered |
| email_address | ✅ | users | email | Covered |
| password | ✅ | users | password | Covered (hashed) |
| confirm_password | ⚠️ | N/A | N/A | **NOT STORED** - Validation only |

### Mailing Address (5 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| mailing_address | ✅ | addresses | address | type='mailing' |
| mailing_postcode | ✅ | addresses | postcode | type='mailing' |
| mailing_city | ✅ | addresses | city | type='mailing' |
| mailing_state | ✅ | addresses | state | type='mailing' |
| mailing_country | ✅ | addresses | country | type='mailing' |

### Primary Practicing Address (7 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| primary_clinic_type | ✅ | addresses | clinic_type | type='primary_clinic' |
| primary_clinic_name | ✅ | addresses | clinic_name | type='primary_clinic' |
| primary_address | ✅ | addresses | address | type='primary_clinic' |
| primary_postcode | ✅ | addresses | postcode | type='primary_clinic' |
| primary_city | ✅ | addresses | city | type='primary_clinic' |
| primary_state | ✅ | addresses | state | type='primary_clinic' |
| primary_country | ✅ | addresses | country | type='primary_clinic' |

### Secondary Practicing Address (7 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| secondary_clinic_type | ✅ | addresses | clinic_type | type='secondary_clinic' |
| secondary_clinic_name | ✅ | addresses | clinic_name | type='secondary_clinic' |
| secondary_address | ✅ | addresses | address | type='secondary_clinic' |
| secondary_postcode | ✅ | addresses | postcode | type='secondary_clinic' |
| secondary_city | ✅ | addresses | city | type='secondary_clinic' |
| secondary_state | ✅ | addresses | state | type='secondary_clinic' |
| secondary_country | ✅ | addresses | country | type='secondary_clinic' |

### Qualifications (9 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| institution_1 | ✅ | qualifications | institution | sequence=1 |
| qualification_1 | ✅ | qualifications | degree_or_qualification | sequence=1 |
| year_obtained_1 | ✅ | qualifications | year_obtained | sequence=1 |
| institution_2 | ✅ | qualifications | institution | sequence=2 |
| qualification_2 | ✅ | qualifications | degree_or_qualification | sequence=2 |
| year_obtained_2 | ✅ | qualifications | year_obtained | sequence=2 |
| institution_3 | ✅ | qualifications | institution | sequence=3 |
| qualification_3 | ✅ | qualifications | degree_or_qualification | sequence=3 |
| year_obtained_3 | ✅ | qualifications | year_obtained | sequence=3 |

### Registration Details (3 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| registration_council | ✅ | applicant_profiles | registration_council | Covered |
| other_council | ✅ | applicant_profiles | other_council | Covered, nullable |
| registration_number | ✅ | applicant_profiles | registration_number | Covered, unique |

**STEP 1 SUMMARY: 40/41 ✅ (confirm_password is validation-only, not stored)**

---

## ✅ STEP 2: Healthcare Services Business (7 fields)

| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| professional_indemnity_type | ✅ | healthcare_services | professional_indemnity_type | Covered |
| employment_status | ✅ | healthcare_services | employment_status | Covered |
| specialty_area | ✅ | healthcare_services | specialty_area | Covered |
| cover_type | ✅ | healthcare_services | cover_type | Covered |
| locum_practice_location | ✅ | healthcare_services | locum_practice_location | Covered |
| service_type | ✅ | healthcare_services | service_type | Covered |
| practice_area | ✅ | healthcare_services | practice_area | Covered |

**STEP 2 SUMMARY: 7/7 ✅**

---

## ✅ STEP 3: Pricing Details (3 input fields + 7 calculated)

### Inputs (3 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| policy_start_date | ✅ | policy_pricings | policy_start_date | Covered |
| policy_expiry_date | ✅ | policy_pricings | policy_expiry_date | Covered |
| liability_limit | ✅ | policy_pricings | liability_limit | Covered |

### Calculated (7 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| displayBasePremium | ✅ | policy_pricings | base_premium | Calculated |
| displayGrossPremium | ✅ | policy_pricings | gross_premium | Calculated |
| displayLocumAddon | ✅ | policy_pricings | locum_addon | Calculated |
| displaySST | ✅ | policy_pricings | sst | Calculated (8%) |
| displayStampDuty | ✅ | policy_pricings | stamp_duty | Calculated (fixed 10) |
| displayTotalPayable | ✅ | policy_pricings | total_payable | Calculated |
| displayLiabilityLimit | ✅ | policy_pricings | liability_limit | Display |

**STEP 3 SUMMARY: 10/10 ✅**

---

## ✅ STEP 4: Risk Management (4 fields)

| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| medical_records | ✅ | risk_managements | medical_records | yes/no |
| informed_consent | ✅ | risk_managements | informed_consent | yes/no |
| adverse_incidents | ✅ | risk_managements | adverse_incidents | yes/no |
| sterilisation_facilities | ✅ | risk_managements | sterilisation_facilities | yes/no |

**STEP 4 SUMMARY: 4/4 ✅**

---

## ✅ STEP 5: Insurance History (8 fields)

### Current Insurance (6 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| current_insurance | ✅ | insurance_histories | current_insurance | yes/no |
| insurer_name | ✅ | insurance_histories | insurer_name | Covered |
| period_of_insurance | ✅ | insurance_histories | period_of_insurance | Covered |
| policy_limit_myr | ✅ | insurance_histories | policy_limit_myr | decimal(15,2) |
| excess_myr | ✅ | insurance_histories | excess_myr | decimal(15,2) |
| retroactive_date | ✅ | insurance_histories | retroactive_date | Covered |

### Previous Claims (2 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| previous_claims | ✅ | insurance_histories | previous_claims | yes/no |
| claims_details | ✅ | insurance_histories | claims_details | longText |

**STEP 5 SUMMARY: 8/8 ✅**

---

## ✅ STEP 6: Claims Experience (10 fields)

### Questions (3 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| claims_made | ✅ | claims_experiences | claims_made | yes/no |
| aware_of_errors | ✅ | claims_experiences | aware_of_errors | yes/no |
| disciplinary_action | ✅ | claims_experiences | disciplinary_action | yes/no |

### Claim Details (7 fields)
| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| claim_date_of_claim | ✅ | claims_experiences | claim_date_of_claim | date |
| claim_notified_date | ✅ | claims_experiences | claim_notified_date | date |
| claim_claimant_name | ✅ | claims_experiences | claim_claimant_name | string |
| claim_allegations | ✅ | claims_experiences | claim_allegations | text |
| claim_amount_claimed | ✅ | claims_experiences | claim_amount_claimed | decimal(15,2) |
| claim_status | ✅ | claims_experiences | claim_status | string |
| claim_amounts_paid | ✅ | claims_experiences | claim_amounts_paid | decimal(15,2) |

**STEP 6 SUMMARY: 10/10 ✅**

---

## ✅ STEP 7: Data Protection Notice (1 field)

| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| agree_declaration | ✅ | policy_applications | agree_data_protection | boolean |

**STEP 7 SUMMARY: 1/1 ✅**

---

## ✅ STEP 8: Declaration & Signature (2 fields)

| Field | Status | Table | Column | Notes |
|-------|--------|-------|--------|-------|
| agree_declaration_final | ✅ | policy_applications | agree_declaration | boolean |
| signature | ✅ | policy_applications | signature_data | longText (Base64) |

**STEP 8 SUMMARY: 2/2 ✅**

---

## OVERALL COVERAGE SUMMARY

| Step | Fields | Covered | Status |
|------|--------|---------|--------|
| Step 1 | 41 | 40 | ✅ 97.5% |
| Step 2 | 7 | 7 | ✅ 100% |
| Step 3 | 10 | 10 | ✅ 100% |
| Step 4 | 4 | 4 | ✅ 100% |
| Step 5 | 8 | 8 | ✅ 100% |
| Step 6 | 10 | 10 | ✅ 100% |
| Step 7 | 1 | 1 | ✅ 100% |
| Step 8 | 2 | 2 | ✅ 100% |
| **TOTAL** | **83** | **82** | **✅ 98.8%** |

---

## ADDITIONAL FIELDS ADDED (Not in form but important for system)

### Users Table
- `application_status` - Track submission status
- `application_submitted_at` - Track when submitted

### Policy Applications Table
- `status` - Application workflow status
- `submitted_at` - Timestamp
- `approved_at` - Timestamp

---

## ⚠️ NOTES

1. **confirm_password**: Not stored in database - only used for validation (standard practice)
2. **Display-only fields** (Step 3): Not stored separately - calculated from user selections
3. **Roles**: Using Spatie Permission package (not a database column)
4. **Signature**: Stored as Base64 PNG in longText column
5. **Timestamps**: All tables have created_at and updated_at for audit trail

---

## ✅ CONCLUSION

**All 82 mandatory database fields are properly covered across normalized tables!**

The database schema is complete and ready for implementation.

### Next Steps:
1. Run migrations: `php artisan migrate`
2. Create Eloquent Models
3. Create Form Submission Service
4. Create API Endpoints
5. Create Admin Dashboard for viewing submissions
