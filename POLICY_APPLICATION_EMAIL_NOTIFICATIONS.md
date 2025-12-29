# Policy Application Email Notifications System

## Overview

This document describes the comprehensive email notification system for the policy application lifecycle in the MRCM Insurance Portal. The system automatically sends emails to clients at key milestones throughout their application journey.

## Implementation Date

December 29, 2025

## Email Notifications Overview

The system sends 6 different email notifications covering the complete policy application lifecycle:

1. **Application Submitted** - When client submits new/renewal policy
2. **Application Approved** - When admin approves the application
3. **Sent to Underwriting** - When application is forwarded to underwriting department
4. **Policy Active** - When Certificate of Insurance (CI) is issued
5. **Application Rejected** - When admin rejects/requests amendments
6. **Application Cancelled** - When application is cancelled due to no response

---

## 1. Application Submitted Email

### Trigger
- **Event**: Client completes and submits policy application (new or renewal)
- **Location**: `PolicySubmissionController::submit()`
- **Status**: `submitted`

### Email Details
- **Subject**: "Application Successfully Submitted - MRCM Insurance"
- **Mail Class**: `App\Mail\PolicySubmittedMail`
- **Template**: `resources/views/emails/policy-submitted.blade.php`
- **Color Theme**: Purple gradient

### Content Includes
- Client name with title (e.g., "Dr. John Smith")
- Confirmation message about successful submission
- Reference number (if available)
- Submission date and time
- Information about next steps (awaiting approval)
- Contact information

### Implementation
```php
// In PolicySubmissionController::submit()
use App\Mail\PolicySubmittedMail;

try {
    Mail::to($currentUser->email)->send(
        new PolicySubmittedMail($policyApplication->load('user.applicantProfile'))
    );
    Log::info('Policy submission email sent', [
        'policy_id' => $policyApplication->id,
        'user_email' => $currentUser->email,
    ]);
} catch (\Exception $mailException) {
    Log::warning('Failed to send policy submission email', [
        'policy_id' => $policyApplication->id,
        'user_email' => $currentUser->email,
        'error' => $mailException->getMessage(),
    ]);
}
```

---

## 2. Application Approved Email

### Trigger
- **Event**: Admin changes policy status to "approved"
- **Location**: `YourActionController::updateStatus()`
- **Status Change**: Any status → `approved`

### Email Details
- **Subject**: "Application Approved - Payment Required - MRCM Insurance"
- **Mail Class**: `App\Mail\PolicyApprovedMail`
- **Template**: `resources/views/emails/policy-approved.blade.php`
- **Color Theme**: Green gradient

### Content Includes
- Client name with title
- Approval confirmation message
- Reference number
- Approved date and time
- **Call-to-action**: Login to portal to proceed with payment
- Portal URL: https://insurance.mrcm.com.my
- Payment instructions reminder
- Contact information

### Status Updates
- Customer Status: `pay_now`
- Admin Status: `not_paid`
- Sets: `approved_at` timestamp

---

## 3. Sent to Underwriting Email (Client Notification)

### Trigger
- **Event**: Admin changes policy status to "send_uw"
- **Location**: `YourActionController::updateStatus()`
- **Status Change**: Any status → `send_uw`

### Email Details
- **Subject**: "Application Submitted to Underwriting - MRCM Insurance"
- **Mail Class**: `App\Mail\PolicySentToUnderwritingClientMail`
- **Template**: `resources/views/emails/policy-sent-to-underwriting.blade.php`
- **Color Theme**: Teal/Cyan gradient

### Content Includes
- Client name with title
- Confirmation that application is with underwriting department
- Reference number
- Date submitted to underwriting
- **Timeline information**: Up to 5 working days for processing
- Patience request during processing period
- Contact information

### Status Updates
- Customer Status: `processing`
- Admin Status: `sent_uw`
- Sets: `sent_to_underwriter_at` timestamp
- Generates reference number if not already set

### Additional Actions
- Sends separate email to underwriting department (existing functionality)
- Email to underwriting includes PDF attachment with application details

---

## 4. Policy Active Email

### Trigger
- **Event**: Admin changes policy status to "active"
- **Location**: `YourActionController::updateStatus()`
- **Status Change**: Any status → `active`

### Email Details
- **Subject**: "Certificate of Insurance Issued - MRCM Insurance"
- **Mail Class**: `App\Mail\PolicyActiveMail`
- **Template**: `resources/views/emails/policy-active.blade.php`
- **Color Theme**: Green gradient

### Content Includes
- Client name with title
- Congratulations message - CI has been issued
- Reference number
- Activated date and time
- **Certificate Download Link** (if certificate document is uploaded)
- Link to view policy on portal
- Notice: Full policy schedule and tax receipt within 30 days
- Contact information

### Status Updates
- Customer Status: `active`
- Admin Status: `active`
- Sets: `activated_at` timestamp
- Generates reference number if not already set
- Saves uploaded certificate document path

### Certificate Management
- Admin uploads Certificate of Insurance (CI) PDF when activating
- File stored in: `public/certificates/`
- Filename format: `CI_MRCM#[ref]_[timestamp].pdf`
- Certificate accessible via download link in email and portal

---

## 5. Application Rejected Email

### Trigger
- **Event**: Admin changes policy status to "rejected"
- **Location**: `YourActionController::updateStatus()`
- **Status Change**: Any status → `rejected`

### Email Details
- **Subject**: "Application Requires Amendment - MRCM Insurance"
- **Mail Class**: `App\Mail\PolicyRejectedMail`
- **Template**: `resources/views/emails/policy-rejected.blade.php`
- **Color Theme**: Red gradient

### Content Includes
- Client name with title
- Notification that application requires amendments
- Reference number
- **Rejection reason/remarks** (from admin input)
- Clear call-to-action: Login to portal to amend application
- Portal URL for amendments
- Contact information for assistance

### Status Updates
- Customer Status: `rejected`
- Admin Status: `rejected`
- Stores rejection reason in `remarks` field
- **Payment data cleared**: All payment-related fields reset
- Payment document deleted from storage

### Cleared Payment Fields
- payment_document
- payment_method
- name_on_card
- nric_no
- card_no
- card_issuing_bank
- card_type
- expiry_month
- expiry_year
- relationship
- authorize_payment
- payment_received_at

---

## 6. Application Cancelled Email

### Trigger
- **Event**: Admin changes policy status to "cancelled"
- **Location**: `YourActionController::updateStatus()`
- **Status Change**: Any status → `cancelled`

### Email Details
- **Subject**: "Application Cancelled - MRCM Insurance"
- **Mail Class**: `App\Mail\PolicyCancelledMail`
- **Template**: `resources/views/emails/policy-cancelled.blade.php`
- **Color Theme**: Gray gradient

### Content Includes
- Client name with title
- Reference to application
- Reference number
- Reason: No response received despite previous communication
- Notification of cancellation
- **Future application option**: Welcome to resubmit new application
- Portal URL for new submissions
- Contact information

---

## Technical Implementation

### Mail Classes Location
```
app/Mail/
├── PolicySubmittedMail.php
├── PolicyApprovedMail.php
├── PolicySentToUnderwritingClientMail.php
├── PolicyActiveMail.php
├── PolicyRejectedMail.php
└── PolicyCancelledMail.php
```

### Email Templates Location
```
resources/views/emails/
├── policy-submitted.blade.php
├── policy-approved.blade.php
├── policy-sent-to-underwriting.blade.php
├── policy-active.blade.php
├── policy-rejected.blade.php
└── policy-cancelled.blade.php
```

### Controllers Modified
1. **PolicySubmissionController** (`app/Http/Controllers/Api/PolicySubmissionController.php`)
   - Added email sending on application submission
   - Lines: ~350-365

2. **YourActionController** (`app/Http/Controllers/YourActionController.php`)
   - Added email sending based on status changes
   - Lines: ~580-620
   - Switch statement handles all 5 status change notifications

### Client Name Formatting
All Mail classes include intelligent client name handling:
```php
// Get client name with title
$profile = $policyApplication->user->applicantProfile;
$title = $profile->title ?? '';
$name = $policyApplication->user->name;

// Remove title from name if it's already there
$nameWithoutTitle = preg_replace('/^(DR\.|PROF\.|MR\.|MS\.|MRS\.)\s*/i', '', $name);

// Format: "Dr. John Smith"
$this->clientName = trim(ucfirst(strtolower($title)) . '. ' . $nameWithoutTitle);
```

### Error Handling
All email sending operations use try-catch blocks:
- **Non-blocking**: Email failures don't interrupt main application flow
- **Logging**: All email attempts (success/failure) are logged
- **Graceful degradation**: System continues functioning even if email fails

Example:
```php
try {
    Mail::to($user->email)->send(new PolicySubmittedMail($policyApplication));
    Log::info('Email sent successfully', ['policy_id' => $id]);
} catch (\Exception $mailException) {
    Log::warning('Failed to send email', [
        'policy_id' => $id,
        'error' => $mailException->getMessage(),
    ]);
    // Application continues normally
}
```

---

## Email Design Features

### Common Design Elements
All emails share consistent professional design:
- **Responsive HTML/CSS** - Mobile-friendly
- **Gradient headers** - Color-coded by email type
- **Professional typography** - Segoe UI font family
- **Information boxes** - Highlighted key details
- **Call-to-action buttons** - Clear next steps
- **Company branding** - MRCM Services (M) Sdn. Bhd.
- **Footer disclaimers** - Automated email notice

### Color Coding
- **Submitted**: Purple gradient (#667eea → #764ba2)
- **Approved**: Green gradient (#28a745 → #20c997)
- **Sent to UW**: Teal gradient (#17a2b8 → #138496)
- **Active**: Green gradient (#28a745 → #20c997)
- **Rejected**: Red gradient (#dc3545 → #c82333)
- **Cancelled**: Gray gradient (#6c757d → #5a6268)

### Information Boxes
Each email includes styled information boxes with:
- Border-left accent color matching theme
- Reference number
- Relevant timestamps
- Status information
- Key action items

---

## Email Flow Diagram

```
New Application Submitted
         ↓
    [Email: Submitted]
         ↓
Admin Reviews Application
         ↓
    ┌────┴────┬─────────┬──────────┐
    ↓         ↓         ↓          ↓
Approved  Rejected  Send UW    Cancelled
    ↓         ↓         ↓          ↓
[Email]   [Email]   [Email]    [Email]
    ↓                   ↓
Client Pays      UW Processes
    ↓                   ↓
Payment Received   UW Approves
    ↓                   ↓
Admin Activates ←──────┘
    ↓
[Email: Active]
```

---

## Testing Checklist

### 1. Application Submitted Email
- [ ] Submit new policy application
- [ ] Verify email received at client email address
- [ ] Check reference number displayed (if generated)
- [ ] Verify submission timestamp is correct
- [ ] Test email rendering on mobile and desktop

### 2. Application Approved Email
- [ ] Admin approves application
- [ ] Verify email received
- [ ] Check "Login to Portal" button works
- [ ] Verify approved timestamp
- [ ] Confirm customer_status = 'pay_now'

### 3. Sent to Underwriting Email
- [ ] Admin changes status to "send_uw"
- [ ] Verify client receives notification email
- [ ] Check underwriting department receives separate email
- [ ] Verify 5-day timeline message present
- [ ] Confirm sent_to_underwriter_at timestamp set

### 4. Policy Active Email
- [ ] Admin uploads CI and activates policy
- [ ] Verify email received
- [ ] Check certificate download link works
- [ ] Verify portal link works
- [ ] Confirm 30-day notice displayed
- [ ] Check activated_at timestamp

### 5. Application Rejected Email
- [ ] Admin rejects with remarks
- [ ] Verify email received
- [ ] Check remarks/reason displayed correctly
- [ ] Verify "Login to Amend" button works
- [ ] Confirm payment data cleared in database

### 6. Application Cancelled Email
- [ ] Admin cancels application
- [ ] Verify email received
- [ ] Check "Submit New Application" button works
- [ ] Verify cancellation message displayed

### General Testing
- [ ] All emails display client name with title correctly
- [ ] No emails sent when status doesn't change
- [ ] Email failures logged but don't break application
- [ ] All timestamps formatted correctly
- [ ] All portal URLs correct (https://insurance.mrcm.com.my)
- [ ] All emails signed by "Suresh, MRCM Services (M) Sdn. Bhd."
- [ ] Footer disclaimers present in all emails

---

## Configuration

### Mail Settings
Ensure your `.env` file has proper mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@mrcm.com.my
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@mrcm.com.my
MAIL_FROM_NAME="MRCM Insurance"
```

### Portal URL
Portal URL is hardcoded in Mail classes as:
```php
$this->portalUrl = 'https://insurance.mrcm.com.my';
```

To change, update all 5 Mail classes that include `$portalUrl` property.

---

## Logging

All email activities are logged for monitoring:

### Successful Emails
```php
Log::info('Policy [status] email sent', [
    'policy_id' => $id,
    'user_email' => $email,
]);
```

### Failed Emails
```php
Log::warning('Failed to send policy [status] email', [
    'policy_id' => $id,
    'user_email' => $email,
    'error' => $exception->getMessage(),
]);
```

Check logs at: `storage/logs/laravel.log`

---

## Future Enhancements

### Potential Improvements
1. **Queue System**: Move email sending to queues for better performance
2. **Email Templates CMS**: Allow admin to edit email templates via portal
3. **Multi-language Support**: Send emails in client's preferred language
4. **SMS Notifications**: Add SMS alerts for critical status changes
5. **Email Preferences**: Let clients choose which emails they want to receive
6. **Email Analytics**: Track email open rates and click-through rates
7. **Attachment Support**: Attach relevant documents to emails
8. **Reply-to Address**: Set up monitored email for client replies
9. **Scheduled Reminders**: Send follow-up emails for pending actions
10. **Email Preview**: Allow admin to preview emails before sending

### Queue Implementation Example
```php
// Instead of immediate sending
Mail::to($email)->send(new PolicyApprovedMail($policy));

// Use queued sending
Mail::to($email)->queue(new PolicyApprovedMail($policy));
```

---

## Support and Maintenance

### Common Issues

**Issue**: Emails not being sent
- Check `.env` mail configuration
- Verify SMTP credentials
- Check `storage/logs/laravel.log` for errors
- Test mail configuration: `php artisan tinker` → `Mail::raw('Test', function($msg) { $msg->to('test@example.com'); });`

**Issue**: Emails going to spam
- Configure SPF, DKIM, DMARC records for sending domain
- Use reputable SMTP service (SendGrid, Mailgun, Amazon SES)
- Avoid spam trigger words in subject lines
- Include unsubscribe option

**Issue**: Wrong client name format
- Check `applicantProfile` relationship loaded
- Verify title field in database
- Check regex pattern in Mail class constructor

**Issue**: Certificate download link not working
- Verify certificate_document field has correct path
- Check file exists in `storage/app/public/certificates/`
- Ensure storage link is created: `php artisan storage:link`

---

## Contact and Support

For questions or issues with the email notification system:

- **Developer**: Development Team
- **Implementation Date**: December 29, 2025
- **Related Documentation**: 
  - EMAIL_NOTIFICATIONS_SYSTEM.md (previous implementation)
  - SUBMISSION_SYSTEM.md
  - DATABASE_SCHEMA.md

---

## Summary

The Policy Application Email Notifications System provides comprehensive, automated communication throughout the entire policy application lifecycle. With 6 strategically-timed emails, clients stay informed at every step from initial submission to policy activation or cancellation. The system is built with robust error handling, professional email design, and extensive logging for reliability and maintainability.
