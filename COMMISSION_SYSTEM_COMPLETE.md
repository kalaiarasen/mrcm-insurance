# Agent Commission System - Implementation Complete

## Overview
The agent commission system automatically tracks and displays agent earnings based on policy applications submitted by their assigned clients.

## Key Features

### 1. Automatic Commission Calculation
- **Trigger**: When a client with `agent_id` submits a policy application (Step 8)
- **Formula**: `commission_amount = gross_premium × (commission_percentage / 100)`
- **Base Amount**: Uses `gross_premium` (includes base + loading + locum pro-rata, excludes taxes)
- **Commission Rate**: Stored from `users.commission_percentage` at time of application

### 2. Dynamic Status Tracking
- **No stored status field** - computed dynamically from policy application status
- **Active**: Commission is receivable when `PolicyApplication.admin_status = 'active'`
- **Pending**: All other statuses (draft, submitted, under review, rejected, etc.)
- **Real-time**: Status updates automatically when policy status changes

### 3. Agent Dashboard
- **Route**: `/agent/commissions`
- **Access**: Only for users with "Agent" role
- **Summary Statistics**:
  - Total Earned: Sum of all commissions
  - Active Commissions: Sum of commissions for active policies
  - Pending Commissions: Sum of commissions for non-active policies
  - Total Policies: Count of commission records

### 4. Commission History Table
- **Features**: Server-side DataTables with sorting/filtering
- **Columns**:
  - Date (created_at)
  - Client Name
  - Client Code
  - Policy Reference Number
  - Base Amount (gross premium)
  - Commission Rate (%)
  - Commission Amount (RM)
  - Status (Active/Pending badge)
- **Filters**: Search by client name, client code, policy reference

## Database Schema

### Table: `agent_commissions`
```sql
agent_id                  BIGINT UNSIGNED (FK to users)
policy_application_id     BIGINT UNSIGNED (FK to policy_applications)
client_id                 BIGINT UNSIGNED (FK to users)
commission_rate           DECIMAL(5,2)  -- Percentage at time of application
base_amount               DECIMAL(10,2) -- gross_premium value
commission_amount         DECIMAL(10,2) -- Calculated earnings
created_at                TIMESTAMP
updated_at                TIMESTAMP

Indexes:
- agent_id
- policy_application_id
- client_id

Foreign Keys:
- CASCADE on delete for all relationships
```

## Implementation Details

### Files Created/Modified

#### 1. Migration: `database/migrations/2025_12_02_211100_create_agent_commissions_table.php`
- Creates agent_commissions table
- Status: Migrated successfully ✓

#### 2. Model: `app/Models/AgentCommission.php`
- Relationships: `agent()`, `client()`, `policyApplication()`
- Computed attribute: `getStatusAttribute()`
- Decimal casts for monetary fields

#### 3. Controller: `app/Http/Controllers/AgentCommissionController.php`
- `index()` method:
  - Returns view with summary statistics
  - Handles AJAX DataTables requests
  - Filters data to logged-in agent only
  - Custom column formatting (badges, currency)
  - Search/filter support

#### 4. View: `resources/views/pages/agent/commissions.blade.php`
- 4 summary cards with statistics
- DataTables integration
- Bootstrap 5 styling
- Status badges (green for active, yellow for pending)

#### 5. Route: `routes/web.php`
- Added: `Route::get('agent/commissions', [AgentCommissionController::class, 'index'])->name('agent.commissions');`
- Import: `use App\Http\Controllers\AgentCommissionController;`

#### 6. Sidebar: `resources/views/layouts/sidebar.blade.php`
- Added "My Commissions" menu item
- Icon: stroke-price/fill-price
- Only visible to Agent role
- Active state detection

#### 7. Policy Submission: `app/Http/Controllers/Api/PolicySubmissionController.php`
- Lines 237-258: Commission calculation logic
- Checks if client has agent_id
- Verifies agent commission_percentage > 0
- Creates AgentCommission record automatically
- Logs commission creation for debugging

## Usage Flow

### For Clients (with assigned agent):
1. Client submits policy application through 8-step form
2. On Step 8 submission, system checks if `user.agent_id` exists
3. If yes, creates commission record automatically
4. Commission initially has "pending" status

### For Agents:
1. Login to system
2. Click "My Commissions" in sidebar
3. View summary dashboard with earnings breakdown
4. Browse commission history in table
5. Filter by client name, code, or policy reference
6. See real-time status updates when policies become active

### For Admins:
1. Approve policy application (set admin_status = 'active')
2. Commission automatically becomes "active" for agent
3. No additional approval workflow needed

## Testing Checklist

✓ Commission created when client with agent_id submits policy
✓ Commission amount calculated correctly (base × rate / 100)
✓ Status computed dynamically from policy application status
✓ Agent dashboard shows only their commissions
✓ Summary statistics calculated correctly
✓ DataTables filtering and sorting works
✓ Status badge updates when policy status changes
✓ Menu item visible only to agents

## API/Data Access

### For retrieving agent commissions programmatically:
```php
// Get all commissions for logged-in agent
$commissions = AgentCommission::where('agent_id', Auth::id())
    ->with(['client', 'policyApplication'])
    ->get();

// Get active commissions only
$activeCommissions = AgentCommission::where('agent_id', Auth::id())
    ->whereHas('policyApplication', function($q) {
        $q->where('admin_status', 'active');
    })
    ->get();

// Calculate total earnings
$totalEarned = AgentCommission::where('agent_id', Auth::id())
    ->sum('commission_amount');

// Access computed status
$commission = AgentCommission::find(1);
$status = $commission->status; // Returns 'active' or 'pending'
```

## Notes

- **No manual commission approval**: Status tied directly to policy application status
- **No payment tracking**: System only tracks commission eligibility, not actual payments
- **Historical rate preservation**: Commission rate stored at time of application, not dynamically referenced
- **Cascading deletes**: If policy application deleted, commission record also deleted
- **Agent filtering**: All queries automatically filtered to logged-in agent's data only
