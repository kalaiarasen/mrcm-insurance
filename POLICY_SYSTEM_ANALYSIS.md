# Policy Application System - Complete Analysis

## Overview
The new policy application is an 8-step form where users (clients) fill in their information to apply for Professional Indemnity insurance. The system stores data progressively and calculates pricing in Step 3.

---

## Step-by-Step Flow

### **Step 1: Applicant Details**
- **Personal Information**: Title, Full Name, Nationality Status, NRIC/Passport
- **Contact Information**: Gender, Contact No, Email (readonly - from auth)
- **Mailing Address**: Full address details
- **Primary Practicing Address**: Clinic type (Government/Private), Name, Full address
- **Secondary Practicing Address**: Optional, same structure as primary
- **Qualifications**: Up to 3 qualifications (Institution, Degree, Year)
- **Registration Details**: Licensing body (MMC/MDC/Others), Registration Number

**Database Tables:**
- `users` - Name, Contact No updated
- `applicant_profiles` - Personal info, NRIC/Passport, Gender, Registration details
- `qualifications` - Up to 3 education records
- `addresses` - 3 types: mailing, primary_clinic, secondary_clinic
- `applicant_contacts` - Contact No, Email

---

### **Step 2: Healthcare Services**
- Professional Indemnity Type (Medical/Dental/Pharmacist)
- Employment Status
- Specialty Area
- Cover Type
- Locum Practice Location
- Service Type (Core/Extended/Specialist)
- Practice Area

**Database Table:**
- `healthcare_services` - All service-related information

---

### **Step 3: Pricing Details** ⭐ **CRITICAL FOR COMMISSION**

#### **Input Fields:**
- Policy Start Date (required)
- Policy Expiry Date (auto-calculated, 1 year from start)
- Liability Limit (dropdown: RM 1M, 2M, 5M, 10M)
- Locum Extension (checkbox, optional add-on)

#### **Pricing Calculation Logic:**
Located in: `resources/views/pages/new-policy/js/_new-policy.blade.php` (line 800+)

```javascript
// Base calculation
const annualPremium = getBasePremium(liabilityLimit);  // From pricing table
const locumExtensionPremium = getLocumExtensionPremium();  // If selected

// Loading (from user profile)
const userLoadingPercentage = auth()->user()->loading ?? 0;
const loadingAmount = annualPremium * (userLoadingPercentage / 100);

// Pro-rata calculation
const numberOfDays = (endDate - startDate) + 1;
const daysInYear = 365;
const totalAnnualPremium = annualPremium + loadingAmount + locumExtensionPremium;
const grossPremium = (totalAnnualPremium * numberOfDays) / daysInYear;

// Discount (based on professional type or voucher)
const discountAmount = grossPremium * (discountPercentage / 100);
const discountedPremium = grossPremium - discountAmount;

// Taxes
const sst = discountedPremium * 0.08;  // 8% SST
const stampDuty = 10.00;  // Fixed

// Final Total
const totalPayable = discountedPremium + sst + stampDuty;
```

#### **Stored Fields in `policy_pricings` Table:**
```php
'policy_start_date'       // Policy start date
'policy_expiry_date'      // Policy end date (1 year)
'liability_limit'         // Liability coverage amount
'base_premium'            // Annual premium (before loading)
'loading_percentage'      // Risk loading % (from user profile)
'loading_amount'          // Loading amount in RM
'gross_premium'           // Base + Loading + Locum (pro-rata)
'locum_addon'             // Locum extension amount
'locum_extension'         // Boolean - was locum selected?
'discount_percentage'     // Discount %
'discount_amount'         // Discount amount in RM
'voucher_code'            // Applied voucher code (if any)
'sst'                     // 8% SST amount
'stamp_duty'              // RM 10 fixed
'total_payable'           // Final amount to pay
```

---

### **Step 4-7:** Risk Management, Insurance History, Claims Experience, Data Protection
Standard form fields stored in respective tables.

### **Step 8:** Declaration & Signature
Final submission with digital signature.

---

## Backend Processing

### **Submission Controller:**
`App\Http\Controllers\Api\PolicySubmissionController@submit`

**Process:**
1. **Validates** application data
2. **Updates** user record (name, contact, status)
3. **Marks old records** as `is_used = false`
4. **Creates new records** with `is_used = true` for:
   - ApplicantProfile
   - Qualifications (1-3)
   - Addresses (mailing, primary, secondary)
   - ApplicantContact
   - HealthcareService
   - **PolicyPricing** ⭐
   - RiskManagement
   - InsuranceHistory
   - ClaimsExperience
5. **Creates PolicyApplication** record linking everything
6. **Transaction wrapped** for data integrity

---

## Commission Calculation Requirements

### **Current Flow:**
```
Client applies → Step 3 calculates pricing → Data stored → Application submitted
```

### **Required for Commission System:**

#### **1. Commission Base Amount:**
Two possible bases:
- **Option A:** `gross_premium` (Base + Loading + Locum)
- **Option B:** `gross_premium + locum_addon` if locum selected

#### **2. Commission Rate:**
- Stored in `users.commission_percentage` (for agents)
- Applied to the base amount

#### **3. Commission Calculation:**
```php
// If agent referred the client (user.agent_id exists)
if ($user->agent_id) {
    $agent = User::find($user->agent_id);
    $commissionRate = $agent->commission_percentage ?? 0;
    
    // Option 1: Commission on Gross Premium
    $commissionBase = $policyPricing->gross_premium;
    
    // Option 2: Commission on Gross Premium + Locum (if applicable)
    // $commissionBase = $policyPricing->gross_premium;
    // if ($policyPricing->locum_extension) {
    //     $commissionBase += $policyPricing->locum_addon;
    // }
    
    $commissionAmount = $commissionBase * ($commissionRate / 100);
}
```

#### **4. New Database Table Needed:**
`agent_commissions`
```php
Schema::create('agent_commissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('policy_application_id')->constrained()->onDelete('cascade');
    $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
    $table->decimal('commission_rate', 5, 2); // e.g., 10.00 = 10%
    $table->decimal('base_amount', 10, 2); // Amount commission is calculated on
    $table->decimal('commission_amount', 10, 2); // Actual commission earned
    $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
    $table->date('approved_at')->nullable();
    $table->date('paid_at')->nullable();
    $table->timestamps();
});
```

#### **5. Integration Points:**

**A. During Policy Submission** (PolicySubmissionController.php line ~200)
```php
// After creating PolicyPricing
$policyPricing = PolicyPricing::create([...]);

// After creating PolicyApplication
$policyApplication = PolicyApplication::create([...]);

// Check if client has an agent
if ($currentUser->agent_id) {
    $agent = User::find($currentUser->agent_id);
    
    if ($agent && $agent->commission_percentage > 0) {
        // Calculate commission base
        $commissionBase = $policyPricing->gross_premium;
        
        // Calculate commission amount
        $commissionAmount = $commissionBase * ($agent->commission_percentage / 100);
        
        // Create commission record
        AgentCommission::create([
            'agent_id' => $agent->id,
            'policy_application_id' => $policyApplication->id,
            'client_id' => $currentUser->id,
            'commission_rate' => $agent->commission_percentage,
            'base_amount' => $commissionBase,
            'commission_amount' => $commissionAmount,
            'status' => 'pending',
        ]);
    }
}
```

**B. New Agent Menu Item** (sidebar.blade.php)
```blade
@hasrole('Agent')
<li class="sidebar-list">
    <i class="fa-solid fa-thumbtack"></i>
    <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('agent.commissions') ? 'active' : '' }}"
        href="{{ route('agent.commissions') }}">
        <svg class="stroke-icon">
            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-price') }}"></use>
        </svg>
        <svg class="fill-icon">
            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-price') }}"></use>
        </svg>
        <span>My Commissions</span>
    </a>
</li>
@endhasrole
```

**C. Agent Commission Dashboard**
- List all commissions earned
- Filter by status (pending/approved/paid)
- Show: Client Name, Policy ID, Application Date, Commission Amount, Status
- Total earnings summary

---

## Key Relationships

```
User (Agent)
  ↓ has many
AgentCommission
  ↓ belongs to
PolicyApplication
  ↓ has one
PolicyPricing (contains all pricing data)
  ↓ belongs to
User (Client)
```

---

## Important Notes

1. **Gross Premium** is the pro-rated annual premium including loading and locum
2. **Total Payable** includes SST and stamp duty - should NOT be used for commission
3. **Locum Extension** is optional - decide if commission includes this or not
4. **Commission Rate** is stored per agent in `users.commission_percentage`
5. **Agent Assignment** happens during client registration via `users.agent_id`
6. All pricing calculations happen client-side (JavaScript) then stored server-side
7. Current system marks old records as `is_used = false` for history tracking

---

## Next Steps for Implementation

1. Create `agent_commissions` migration and model
2. Add commission calculation in PolicySubmissionController
3. Create AgentCommissionController with index/show methods
4. Create agent commission dashboard view
5. Add route and sidebar menu item for agents
6. Add commission approval workflow for admins (optional)
7. Add commission payment tracking (optional)

