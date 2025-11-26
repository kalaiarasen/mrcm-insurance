<?php

namespace App\Http\Controllers;

use App\Models\PolicyApplication;
use App\Mail\SendToUnderwriting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class YourActionController extends Controller
{
    /**
     * Display the for-your-action page with policy statistics and pending policies.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PolicyApplication::with('user')
                ->orderBy('updated_at', 'desc');

            return DataTables::of($query)
                ->addColumn('policy_id', function ($policy) {
                    return '<small>' . e($policy->reference_number ?? '') . '</small>';
                })
                ->addColumn('status', function ($policy) {
                    $statusMap = [
                        'new_case' => 'New Case',
                        'new_renewal' => 'New Renewal',
                        'not_paid' => 'Not Paid',
                        'paid' => 'Paid',
                        'sent_uw' => 'Sent UW',
                        'active' => 'Active',
                    ];
                    
                    $displayStatus = $statusMap[$policy->admin_status] ?? ucfirst($policy->admin_status ?? 'N/A');
                    
                    $badgeClass = match($displayStatus) {
                        'Approved' => 'bg-success',
                        'Submitted' => 'bg-info',
                        'Rejected' => 'bg-danger',
                        'Active' => 'bg-primary',
                        'Processing' => 'bg-warning',
                        'Send UW', 'Sent UW' => 'bg-info',
                        'Cancelled' => 'bg-secondary',
                        'New', 'New Case' => 'bg-light text-dark',
                        default => 'bg-secondary'
                    };
                    
                    return '<span class="badge ' . $badgeClass . '">' . e($displayStatus) . '</span>';
                })
                ->addColumn('expiry_date', function ($policy) {
                    $expiryDate = $policy->user?->policyPricing?->policy_expiry_date ?? 'N/A';
                    return $expiryDate === 'N/A' ? 'N/A' : \Carbon\Carbon::parse($expiryDate)->format('d-M-Y');
                })
                ->addColumn('name', function ($policy) {
                    return '<strong>' . e($policy->user?->name ?? 'Unknown') . '</strong>';
                })
                ->addColumn('policy_no', function ($policy) {
                    return e($policy->reference_number ?? '');
                })
                ->addColumn('email', function ($policy) {
                    return e($policy->user?->email ?? 'N/A');
                })
                ->addColumn('class', function ($policy) {
                    return e($policy->user?->healthcareService?->coverage_type ?? 'General Cover');
                })
                ->addColumn('amount', function ($policy) {
                    $amount = $policy->user?->policyPricing?->total_payable;
                    if (is_numeric($amount)) {
                        return 'RM' . number_format($amount, 2);
                    } elseif ($amount === null) {
                        return 'null';
                    }
                    return e($amount);
                })
                ->addColumn('action', function ($policy) {
                    $viewUrl = route('for-your-action.show', $policy->id);
                    $deleteUrl = route('for-your-action.destroy', $policy->id);
                    
                    $html = '<ul class="action">';
                    $html .= '<li class="view me-2">';
                    $html .= '<a href="' . $viewUrl . '" title="View Details">';
                    $html .= '<i class="fa-regular fa-eye"></i>';
                    $html .= '</a>';
                    $html .= '</li>';
                    
                    if (in_array($policy->status, ['New Case', 'Rejected'])) {
                        $html .= '<li class="delete">';
                        $html .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this policy application?\');">';
                        $html .= csrf_field();
                        $html .= method_field('DELETE');
                        $html .= '<button type="submit" class="border-0 bg-transparent p-0" title="Delete">';
                        $html .= '<i class="fa-regular fa-trash-can"></i>';
                        $html .= '</button>';
                        $html .= '</form>';
                        $html .= '</li>';
                    }
                    
                    $html .= '</ul>';
                    return $html;
                })
                ->rawColumns(['policy_id', 'status', 'name', 'action'])
                ->make(true);
        }

        // Count policies by admin_status (show all, no is_used filter)
        $newPolicies = PolicyApplication::where('admin_status', 'new_case')->count();
        $activePolicies = PolicyApplication::where('admin_status', 'active')->count();
        $pendingPolicies = PolicyApplication::where('admin_status', 'not_paid')->count();
        $rejectedPolicies = PolicyApplication::where('status', 'rejected')->count();

        return view('pages.your-action.index', compact('newPolicies', 'activePolicies', 'pendingPolicies', 'rejectedPolicies'));
    }

    /**
     * Display detailed view of a specific policy application.
     */
    public function show($id)
    {
        // Find the policy application with all related data (no is_used filter)
        $policyApplication = PolicyApplication::with([
            'user.applicantProfile',
            'user.qualifications',
            'user.addresses',
            'user.applicantContact',
            'user.healthcareService',
            'user.policyPricing',
            'user.riskManagement',
            'user.insuranceHistory',
            'user.claimsExperience',
            'actionBy'
        ])
        ->where('id', $id)
        ->firstOrFail();

        return view('pages.your-action.show', compact('policyApplication'));
    }

    /**
     * Update the status of a policy application.
     */
    public function updateStatus(Request $request, $id)
    {
        $policyApplication = PolicyApplication::where('id', $id)
            ->firstOrFail();

        // Validate request
        $validated = $request->validate([
            'status' => 'required|in:new,approved,send_uw,active,processing,rejected,cancelled',
            'remarks' => 'nullable|string|max:5000',
        ]);

        $oldStatus = $policyApplication->status;
        $newStatus = $validated['status'];

        // Start transaction
        DB::beginTransaction();

        try {
            // Update basic fields
            $policyApplication->status = $newStatus;
            $policyApplication->remarks = $validated['remarks'];
            
            // Set action metadata for all status changes
            $policyApplication->action_by = Auth::id();
            $policyApplication->action_at = now();

            // Handle status-specific actions and update dual status system
            switch ($newStatus) {
                case 'approved':
                    // When admin approves:
                    // Customer Status → pay_now (client gets email and pay now appears)
                    // Admin Status → not_paid (waiting for payment)
                    $policyApplication->customer_status = 'pay_now';
                    $policyApplication->admin_status = 'not_paid';
                    $policyApplication->approved_at = now();
                    break;

                case 'active':
                    // When policy becomes active:
                    // Customer Status → active
                    // Admin Status → active
                    $policyApplication->customer_status = 'active';
                    $policyApplication->admin_status = 'active';
                    $policyApplication->activated_at = now();
                    
                    // Generate reference number ONLY when status becomes active
                    if (!$policyApplication->reference_number) {
                        $policyApplication->reference_number = $this->generateReferenceNumber($policyApplication->user_id);
                    }
                    break;
                    
                case 'send_uw':
                    // When sent to underwriter:
                    // Customer Status → processing
                    // Admin Status → sent_uw
                    $policyApplication->customer_status = 'processing';
                    $policyApplication->admin_status = 'sent_uw';
                    $policyApplication->sent_to_underwriter_at = now();
                    
                    // Send email to underwriting with PDF attachment
                    try {
                        Mail::send(new SendToUnderwriting($policyApplication));
                        Log::info('Underwriting email sent successfully', [
                            'policy_id' => $id,
                            'reference' => $policyApplication->reference_number,
                        ]);
                    } catch (\Exception $mailException) {
                        Log::error('Failed to send underwriting email', [
                            'policy_id' => $id,
                            'error' => $mailException->getMessage(),
                        ]);
                        // Continue with status update even if email fails
                    }
                    break;
                    
                case 'rejected':
                    // When admin rejects:
                    // Customer Status → rejected
                    // Admin Status → rejected
                    $policyApplication->customer_status = 'rejected';
                    $policyApplication->admin_status = 'rejected';
                    break;
            }

            $policyApplication->save();

            // Update user application status
            $policyApplication->user->update([
                'application_status' => $newStatus,
            ]);

            DB::commit();

            $successMessage = "Application status updated from '{$oldStatus}' to '{$newStatus}' successfully!";
            if ($newStatus === 'send_uw') {
                $successMessage .= " Email sent to underwriting department.";
            }

            return redirect()
                ->route('for-your-action')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update policy application status', [
                'policy_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to update application status. Please try again.');
        }
    }

    /**
     * Show the edit form for a policy application.
     */
    public function edit($id)
    {
        // Find the policy application with all related data (no is_used filter)
        $policyApplication = PolicyApplication::with([
            'user.applicantProfile',
            'user.qualifications',
            'user.addresses',
            'user.applicantContact',
            'user.healthcareService',
            'user.policyPricing',
            'user.riskManagement',
            'user.insuranceHistory',
            'user.claimsExperience',
        ])
        ->where('id', $id)
        ->firstOrFail();

        return view('pages.your-action.edit', compact('policyApplication'));
    }

    /**
     * Update the policy application data.
     */
    public function update(Request $request, $id)
    {
        $policyApplication = PolicyApplication::where('id', $id)
            ->firstOrFail();

        // Start transaction
        DB::beginTransaction();

        try {
            $data = $request->input('application_data');
            $user = $policyApplication->user;

            // Update User
            $user->update([
                'name' => $data['full_name'] ?? $user->name,
                'email' => $data['email_address'] ?? $user->email,
                'contact_no' => $data['contact_no'] ?? $user->contact_no,
            ]);

            // Update Applicant Profile
            if ($user->applicantProfile) {
                $user->applicantProfile->update([
                    'title' => $data['title'] ?? $user->applicantProfile->title,
                    'nationality_status' => $data['nationality_status'] ?? $user->applicantProfile->nationality_status,
                    'nric_number' => $data['nric_number'] ?? $user->applicantProfile->nric_number,
                    'passport_number' => $data['passport_number'] ?? $user->applicantProfile->passport_number,
                    'gender' => $data['gender'] ?? $user->applicantProfile->gender,
                    'registration_council' => $data['registration_council'] ?? $user->applicantProfile->registration_council,
                    'other_council' => $data['other_council'] ?? $user->applicantProfile->other_council,
                    'registration_number' => $data['registration_number'] ?? $user->applicantProfile->registration_number,
                ]);
            }

            // Update Addresses
            $addressTypes = [
                'mailing' => ['address' => 'mailing_address', 'postcode' => 'mailing_postcode', 'city' => 'mailing_city', 'state' => 'mailing_state', 'country' => 'mailing_country'],
                'primary_clinic' => ['type_field' => 'primary_clinic_type', 'clinic_name_field' => 'primary_clinic_name', 'address' => 'primary_address', 'postcode' => 'primary_postcode', 'city' => 'primary_city', 'state' => 'primary_state', 'country' => 'primary_country'],
                'secondary_clinic' => ['type_field' => 'secondary_clinic_type', 'clinic_name_field' => 'secondary_clinic_name', 'address' => 'secondary_address', 'postcode' => 'secondary_postcode', 'city' => 'secondary_city', 'state' => 'secondary_state', 'country' => 'secondary_country'],
            ];

            foreach ($addressTypes as $type => $fields) {
                $address = $user->addresses->firstWhere('type', $type);
                $addressData = [];

                if ($type === 'mailing') {
                    $addressData = [
                        'address' => $data[$fields['address']] ?? null,
                        'postcode' => $data[$fields['postcode']] ?? null,
                        'city' => $data[$fields['city']] ?? null,
                        'state' => $data[$fields['state']] ?? null,
                        'country' => $data[$fields['country']] ?? null,
                    ];
                } else {
                    $addressData = [
                        'clinic_type' => $data[$fields['type_field']] ?? null,
                        'clinic_name' => $data[$fields['clinic_name_field']] ?? null,
                        'address' => $data[$fields['address']] ?? null,
                        'postcode' => $data[$fields['postcode']] ?? null,
                        'city' => $data[$fields['city']] ?? null,
                        'state' => $data[$fields['state']] ?? null,
                        'country' => $data[$fields['country']] ?? null,
                    ];
                }

                if ($address) {
                    $address->update($addressData);
                }
            }

            // Update Qualifications
            for ($i = 1; $i <= 3; $i++) {
                $qualification = $user->qualifications->where('sequence', $i)->first();
                $institution = $data["institution_$i"] ?? null;
                $degree = $data["qualification_$i"] ?? null;
                $year = $data["year_obtained_$i"] ?? null;

                if ($qualification && $institution && $degree && $year) {
                    $qualification->update([
                        'institution' => $institution,
                        'degree_or_qualification' => $degree,
                        'year_obtained' => $year,
                    ]);
                }
            }

            // Update Healthcare Service
            if ($user->healthcareService) {
                $user->healthcareService->update([
                    'professional_indemnity_type' => $data['professional_indemnity_type'] ?? $user->healthcareService->professional_indemnity_type,
                    'employment_status' => $data['employment_status'] ?? $user->healthcareService->employment_status,
                    'specialty_area' => $data['specialty_area'] ?? $user->healthcareService->specialty_area,
                    'cover_type' => $data['cover_type'] ?? $user->healthcareService->cover_type,
                    'locum_practice_location' => $data['locum_practice_location'] ?? $user->healthcareService->locum_practice_location,
                    'service_type' => $data['service_type'] ?? $user->healthcareService->service_type,
                    'practice_area' => $data['practice_area'] ?? $user->healthcareService->practice_area,
                ]);
            }

            // Update Policy Pricing
            if ($user->policyPricing) {
                $user->policyPricing->update([
                    'policy_start_date' => $data['policy_start_date'] ?? $user->policyPricing->policy_start_date,
                    'policy_expiry_date' => $data['policy_expiry_date'] ?? $user->policyPricing->policy_expiry_date,
                    'liability_limit' => $data['liability_limit'] ?? $user->policyPricing->liability_limit,
                    'base_premium' => $data['displayBasePremium'] ?? $user->policyPricing->base_premium,
                    'gross_premium' => $data['displayGrossPremium'] ?? $user->policyPricing->gross_premium,
                    'locum_addon' => $data['displayLocumAddon'] ?? $user->policyPricing->locum_addon,
                    'locum_extension' => $data['locum_extension'] ?? $user->policyPricing->locum_extension,
                    'sst' => $data['displaySST'] ?? $user->policyPricing->sst,
                    'stamp_duty' => $data['displayStampDuty'] ?? $user->policyPricing->stamp_duty,
                    'total_payable' => $data['displayTotalPayable'] ?? $user->policyPricing->total_payable,
                ]);
            }

            // Update Risk Management
            if ($user->riskManagement) {
                $user->riskManagement->update([
                    'medical_records' => ($data['medical_records'] ?? 'no') === 'yes',
                    'informed_consent' => ($data['informed_consent'] ?? 'no') === 'yes',
                    'adverse_incidents' => ($data['adverse_incidents'] ?? 'no') === 'yes',
                    'sterilisation_facilities' => ($data['sterilisation_facilities'] ?? 'no') === 'yes',
                ]);
            }

            // Update Insurance History
            if ($user->insuranceHistory) {
                $user->insuranceHistory->update([
                    'current_insurance' => ($data['current_insurance'] ?? 'no') === 'yes',
                    'insurer_name' => $data['insurer_name'] ?? $user->insuranceHistory->insurer_name,
                    'period_of_insurance' => $data['period_of_insurance'] ?? $user->insuranceHistory->period_of_insurance,
                    'policy_limit_myr' => $data['policy_limit_myr'] ?? $user->insuranceHistory->policy_limit_myr,
                    'excess_myr' => $data['excess_myr'] ?? $user->insuranceHistory->excess_myr,
                    'retroactive_date' => $data['retroactive_date'] ?? $user->insuranceHistory->retroactive_date,
                    'previous_claims' => ($data['previous_claims'] ?? 'no') === 'yes',
                    'claims_details' => $data['claims_details'] ?? $user->insuranceHistory->claims_details,
                ]);
            }

            // Update Claims Experience
            if ($user->claimsExperience) {
                $user->claimsExperience->update([
                    'claims_made' => ($data['claims_made'] ?? 'no') === 'yes',
                    'aware_of_errors' => ($data['aware_of_errors'] ?? 'no') === 'yes',
                    'disciplinary_action' => ($data['disciplinary_action'] ?? 'no') === 'yes',
                    'claim_date_of_claim' => $data['claim_date_of_claim'] ?? $user->claimsExperience->claim_date_of_claim,
                    'claim_notified_date' => $data['claim_notified_date'] ?? $user->claimsExperience->claim_notified_date,
                    'claim_claimant_name' => $data['claim_claimant_name'] ?? $user->claimsExperience->claim_claimant_name,
                    'claim_allegations' => $data['claim_allegations'] ?? $user->claimsExperience->claim_allegations,
                    'claim_amount_claimed' => $data['claim_amount_claimed'] ?? $user->claimsExperience->claim_amount_claimed,
                    'claim_status' => $data['claim_status'] ?? $user->claimsExperience->claim_status,
                    'claim_amounts_paid' => $data['claim_amounts_paid'] ?? $user->claimsExperience->claim_amounts_paid,
                ]);
            }

            // Update Contact
            if ($user->applicantContact) {
                $user->applicantContact->update([
                    'contact_no' => $data['contact_no'] ?? $user->applicantContact->contact_no,
                    'email_address' => $data['email_address'] ?? $user->applicantContact->email_address,
                ]);
            }

            // Update Policy Application declarations
            $policyApplication->update([
                'agree_data_protection' => ($data['agree_declaration'] ?? 'no') === 'yes',
                'agree_declaration' => ($data['agree_declaration_final'] ?? 'no') === 'yes',
                'signature_data' => $data['signature'] ?? $policyApplication->signature_data,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Application updated successfully!',
                'redirect_url' => route('for-your-action.show', $policyApplication->id)
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update policy application', [
                'policy_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update application. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a policy application (only for rejected and submitted status)
     */
    public function destroy($id)
    {
        $policyApplication = PolicyApplication::findOrFail($id);

        DB::beginTransaction();

        try {
            $userId = $policyApplication->user_id;
            $policyApplication->delete();

            DB::commit();

            return redirect()
                ->route('for-your-action')
                ->with('success', 'Policy application deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to delete policy application', [
                'policy_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to delete application. Please try again.');
        }
    }

    /**
     * Export policy application as PDF
     */
    public function exportPdf($id)
    {
        // Find the policy application with all related data
        $policyApplication = PolicyApplication::with([
            'user.applicantProfile',
            'user.qualifications',
            'user.addresses',
            'user.applicantContact',
            'user.healthcareService',
            'user.policyPricing',
            'user.riskManagement',
            'user.insuranceHistory',
            'user.claimsExperience',
        ])
        ->where('id', $id)
        ->firstOrFail();

        // Extract related data for easier access in the view
        $profile = $policyApplication->user->applicantProfile;
        $qualifications = $policyApplication->user->qualifications;
        $addresses = $policyApplication->user->addresses;
        $contact = $policyApplication->user->applicantContact;
        $healthcare = $policyApplication->user->healthcareService;
        $pricing = $policyApplication->user->policyPricing;
        $risk = $policyApplication->user->riskManagement;
        $insurance = $policyApplication->user->insuranceHistory;
        $claims = $policyApplication->user->claimsExperience;

        // Generate PDF
        $pdf = Pdf::loadView('pdf.policy-application', compact(
            'policyApplication',
            'profile',
            'qualifications',
            'addresses',
            'contact',
            'healthcare',
            'pricing',
            'risk',
            'insurance',
            'claims'
        ));

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $filename = 'Policy_Application_' . ($policyApplication->reference_number ?? 'MRCM#' . $policyApplication->id) . '.pdf';

        // Stream the PDF (display in browser)
        return $pdf->stream($filename);
    }

    /**
     * Upload tax receipt and policy schedule documents
     */
    public function uploadDocuments(Request $request, $id)
    {
        $policyApplication = PolicyApplication::where('id', $id)->firstOrFail();

        // Validate request
        $validated = $request->validate([
            'tax_receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
            'policy_schedule' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        DB::beginTransaction();

        try {
            // Handle Tax Receipt Upload
            if ($request->hasFile('tax_receipt')) {
                // Delete old file if exists
                if ($policyApplication->tax_receipt_path) {
                    Storage::disk('public')->delete($policyApplication->tax_receipt_path);
                }

                // Store new file
                $taxReceiptPath = $request->file('tax_receipt')->store('tax-receipts', 'public');
                $policyApplication->tax_receipt_path = $taxReceiptPath;
                
                Log::info('Tax receipt uploaded', [
                    'policy_id' => $id,
                    'path' => $taxReceiptPath,
                ]);
            }

            // Handle Policy Schedule Upload
            if ($request->hasFile('policy_schedule')) {
                // Delete old file if exists
                if ($policyApplication->policy_schedule_path) {
                    Storage::disk('public')->delete($policyApplication->policy_schedule_path);
                }

                // Store new file
                $policySchedulePath = $request->file('policy_schedule')->store('policy-schedules', 'public');
                $policyApplication->policy_schedule_path = $policySchedulePath;
                
                Log::info('Policy schedule uploaded', [
                    'policy_id' => $id,
                    'path' => $policySchedulePath,
                ]);
            }

            $policyApplication->save();

            DB::commit();

            return redirect()
                ->route('for-your-action.show', $id)
                ->with('success', 'Documents uploaded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to upload documents', [
                'policy_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to upload documents. Please try again.');
        }
    }

    /**
     * Generate a unique reference number for the application
     * Format: MRCM#YY-XXXX where YY is policy year (e.g., 26 for 2025-2026 policy)
     * 
     * @param int $userId
     * @return string
     */
    private function generateReferenceNumber($userId)
    {
        // Get the last 2 digits of current year + 1 for policy year
        // For 2025, policy year is 2025-2026, so we use 26
        $policyYear = substr((string)(date('Y') + 1), -2);
        
        return 'MRCM#' . $policyYear . '-' . str_pad($userId, 4, '0', STR_PAD_LEFT);
    }
}
