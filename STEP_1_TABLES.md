# STEP 1: Applicant Details - Complete Table Structure

## Overview
Step 1 is split across **4 tables** for proper normalization:

---

## Table 1: **users** (Already exists - minimal changes)
```
- id (PK)
- name → Full Name from Step 1
- email → Email address (unique)
- password (hashed)
- contact_no → Phone number
- application_status: 'draft' | 'submitted' | 'approved' | 'rejected'
- application_submitted_at: timestamp
- timestamps
```

✅ We can reuse the existing `contact_no` column that was added in the previous migration!

---

## Table 2: **applicant_profiles** (NEW)
Stores personal identification information.

```
- id (PK)
- user_id (FK → users) [unique]
- title: 'DR' | 'MR' | 'MS' | 'PROF' | 'DATO' | 'DATIN' | 'DATUK' | etc
- nationality_status: 'malaysian' | 'non_malaysian'
- nric_number (unique, nullable)
- passport_number (unique, nullable)
- gender: 'male' | 'female'
- registration_council: 'mmc' | 'mdc' | 'others'
- other_council (nullable)
- registration_number (unique)
- timestamps
```

**Form Fields Stored:**
- ✅ Title
- ✅ Nationality Status
- ✅ NRIC Number
- ✅ Passport Number
- ✅ Gender
- ✅ Registration Council
- ✅ Other Council
- ✅ Registration Number

---

## Table 3: **applicant_contacts** (NEW - THIS WAS MISSING!)
Stores contact information.

```
- id (PK)
- user_id (FK → users) [unique]
- contact_no
- email_address (unique)
- timestamps
```

**Form Fields Stored:**
- ✅ Contact No
- ✅ Email Address

---

## Table 4: **addresses** (1-to-Many)
Stores multiple addresses (mailing, primary clinic, secondary clinic).

```
- id (PK)
- user_id (FK → users)
- type: 'mailing' | 'primary_clinic' | 'secondary_clinic'
- address
- postcode
- city
- state
- country
- clinic_type: 'government' | 'private' (for clinic addresses)
- clinic_name (for clinic addresses)
- timestamps
```

**Form Fields Stored:**
- ✅ Mailing Address + Postcode + City + State + Country
- ✅ Primary Clinic Type (radio: government/private)
- ✅ Primary Clinic Name + Address + Postcode + City + State + Country
- ✅ Secondary Clinic Type + Name + Address + Postcode + City + State + Country

---

## Table 5: **qualifications** (1-to-Many)
Stores up to 3 qualifications.

```
- id (PK)
- user_id (FK → users)
- sequence: 1 | 2 | 3
- institution
- degree_or_qualification
- year_obtained
- timestamps
```

**Form Fields Stored:**
- ✅ Qualification 1, 2, 3 (Institution, Degree, Year)

---

## Complete Step 1 Data Flow

### User Registration/Input:
```
Personal Information:
├── Title → applicant_profiles.title
├── Full Name → users.name
├── Nationality Status → applicant_profiles.nationality_status
├── NRIC Number → applicant_profiles.nric_number
├── Passport Number → applicant_profiles.passport_number
├── Gender → applicant_profiles.gender
├── Contact No → users.contact_no
└── Email Address → users.email

Mailing Address:
├── Address → addresses (type='mailing')
├── Postcode → addresses (type='mailing')
├── City → addresses (type='mailing')
├── State → addresses (type='mailing')
└── Country → addresses (type='mailing')

Primary Practicing Address:
├── Clinic Type → addresses (type='primary_clinic').clinic_type
├── Clinic Name → addresses (type='primary_clinic').clinic_name
├── Address → addresses (type='primary_clinic')
├── Postcode → addresses (type='primary_clinic')
├── City → addresses (type='primary_clinic')
├── State → addresses (type='primary_clinic')
└── Country → addresses (type='primary_clinic')

Secondary Practicing Address:
├── Clinic Type → addresses (type='secondary_clinic').clinic_type
├── Clinic Name → addresses (type='secondary_clinic').clinic_name
├── Address → addresses (type='secondary_clinic')
├── Postcode → addresses (type='secondary_clinic')
├── City → addresses (type='secondary_clinic')
├── State → addresses (type='secondary_clinic')
└── Country → addresses (type='secondary_clinic')

Qualifications (1-3):
├── Qualification 1 → qualifications (sequence=1)
├── Qualification 2 → qualifications (sequence=2)
└── Qualification 3 → qualifications (sequence=3)

Registration Details:
├── Registration Council → applicant_profiles.registration_council
├── Other Council → applicant_profiles.other_council
└── Registration Number → applicant_profiles.registration_number
```

---

## Eloquent Relationships

### User Model:
```php
public function applicantProfile() {
    return $this->hasOne(ApplicantProfile::class);
}

public function applicantContact() {
    return $this->hasOne(ApplicantContact::class);
}

public function addresses() {
    return $this->hasMany(Address::class);
}

public function qualifications() {
    return $this->hasMany(Qualification::class);
}
```

### Accessing Step 1 Data:
```php
$user = User::with([
    'applicantProfile',
    'applicantContact',
    'addresses',
    'qualifications'
])->find($userId);

// Access data:
$user->name                                    // Full Name
$user->email                                   // Email
$user->contact_no                              // Contact Number
$user->applicantProfile->title                 // Title
$user->applicantProfile->gender                // Gender
$user->applicantProfile->registration_number   // Reg Number

// Get all addresses
$mailingAddress = $user->addresses()->where('type', 'mailing')->first();
$primaryClinic = $user->addresses()->where('type', 'primary_clinic')->first();
$secondaryClinic = $user->addresses()->where('type', 'secondary_clinic')->first();

// Get all qualifications
$qualifications = $user->qualifications()->orderBy('sequence')->get();
```

---

## Migration Order for Step 1

```
1. 2025_10_19_000001_add_applicant_fields_to_users_table.php
2. 2025_10_19_000001_5_create_applicant_contacts_table.php ← NEW!
3. 2025_10_19_000002_create_qualifications_table.php
4. 2025_10_19_000003_create_applicant_profiles_table.php
5. 2025_10_19_000004_create_addresses_table.php
```

---

## Complete Step 1 Coverage

| Form Field | Table | Column | Type |
|-----------|-------|--------|------|
| Title | applicant_profiles | title | string |
| Full Name | users | name | string |
| Nationality Status | applicant_profiles | nationality_status | string |
| NRIC Number | applicant_profiles | nric_number | string (unique) |
| Passport Number | applicant_profiles | passport_number | string (unique) |
| Gender | applicant_profiles | gender | string |
| Contact No | users | contact_no | string |
| Email | users | email | string (unique) |
| Mailing Address | addresses | address | text |
| Mailing Postcode | addresses | postcode | string |
| Mailing City | addresses | city | string |
| Mailing State | addresses | state | string |
| Mailing Country | addresses | country | string |
| Primary Clinic Type | addresses | clinic_type | string |
| Primary Clinic Name | addresses | clinic_name | string |
| Primary Address | addresses | address | text |
| Primary Postcode | addresses | postcode | string |
| Primary City | addresses | city | string |
| Primary State | addresses | state | string |
| Primary Country | addresses | country | string |
| Secondary Clinic Type | addresses | clinic_type | string |
| Secondary Clinic Name | addresses | clinic_name | string |
| Secondary Address | addresses | address | text |
| Secondary Postcode | addresses | postcode | string |
| Secondary City | addresses | city | string |
| Secondary State | addresses | state | string |
| Secondary Country | addresses | country | string |
| Institution 1-3 | qualifications | institution | string |
| Qualification 1-3 | qualifications | degree_or_qualification | string |
| Year Obtained 1-3 | qualifications | year_obtained | year |
| Registration Council | applicant_profiles | registration_council | string |
| Other Council | applicant_profiles | other_council | string |
| Registration Number | applicant_profiles | registration_number | string (unique) |

✅ **100% coverage of all Step 1 fields!**
