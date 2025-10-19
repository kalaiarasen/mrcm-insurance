# Insurance Policy Application Database Schema

## Overview
Complete database structure for storing 8-step insurance policy application data with proper normalization and best practices.

---

## Database Tables & Relationships

### 1. **users** (Extended with application status)
Base Laravel users table with additional fields:
```
- id (PK)
- name
- email (unique)
- password (hashed)
- application_status: 'draft' | 'submitted' | 'approved' | 'rejected'
- application_submitted_at: timestamp
- timestamps
```

**Role Assignment via Spatie Permission:**
- `roles`: Admin, Agent, Client (assigned via Spatie)

---

### 2. **applicant_profiles** (Step 1)
Stores personal profile information tied to user.

```
- id (PK)
- user_id (FK → users)
- title: DR, MR, MS, PROF, etc
- nationality_status: 'malaysian' | 'non_malaysian'
- nric_number (unique)
- passport_number (unique)
- gender: 'male' | 'female'
- registration_council
- other_council
- registration_number (unique)
- timestamps
```

**Relationships:**
- `BelongsTo` User
- `HasMany` Addresses

---

### 3. **addresses** (Step 1)
Stores multiple address types for applicant.

```
- id (PK)
- user_id (FK → users)
- type: 'mailing' | 'primary_clinic' | 'secondary_clinic'
- address
- postcode
- city
- state
- country
- clinic_type: 'government' | 'private' (optional)
- clinic_name (optional)
- timestamps
```

**Relationships:**
- `BelongsTo` User
- **Polymorphic relationship** (can extend to other models)

---

### 4. **qualifications** (Step 1)
Stores up to 3 qualifications per applicant.

```
- id (PK)
- user_id (FK → users)
- sequence: 1 | 2 | 3
- institution
- degree_or_qualification
- year_obtained
- timestamps
```

**Relationships:**
- `BelongsTo` User

---

### 5. **healthcare_services** (Step 2)
Stores healthcare practice details.

```
- id (PK)
- user_id (FK → users)
- professional_indemnity_type: 'medical_practice' | 'dental_practice' | 'pharmacist'
- employment_status: 'government' | 'private' | 'self_employed' | 'non_practicing'
- specialty_area: varies by profession
- cover_type: varies by selections
- locum_practice_location: 'private_clinic' | 'private_hospital'
- service_type: varies by category
- practice_area: varies by selection
- timestamps
```

**Relationships:**
- `BelongsTo` User
- `HasOne` PolicyPricing

---

### 6. **policy_pricings** (Step 3)
Stores pricing calculations and policy details.

```
- id (PK)
- user_id (FK → users)
- policy_start_date
- policy_expiry_date
- liability_limit: decimal(15,2) - RM amount
- base_premium: decimal(10,2)
- gross_premium: decimal(10,2)
- locum_addon: decimal(10,2) - default 0
- sst: decimal(10,2) - 8% tax
- stamp_duty: decimal(10,2) - default 10
- total_payable: decimal(10,2)
- timestamps
```

**Relationships:**
- `BelongsTo` User
- `BelongsTo` HealthcareService

---

### 7. **risk_managements** (Step 4)
Stores risk management questionnaire responses.

```
- id (PK)
- user_id (FK → users)
- medical_records: 'yes' | 'no'
- informed_consent: 'yes' | 'no'
- adverse_incidents: 'yes' | 'no'
- sterilisation_facilities: 'yes' | 'no'
- timestamps
```

**Relationships:**
- `BelongsTo` User

---

### 8. **insurance_histories** (Step 5)
Stores previous and current insurance information.

```
- id (PK)
- user_id (FK → users)
- current_insurance: 'yes' | 'no'
- insurer_name
- period_of_insurance
- policy_limit_myr: decimal(15,2)
- excess_myr: decimal(15,2)
- retroactive_date
- previous_claims: 'yes' | 'no'
- claims_details: longText
- timestamps
```

**Relationships:**
- `BelongsTo` User

---

### 9. **claims_experiences** (Step 6)
Stores claims experience details.

```
- id (PK)
- user_id (FK → users)
- claims_made: 'yes' | 'no'
- aware_of_errors: 'yes' | 'no'
- disciplinary_action: 'yes' | 'no'
- claim_date_of_claim: date
- claim_notified_date: date
- claim_claimant_name
- claim_allegations: text
- claim_amount_claimed: decimal(15,2)
- claim_status: 'outstanding' | 'finalised'
- claim_amounts_paid: decimal(15,2)
- timestamps
```

**Relationships:**
- `BelongsTo` User

---

### 10. **policy_applications** (Step 7 & 8)
Stores final application submission and signature.

```
- id (PK)
- user_id (FK → users)
- agree_data_protection: boolean
- agree_declaration: boolean
- signature_data: longText (Base64 PNG)
- status: 'draft' | 'submitted' | 'approved' | 'rejected'
- submitted_at: timestamp
- approved_at: timestamp
- timestamps
```

**Relationships:**
- `BelongsTo` User

---

## Entity Relationship Diagram

```
users (1)
  ├── (1-to-Many) → qualifications
  ├── (1-to-Many) → addresses
  ├── (1-to-1) → applicant_profiles
  ├── (1-to-1) → healthcare_services
  ├── (1-to-1) → policy_pricings
  ├── (1-to-1) → risk_managements
  ├── (1-to-1) → insurance_histories
  ├── (1-to-1) → claims_experiences
  └── (1-to-1) → policy_applications

roles (Spatie Permission)
  └── (Many-to-Many) → users
```

---

## Larvel Eloquent Models Required

Create the following models:
```
php artisan make:model ApplicantProfile -m
php artisan make:model Address -m
php artisan make:model Qualification -m
php artisan make:model HealthcareService -m
php artisan make:model PolicyPricing -m
php artisan make:model RiskManagement -m
php artisan make:model InsuranceHistory -m
php artisan make:model ClaimsExperience -m
php artisan make:model PolicyApplication -m
```

---

## Best Practices Implemented

✅ **Normalization:** Each concern separated into its own table
✅ **Foreign Keys:** Proper relationships with cascade delete
✅ **Indexing:** User_id indexed for fast queries
✅ **Scalability:** Can easily add more records without affecting users table
✅ **Maintainability:** Clear separation of concerns
✅ **Relationships:** Proper Eloquent relationships for easy querying
✅ **Timestamps:** Auto tracking of created_at and updated_at
✅ **Data Types:** Appropriate column types (decimal for money, date for dates, text for long content)
✅ **Constraints:** Unique constraints on identifiers (NRIC, passport, registration number)
✅ **Comments:** Clear documentation in migration comments

---

## Migration Order

Run migrations in this order:
```
1. 2025_10_19_000001_add_applicant_fields_to_users_table.php
2. 2025_10_19_000002_create_qualifications_table.php
3. 2025_10_19_000003_create_applicant_profiles_table.php
4. 2025_10_19_000004_create_addresses_table.php
5. 2025_10_19_000005_create_healthcare_services_table.php
6. 2025_10_19_000006_create_policy_pricings_table.php
7. 2025_10_19_000007_create_risk_managements_table.php
8. 2025_10_19_000008_create_insurance_histories_table.php
9. 2025_10_19_000009_create_claims_experiences_table.php
10. 2025_10_19_000010_create_policy_applications_table.php
```

Run with:
```bash
php artisan migrate
```

---

## Sample Query Examples

### Get complete application for a user:
```php
$user = User::with([
    'applicantProfile',
    'qualifications',
    'addresses',
    'healthcareService',
    'policyPricing',
    'riskManagement',
    'insuranceHistory',
    'claimsExperience',
    'policyApplication'
])->find($userId);
```

### Get all submitted applications:
```php
$submitted = User::where('application_status', 'submitted')
    ->with('applicantProfile', 'policyApplication')
    ->get();
```

### Get user with all step data:
```php
$application = User::findOrFail($userId)->load([
    'applicantProfile:user_id,title,gender,registration_number',
    'qualifications:user_id,sequence,institution,degree_or_qualification',
    'addresses:user_id,type,address,city,state',
    'healthcareService:user_id,professional_indemnity_type,employment_status',
    'policyPricing:user_id,policy_start_date,liability_limit,total_payable'
]);
```

---

## Next Steps

1. ✅ Create all migration files (Done)
2. ⏳ Create Eloquent Models with relationships
3. ⏳ Create Model Observers for auto-saving
4. ⏳ Create API endpoints for form submission
5. ⏳ Create service classes for data validation
6. ⏳ Create admin dashboard for viewing applications
