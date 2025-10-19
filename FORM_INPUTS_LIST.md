# Complete List of All Form Inputs - Steps 1 to 8

## STEP 1: Details of the Applicant

### Personal Information
1. `title` - Select dropdown (DR, MR, MS, PROF, DATO, DATIN, DATUK, DATUK SERI, TAN SRI)
2. `full_name` - Text input
3. `nationality_status` - Select dropdown (Malaysian, Non-Malaysian)
4. `nric_number` - Text input (format: XXXXXX-XX-XXXX)
5. `passport_number` - Text input

### Contact Information
6. `gender` - Select dropdown (Male, Female)
7. `contact_no` - Tel input
8. `email_address` - Email input
9. `password` - Password input
10. `confirm_password` - Password input

### Mailing Address
11. `mailing_address` - Text input (Address)
12. `mailing_postcode` - Text input
13. `mailing_city` - Text input
14. `mailing_state` - Text input
15. `mailing_country` - Text input

### Primary Practicing Address
16. `primary_clinic_type` - Radio button (government, private)
17. `primary_clinic_name` - Text input
18. `primary_address` - Text input
19. `primary_postcode` - Text input
20. `primary_city` - Text input
21. `primary_state` - Text input
22. `primary_country` - Text input

### Secondary Practicing Address
23. `secondary_clinic_type` - Radio button (government, private)
24. `secondary_clinic_name` - Text input
25. `secondary_address` - Text input
26. `secondary_postcode` - Text input
27. `secondary_city` - Text input
28. `secondary_state` - Text input
29. `secondary_country` - Text input

### Qualifications (3 sets)
30. `institution_1` - Text input
31. `qualification_1` - Text input
32. `year_obtained_1` - Number input
33. `institution_2` - Text input
34. `qualification_2` - Text input
35. `year_obtained_2` - Number input
36. `institution_3` - Text input
37. `qualification_3` - Text input
38. `year_obtained_3` - Number input

### Registration Details
39. `registration_council` - Select dropdown (MMC, MDC, Others)
40. `other_council` - Text input (conditional, shown if "Others" selected)
41. `registration_number` - Text input

---

## STEP 2: Details of Healthcare Services Business

1. `professional_indemnity_type` - Select dropdown (Medical Practice, Dental Practice, Pharmacist)
2. `employment_status` - Select dropdown (Government, Private, Self-Employed, Non-Practicing)
3. `specialty_area` - Select dropdown (General Practice, Medical Officer, Specialist, Dental, Dental Specialist)
4. `cover_type` - Select dropdown (Basic Coverage, Comprehensive Coverage, Premium Coverage)
5. `locum_practice_location` - Select dropdown (Private Clinic, Private Hospital)
6. `service_type` - Select dropdown (Core Services, Core Services with procedures, General Practitioner options, Cosmetic & Aesthetic options)
7. `practice_area` - Select dropdown (General Practice, General Practice with Specialized Procedures, Core Services, etc.)

---

## STEP 3: Pricing Details

### Display Information (Read-only)
- displayCoverType - Display only
- displayMedicalStatus - Display only
- displayClass - Display only

### Policy Details
1. `policy_start_date` - Date input
2. `policy_expiry_date` - Date input (read-only, auto-calculated)
3. `liability_limit` - Select dropdown (RM 1M, RM 2M, RM 5M, RM 10M)

### Pricing Breakdown (Display only, calculated)
- displayLiabilityLimit - Display only
- displayBasePremium - Display only
- displayGrossPremium - Display only
- displayLocumAddon - Display only (conditional)
- displaySST - Display only
- displayStampDuty - Display only
- displayTotalPayable - Display only

---

## STEP 4: Risk Management

1. `medical_records` - Radio button (yes, no)
   - Question: "Do you maintain accurate records of medical services rendered?"

2. `informed_consent` - Radio button (yes, no)
   - Question: "Is consent/informed consent obtained and recorded?"

3. `adverse_incidents` - Radio button (yes, no)
   - Question: "Do you have procedures for reporting adverse incidents and events?"

4. `sterilisation_facilities` - Radio button (yes, no)
   - Question: "Do you have facilities for sterilisation of instruments?"

---

## STEP 5: Insurance History

### Current Insurance
1. `current_insurance` - Radio button (yes, no)
   - Question: "Do you currently hold medical malpractice insurance?"

#### Conditional Details (shown if current_insurance = "yes")
2. `insurer_name` - Text input
3. `period_of_insurance` - Text input
4. `policy_limit_myr` - Text input
5. `excess_myr` - Text input
6. `retroactive_date` - Text input

### Previous Claims
7. `previous_claims` - Radio button (yes, no)
   - Question: "Have you ever had any application for medical malpractice insurance refused, or had coverage rescinded/cancelled?"

#### Conditional Details (shown if previous_claims = "yes")
8. `claims_details` - Textarea

---

## STEP 6: Claims Experience

### Questions
1. `claims_made` - Radio button (yes, no)
   - Question: "Have any claims ever been made, or lawsuits been brought against you?"

2. `aware_of_errors` - Radio button (yes, no)
   - Question: "Are you aware of any errors, omissions, offences, circumstances or allegations?"

3. `disciplinary_action` - Radio button (yes, no)
   - Question: "Have you ever been the subject of disciplinary action or investigation?"

### Conditional Details (shown if ANY above = "yes")
4. `claim_date_of_claim` - Text input
5. `claim_notified_date` - Text input
6. `claim_claimant_name` - Text input
7. `claim_allegations` - Text input
8. `claim_amount_claimed` - Text input
9. `claim_status` - Text input
10. `claim_amounts_paid` - Text input

---

## STEP 7: Data Protection Notice & Declaration

### Declaration Section
1. `agree_declaration` - Checkbox (yes)
   - Label: "I have read and agreed the above declaration"

---

## STEP 8: Declaration & Signature

### Declaration Agreement
1. `agree_declaration_final` - Checkbox (yes)
   - Label: "I have read and agreed the above declaration"

### Signature
2. `signature` - Canvas element (signatureCanvas)
   - Data stored as Base64 PNG image

---

## SUMMARY STATISTICS

- **Step 1**: 41 inputs
- **Step 2**: 7 inputs
- **Step 3**: 3 inputs (+ 7 display-only fields)
- **Step 4**: 4 inputs (all radio buttons)
- **Step 5**: 8 inputs (2 main + 6 conditional)
- **Step 6**: 10 inputs (3 main + 7 conditional)
- **Step 7**: 1 input (checkbox)
- **Step 8**: 2 inputs (1 checkbox + 1 canvas signature)

**Total Mandatory Inputs**: 76 fields
**Total Display/Calculated Fields**: 7 fields in Step 3

---

## CONDITIONAL FIELDS SUMMARY

| Step | Condition | Fields |
|------|-----------|--------|
| 1 | If nationality_status = "non_malaysian" | Show passport_number field |
| 1 | If nationality_status = "malaysian" | Show nric_number field |
| 1 | If registration_council = "others" | Show other_council field |
| 2 | If professional_indemnity_type changes | Show/hide employment_status, specialty_area, cover_type, etc. |
| 3 | If liability_limit is selected | Show/calculate pricing_breakdown section |
| 5 | If current_insurance = "yes" | Show insurer_name, period_of_insurance, policy_limit_myr, excess_myr, retroactive_date |
| 5 | If previous_claims = "yes" | Show claims_details textarea |
| 6 | If ANY of (claims_made OR aware_of_errors OR disciplinary_action) = "yes" | Show all 7 claim detail fields |

---

## FORM STRUCTURE

- **Step 1 Form**: `#policyApplicationForm`
- **Step 2 Form**: `#healthcareServicesForm`
- **Step 3 Form**: `#pricingDetailsForm`
- **Step 4 Form**: `#declarationForm`
- **Step 5 Form**: `#insuranceHistoryForm`
- **Step 6 Form**: `#claimsExperienceForm`
- **Step 7 Form**: `#dataProtectionForm`
- **Step 8 Form**: `#declarationSignatureForm`
