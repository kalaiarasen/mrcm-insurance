<?php

namespace App\Http\Controllers;

use App\Models\PolicyApplication;
use App\Mail\SendToUnderwriting;
use App\Mail\PolicyApprovedMail;
use App\Mail\PolicySentToUnderwritingClientMail;
use App\Mail\PolicyActiveMail;
use App\Mail\PolicyRejectedMail;
use App\Mail\PolicyCancelledMail;
use App\Exports\PolicyApplicationsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class YourActionController extends Controller
{
    /**
     * Display the for-your-action page with policy statistics and pending policies.
     */
    public function index(Request $request)
    {
        // Restrict Client role from accessing this page
        if (auth()->user()->hasRole('Client')) {
            abort(403, 'Unauthorized access.');
        }

        if ($request->ajax()) {
            $query = PolicyApplication::with('user', 'user.applicantContact', 'user.healthcareService', 'policyPricing');

            // Filter by agent_id if user is an agent
            if (Auth::user()->hasRole('Agent')) {
                $query->whereHas('user', function($q) {
                    $q->where('agent_id', Auth::id());
                });
            }

            // Apply date range filter
            if ($request->filled('start_date')) {
                $query->whereDate('updated_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('updated_at', '<=', $request->end_date);
            }

            // Apply policy type filter
            if ($request->filled('policy_type')) {
                $query->whereHas('user.healthcareService', function($q) use ($request) {
                    $q->where('professional_indemnity_type', $request->policy_type);
                });
            }

            // Apply status filter
            if ($request->filled('status')) {
                $query->where('admin_status', $request->status);
            }

            // Apply agent filter
            if ($request->filled('agent_id')) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('agent_id', $request->agent_id);
                });
            }

            // Apply expiry year filter
            if ($request->filled('expiry_year')) {
                $selectedYear = $request->expiry_year;
                $query->whereHas('policyPricing', function($q) use ($selectedYear) {
                    $q->whereYear('policy_expiry_date', $selectedYear);
                })
                // Exclude users who have a policy expiring in the year after the selected year
                ->whereNotExists(function($subQuery) use ($selectedYear) {
                    $subQuery->select(DB::raw(1))
                        ->from('policy_applications as pa2')
                        ->whereColumn('pa2.user_id', 'policy_applications.user_id')
                        ->whereColumn('pa2.id', '!=', 'policy_applications.id')
                        ->whereIn('pa2.admin_status', ['active', 'new_case', 'new_renewal', 'not_paid', 'paid', 'sent_uw'])
                        ->whereExists(function($pricingSubQuery) use ($selectedYear) {
                            $pricingSubQuery->select(DB::raw(1))
                                ->from('policy_pricings')
                                ->whereColumn('policy_pricings.policy_application_id', 'pa2.id')
                                ->whereYear('policy_pricings.policy_expiry_date', $selectedYear + 1);
                        });
                });
            }

            // Apply card filter (for clickable analytics cards)
            if ($request->filled('card_filter')) {
                switch ($request->card_filter) {
                    case 'active_last_30':
                        $query->where('admin_status', 'active')
                              ->where('activated_at', '>=', now()->subDays(30));
                        break;
                    case 'expiring_soon':
                        // Only show policies expiring in next 3 months
                        // EXCLUDE users who already have a policy (active OR submitted) for next year
                        $query->where('admin_status', 'active')
                              ->whereHas('policyPricing', function($q) {
                                  $q->whereBetween('policy_expiry_date', [
                                      now()->toDateString(),
                                      now()->addMonths(3)->toDateString()
                                  ]);
                              })
                              // Exclude users who have another policy expiring next year
                              ->whereNotExists(function($subQuery) {
                                  $subQuery->select(DB::raw(1))
                                      ->from('policy_applications as pa2')
                                      ->whereColumn('pa2.user_id', 'policy_applications.user_id')
                                      ->whereColumn('pa2.id', '!=', 'policy_applications.id')
                                      ->whereIn('pa2.admin_status', ['active', 'new_case', 'new_renewal', 'not_paid', 'paid', 'sent_uw'])
                                      ->whereExists(function($pricingSubQuery) {
                                          $pricingSubQuery->select(DB::raw(1))
                                              ->from('policy_pricings')
                                              ->whereColumn('policy_pricings.policy_application_id', 'pa2.id')
                                              ->whereYear('policy_pricings.policy_expiry_date', now()->addYear()->year);
                                      });
                              });
                        break;
                    case 'pending_payment':
                        $query->where('admin_status', 'not_paid');
                        break;
                    case 'sent_uw':
                        $query->where('admin_status', 'sent_uw');
                        break;
                }
            }

            // Calculate total sales for filtered data
            $totalSales = (clone $query)->whereHas('policyPricing', function($q) {
                $q->whereNotNull('total_payable');
            })->with('policyPricing')->get()->sum(function($policy) {
                return $policy->policyPricing?->total_payable ?? 0;
            });

            return DataTables::of($query)
                ->with('totalSales', $totalSales)
                ->addColumn('policy_id', function ($policy) {
                    return '<small>' . e($policy->reference_number ?? '') . '</small>';
                })
                ->addColumn('date_changed', function ($policy) {
                    $date = $policy->updated_at;
                    
                    return '<small>' . $date->format('d-M-Y') . '<br><span class="text-muted">' . $date->format('h:i A') . '</span></small>';
                })
                ->addColumn('status', function ($policy) {
                    $statusMap = [
                        'new_case' => 'New Case',
                        'new_renewal' => 'New Renewal',
                        'not_paid' => 'Not Paid',
                        'paid' => 'Paid',
                        'sent_uw' => 'Sent UW',
                        'active' => 'Active',
                        'cancelled' => 'Cancelled',
                        'rejected' => 'Rejected',
                    ];
                    
                    $displayStatus = $statusMap[$policy->admin_status] ?? ucfirst($policy->admin_status ?? 'N/A');
                    
                    $badgeClass = match($displayStatus) {
                        'Approved' => 'bg-success',
                        'Submitted' => 'bg-info',
                        'Rejected' => 'bg-danger',
                        'Active' => 'bg-success',
                        'Processing' => 'bg-warning',
                        'Not Paid' => 'bg-danger',
                        'Send UW', 'Sent UW' => 'bg-warning',
                        'Cancelled' => 'bg-secondary',
                        'New', 'New Case' => 'bg-secondary',
                        'Paid' => 'bg-success',
                        'New Renewal' => 'bg-info',
                        default => 'bg-secondary'
                    };
                    
                    return '<span class="badge ' . $badgeClass . '">' . e($displayStatus) . '</span>';
                })
                ->addColumn('expiry_date', function ($policy) {
                    $expiryDate = $policy->policyPricing?->policy_expiry_date ?? 'N/A';
                    return $expiryDate === 'N/A' ? 'N/A' : \Carbon\Carbon::parse($expiryDate)->format('d-M-Y');
                })
                ->addColumn('name', function ($policy) {
                    $userId = $policy->user?->id ?? 0;
                    $name = '<a href="javascript:void(0)" class="text-primary fw-bold" onclick="showPolicyHistory(' . $userId . ')">' 
                          . e($policy->user?->name ?? 'Unknown') 
                          . '</a>';
                    $email = '<br><small class="text-muted">' . e($policy->user?->email ?? 'N/A') . '</small>';
                    $phone = $policy->user?->contact_no ?? $policy->user?->applicantContact?->contact_no ?? 'N/A';
                    $phoneDisplay = '<br><small class="text-muted"><i class="fa fa-phone me-1"></i>' . e($phone) . '</small>';
                    return $name . $email . $phoneDisplay;
                })
                ->addColumn('class', function ($policy) {
                    $healthcareService = $policy->user?->healthcareService;
                    $pricing = $policy->policyPricing;
                    
                    // Try practice_area first, fallback to service_type, then cover_type
                    $classValue = $healthcareService?->practice_area 
                               ?? $healthcareService?->service_type 
                               ?? $healthcareService?->cover_type;
                    
                    // Comprehensive mapping for practice_area, service_type, and cover_type values
                    $classMap = [
                        // Practice Area values
                        'general_practice' => 'General Practice',
                        'general_practice_with_specialized_procedures' => 'General Practice with Specialized Procedures',
                        'core_services' => 'Core Services',
                        'core_services_with_procedures' => 'Core Services with Procedures',
                        'general_practitioner_with_obstetrics' => 'General Practitioner with Obstetrics',
                        'cosmetic_aesthetic_non_invasive' => 'Cosmetic & Aesthetic – Non-Invasive',
                        'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic & Aesthetic – Non-Surgical Invasive',
                        'office_clinical_orthopaedics' => 'Office / Clinical Orthopaedics',
                        'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A.)',
                        'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic and Aesthetic (Surgical, Invasive)',
                        'general_dental_practice' => 'General Dental Practitioner',
                        'general_dental_practitioners_accredited_specialised_procedures' => 'General Dental Practitioners, practising accredited specialised procedures',
                        // Service Type values (fallback)
                        'general_practitioner_private_hospital_outpatient' => 'General Practitioner in Private Hospital - Outpatient Services',
                        'general_practitioner_private_hospital_emergency' => 'General Practitioner in Private Hospital – Emergency Department',
                        // Cover Type values (third fallback)
                        'basic_coverage' => 'Basic Coverage',
                        'comprehensive_coverage' => 'Comprehensive Coverage',
                        'premium_coverage' => 'Premium Coverage',
                        'general_dental_practitioners' => 'General Dentist Practice, practicing accredited specialised procedures',
                    ];
                    
                    $classDisplay = e($classMap[$classValue] ?? 'N/A');
                    
                    // Add locum extension indicator
                    if ($pricing && $pricing->locum_extension) {
                        $classDisplay .= ' (with locum extension)';
                    }
                    
                    return $classDisplay;
                })
                ->addColumn('amount', function ($policy) {
                    $amount = $policy->policyPricing?->total_payable;
                    if (is_numeric($amount)) {
                        return 'RM' . number_format($amount, 2);
                    } elseif ($amount === null) {
                        return 'null';
                    }
                    return e($amount);
                })
                ->addColumn('agent', function ($policy) {
                    $agentId = $policy->user?->agent_id;
                    if ($agentId) {
                        $agent = \App\Models\User::find($agentId);
                        return e($agent?->name ?? '-');
                    }
                    return '-';
                })
                ->addColumn('action', function ($policy) {
                    $viewUrl = route('for-your-action.show', $policy->id);
                    $deleteUrl = route('for-your-action.destroy', $policy->id);
                    
                    $html = '<ul class="action">';
                    
                    // Only show view and delete buttons for non-agent users
                    if (!Auth::user()->hasRole('Agent')) {
                        $html .= '<li class="view me-2">';
                        $html .= '<a href="' . $viewUrl . '" title="View Details">';
                        $html .= '<i class="fa-regular fa-eye"></i>';
                        $html .= '</a>';
                        $html .= '</li>';
                        
                        $html .= '<li class="delete">';
                        $html .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this policy application?\');">';
                        $html .= csrf_field();
                        $html .= method_field('DELETE');
                        $html .= '<button type="submit" class="border-0 bg-transparent p-0" title="Delete">';
                        $html .= '<i class="fa-regular fa-trash-can"></i>';
                        $html .= '</button>';
                        $html .= '</form>';
                        $html .= '</li>';
                    } else {
                        // Agents see nothing in action column - only can view the list
                        $html .= '<li><span class="text-muted">View only</span></li>';
                    }
                    
                    $html .= '</ul>';
                    return $html;
                })
                ->filterColumn('status', function($query, $keyword) {
                    // Map status keywords to admin_status values for searching
                    $statusMap = [
                        'new case' => 'new_case',
                        'new renewal' => 'new_renewal',
                        'not paid' => 'not_paid',
                        'paid' => 'paid',
                        'sent uw' => 'sent_uw',
                        'send uw' => 'sent_uw',
                        'active' => 'active',
                        'cancelled' => 'cancelled',
                        'rejected' => 'rejected',
                    ];
                    
                    $searchTerm = strtolower($keyword);
                    $adminStatus = $statusMap[$searchTerm] ?? $searchTerm;
                    
                    $query->where(function($q) use ($adminStatus, $keyword) {
                        $q->where('admin_status', 'like', "%{$adminStatus}%")
                          ->orWhere('status', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('policy_id', function($query, $keyword) {
                    $query->where('reference_number', 'like', "%{$keyword}%");
                })
                ->filterColumn('name', function($query, $keyword) {
                    $query->whereHas('user', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                          ->orWhere('email', 'like', "%{$keyword}%")
                          ->orWhere('contact_no', 'like', "%{$keyword}%")
                          ->orWhereHas('applicantContact', function($subQ) use ($keyword) {
                              $subQ->where('contact_no', 'like', "%{$keyword}%");
                          });
                    });
                })
                ->filterColumn('expiry_date', function($query, $keyword) {
                    $query->whereHas('policyPricing', function($q) use ($keyword) {
                        $q->where('policy_expiry_date', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('class', function($query, $keyword) {
                    $query->whereHas('user.healthcareService', function($q) use ($keyword) {
                        $q->where('practice_area', 'like', "%{$keyword}%")
                          ->orWhere('service_type', 'like', "%{$keyword}%")
                          ->orWhere('cover_type', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('amount', function($query, $keyword) {
                    $query->whereHas('policyPricing', function($q) use ($keyword) {
                        $q->where('total_payable', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('agent', function($query, $keyword) {
                    $query->whereHas('user', function($q) use ($keyword) {
                        $q->whereHas('agent', function($subQ) use ($keyword) {
                            $subQ->where('name', 'like', "%{$keyword}%");
                        });
                    });
                })
                ->orderColumn('policy_id', function ($query, $order) {
                    $query->orderBy('reference_number', $order);
                })
                ->orderColumn('date_changed', function ($query, $order) {
                    $query->orderBy('updated_at', $order);
                })
                ->rawColumns(['policy_id', 'date_changed', 'status', 'name', 'agent', 'action'])
                ->make(true);
        }

        // Count policies by admin_status (show all, no is_used filter)
        $baseQuery = PolicyApplication::query();
        
        // Filter by agent_id if user is an agent
        if (Auth::user()->hasRole('Agent')) {
            $baseQuery->whereHas('user', function($q) {
                $q->where('agent_id', Auth::id());
            });
        }
        
        // Calculate statistics for new clickable analytics cards
        $activeLast30Days = (clone $baseQuery)
            ->where('admin_status', 'active')
            ->where('activated_at', '>=', now()->subDays(30))
            ->count();

        // Expiring in next 3 months (excluding users with next year policies)
        $expiringNext3Months = (clone $baseQuery)
            ->where('admin_status', 'active')
            ->whereHas('policyPricing', function($q) {
                $q->whereBetween('policy_expiry_date', [
                    now()->toDateString(),
                    now()->addMonths(3)->toDateString()
                ]);
            })
            // Exclude users who have another policy expiring next year
            ->whereNotExists(function($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('policy_applications as pa2')
                    ->whereColumn('pa2.user_id', 'policy_applications.user_id')
                    ->whereColumn('pa2.id', '!=', 'policy_applications.id')
                    ->whereIn('pa2.admin_status', ['active', 'new_case', 'new_renewal', 'not_paid', 'paid', 'sent_uw'])
                    ->whereExists(function($pricingSubQuery) {
                        $pricingSubQuery->select(DB::raw(1))
                            ->from('policy_pricings')
                            ->whereColumn('policy_pricings.policy_application_id', 'pa2.id')
                            ->whereYear('policy_pricings.policy_expiry_date', now()->addYear()->year);
                    });
            })
            ->count();

        $pendingPayment = (clone $baseQuery)
            ->where('admin_status', 'not_paid')
            ->count();

        $sentToUnderwriting = (clone $baseQuery)
            ->where('admin_status', 'sent_uw')
            ->count();

        return view('pages.your-action.index', compact(
            'activeLast30Days',
            'expiringNext3Months',
            'pendingPayment',
            'sentToUnderwriting'
        ));
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
            'policyPricing',
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
        $validationRules = [
            'status' => 'required|in:new,approved,send_uw,active,processing,rejected,cancelled',
            'remarks' => 'nullable|string|max:5000',
        ];
        
        // Add certificate document validation when status is changing to active
        if ($request->input('status') === 'active') {
            $validationRules['certificate_document'] = 'required|file|mimes:pdf|max:10240'; // 10MB max
        }
        
        $validated = $request->validate($validationRules);

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
                    
                    // Generate sequential reference number when approved
                    if (!$policyApplication->reference_number) {
                        $policyApplication->reference_number = $this->generateReferenceNumber($policyApplication);
                    }
                    break;

                case 'active':
                    // When policy becomes active:
                    // Customer Status → active
                    // Admin Status → active
                    $policyApplication->customer_status = 'active';
                    $policyApplication->admin_status = 'active';
                    $policyApplication->activated_at = now();
                    
                    // Generate reference number if not already generated (fallback)
                    if (!$policyApplication->reference_number) {
                        $policyApplication->reference_number = $this->generateReferenceNumber($policyApplication);
                    }
                    
                    // Handle Certificate of Insurance (CI) document upload
                    if ($request->hasFile('certificate_document')) {
                        $file = $request->file('certificate_document');
                        
                        // Generate unique filename (sanitize reference number for URL safety)
                        $sanitizedRef = str_replace('#', '_', $policyApplication->reference_number);
                        $filename = 'CI_' . $sanitizedRef . '_' . time() . '.pdf';
                        
                        // Store file in public/certificates directory
                        $path = $file->storeAs('certificates', $filename, 'public');
                        
                        // Save path to database
                        $policyApplication->certificate_document = $path;
                        
                        Log::info('Certificate document uploaded', [
                            'policy_id' => $id,
                            'reference' => $policyApplication->reference_number,
                            'file_path' => $path,
                        ]);
                    }
                    break;
                    
                case 'send_uw':
                    // When sent to underwriter:
                    // Customer Status → processing
                    // Admin Status → sent_uw
                    $policyApplication->customer_status = 'processing';
                    $policyApplication->admin_status = 'sent_uw';
                    $policyApplication->sent_to_underwriter_at = now();
                    if (!$policyApplication->reference_number) {
                        $policyApplication->reference_number = $this->generateReferenceNumber($policyApplication);
                    }
                    
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
                    
                    // Clear payment data
                    // Delete payment document file if exists
                    if ($policyApplication->payment_document) {
                        Storage::disk('public')->delete($policyApplication->payment_document);
                    }
                    
                    // Clear all payment-related fields
                    $policyApplication->payment_document = null;
                    $policyApplication->payment_method = null;
                    $policyApplication->name_on_card = null;
                    $policyApplication->nric_no = null;
                    $policyApplication->card_no = null;
                    $policyApplication->card_issuing_bank = null;
                    $policyApplication->card_type = null;
                    $policyApplication->expiry_month = null;
                    $policyApplication->expiry_year = null;
                    $policyApplication->relationship = null;
                    $policyApplication->authorize_payment = null;
                    $policyApplication->payment_received_at = null;
                    
                    Log::info('Payment data cleared for rejected policy', [
                        'policy_id' => $id,
                        'reference' => $policyApplication->reference_number,
                    ]);
                    break;
                    
                case 'cancelled':
                    $policyApplication->customer_status = 'cancelled';
                    $policyApplication->admin_status = 'cancelled';
                    break;
            }

            $policyApplication->save();

            // Update user application status
            $policyApplication->user->update([
                'application_status' => $newStatus,
            ]);

            DB::commit();

            // Send email notifications to client based on status change
            Log::info('Email notification check', [
                'policy_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'status_changed' => $oldStatus !== $newStatus,
            ]);
            
            if ($oldStatus !== $newStatus) {
                try {
                    $policyApplication->load('user.applicantProfile');
                    
                    Log::info('Attempting to send email', [
                        'policy_id' => $id,
                        'status' => $newStatus,
                        'user_email' => $policyApplication->user->email,
                    ]);
                    
                    switch ($newStatus) {
                        case 'approved':
                            Mail::to($policyApplication->user->email)->send(new PolicyApprovedMail($policyApplication));
                            Log::info('Policy approved email sent', ['policy_id' => $id]);
                            break;
                            
                        case 'send_uw':
                            Mail::to($policyApplication->user->email)->send(new PolicySentToUnderwritingClientMail($policyApplication));
                            Log::info('Policy sent to underwriting client email sent', ['policy_id' => $id]);
                            break;
                            
                        case 'active':
                            Mail::to($policyApplication->user->email)->send(new PolicyActiveMail($policyApplication));
                            Log::info('Policy active email sent', ['policy_id' => $id]);
                            break;
                            
                        case 'rejected':
                            Mail::to($policyApplication->user->email)->send(new PolicyRejectedMail($policyApplication));
                            Log::info('Policy rejected email sent', ['policy_id' => $id]);
                            break;
                            
                        case 'cancelled':
                            Mail::to($policyApplication->user->email)->send(new PolicyCancelledMail($policyApplication));
                            Log::info('Policy cancelled email sent', ['policy_id' => $id]);
                            break;
                    }
                } catch (\Exception $mailException) {
                    Log::warning('Failed to send policy status email', [
                        'policy_id' => $id,
                        'status' => $newStatus,
                        'error' => $mailException->getMessage(),
                    ]);
                }
            } else {
                Log::info('Email skipped - status unchanged', [
                    'policy_id' => $id,
                    'status' => $newStatus,
                ]);
            }

            $successMessage = "Application status updated from '{$oldStatus}' to '{$newStatus}' successfully!";
            if ($newStatus === 'send_uw') {
                $successMessage .= " Email sent to underwriting department.";
            }

            return redirect()
                ->back()
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
            'policyPricing',
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

            // Update User - only name and contact_no, NOT email (email is unique and shouldn't change)
            // Note: full_name already includes title prefix, don't add it again
            $applicantFullName = $data['full_name'] ?? null;
            
            $user->update([
                'name' => $applicantFullName ?? $user->name,
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
            $pricing = $policyApplication->policyPricing;
            if ($pricing) {
                $pricing->update([
                    'policy_start_date' => $data['policy_start_date'] ?? $pricing->policy_start_date,
                    'policy_expiry_date' => $data['policy_expiry_date'] ?? $pricing->policy_expiry_date,
                    'liability_limit' => $data['liability_limit'] ?? $pricing->liability_limit,
                    'base_premium' => $data['displayBasePremium'] ?? $pricing->base_premium,
                    'loading_percentage' => $data['displayLoadingPercentage'] ?? $pricing->loading_percentage,
                    'loading_amount' => $data['displayLoadingAmount'] ?? $pricing->loading_amount,
                    'gross_premium' => $data['displayGrossPremium'] ?? $pricing->gross_premium,
                    'locum_addon' => $data['displayLocumAddon'] ?? $pricing->locum_addon,
                    'locum_extension' => $data['locum_extension'] ?? $pricing->locum_extension,
                    'discount_percentage' => $data['displayDiscountPercentage'] ?? $pricing->discount_percentage,
                    'discount_amount' => $data['displayDiscountAmount'] ?? $pricing->discount_amount,
                    'voucher_code' => $data['voucher_code'] ?? $pricing->voucher_code,
                    'sst' => $data['displaySST'] ?? $pricing->sst,
                    'stamp_duty' => $data['displayStampDuty'] ?? $pricing->stamp_duty,
                    'total_payable' => $data['displayTotalPayable'] ?? $pricing->total_payable,
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

            // DON'T update Policy Application declarations (agree_data_protection, agree_declaration, signature_data)
            // These remain as originally submitted by the user and should not be changed during admin edit

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
            'policyPricing',
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
        $pricing = $policyApplication->policyPricing;
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
     * Upload payment document and update status to paid (Admin version)
     */
    public function uploadPayment(Request $request, $id)
    {
        $policyApplication = PolicyApplication::where('id', $id)
            ->firstOrFail();

        // Validate based on payment type
        $paymentType = $request->input('payment_type', 'proof');

        if ($paymentType === 'proof') {
            // Validate the payment document
            $request->validate([
                'payment_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
                'payment_type' => 'required|in:proof,credit_card',
            ]);
        } else {
            // Validate credit card details
            $request->validate([
                'name_on_card' => 'nullable|string|max:100',
                'nric_no' => 'nullable|string|max:50',
                'card_no' => 'nullable|string|max:50',
                'card_issuing_bank' => 'nullable|string|max:100',
                'card_type' => 'nullable|array',
                'expiry_month' => 'nullable|string',
                'expiry_year' => 'nullable|string',
                'relationship' => 'nullable|array',
                'authorize_payment' => 'required|accepted',
                'payment_type' => 'required|in:proof,credit_card',
            ], [
                'authorize_payment.required' => 'You must authorize the payment.',
                'authorize_payment.accepted' => 'You must agree to the authorization terms.',
            ]);
        }

        DB::beginTransaction();

        try {
            if ($paymentType === 'proof' && $request->hasFile('payment_document')) {
                // Store the payment document
                $file = $request->file('payment_document');
                $filename = 'payment_' . $policyApplication->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('payment-documents', $filename, 'public');

                // Delete old payment document if exists
                if ($policyApplication->payment_document) {
                    Storage::disk('public')->delete($policyApplication->payment_document);
                }

                $policyApplication->payment_document = $path;
                $policyApplication->payment_method = 'proof';
                
                // Clear credit card data when switching to proof payment
                $policyApplication->name_on_card = null;
                $policyApplication->nric_no = null;
                $policyApplication->card_no = null;
                $policyApplication->card_issuing_bank = null;
                $policyApplication->card_type = null;
                $policyApplication->expiry_month = null;
                $policyApplication->expiry_year = null;
                $policyApplication->relationship = null;
                $policyApplication->authorize_payment = null;
            } elseif ($paymentType === 'credit_card') {
                // Store credit card info
                $policyApplication->payment_method = 'credit_card';
                $policyApplication->name_on_card = $request->input('name_on_card');
                $policyApplication->nric_no = $request->input('nric_no');
                $policyApplication->card_no = $request->input('card_no');
                $policyApplication->card_issuing_bank = $request->input('card_issuing_bank');
                $policyApplication->card_type = $request->input('card_type');
                $policyApplication->expiry_month = $request->input('expiry_month');
                $policyApplication->expiry_year = $request->input('expiry_year');
                $policyApplication->relationship = $request->input('relationship');
                $policyApplication->authorize_payment = $request->input('authorize_payment') ? true : false;
                // Note: Card details will be submitted to Great Eastern for processing
            }

            // Update policy application with payment status
            $policyApplication->customer_status = 'paid';
            $policyApplication->admin_status = 'paid';
            $policyApplication->payment_received_at = now();
            $policyApplication->save();

            DB::commit();

            $message = $paymentType === 'proof'
                ? 'Payment document uploaded successfully! Policy status has been updated to Paid.'
                : 'Credit card payment information saved successfully! Policy status has been updated to Paid.';

            return redirect()
                ->route('for-your-action.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to process payment (Admin)', [
                'policy_id' => $id,
                'payment_type' => $paymentType,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to process payment. Please try again.');
        }
    }

    /**
     * Export policy applications to Excel
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $policyType = $request->input('policy_type');
        $status = $request->input('status');
        $filterAgentId = $request->input('agent_id'); // Agent filter from dropdown
        $cardFilter = $request->input('card_filter'); // Card filter from clickable analytics
        $expiryYear = $request->input('expiry_year'); // Expiry year filter
        
        // Pass agent ID if user is an agent (their own ID) or filtered agent ID
        $agentId = Auth::user()->hasRole('Agent') ? Auth::id() : $filterAgentId;

        $fileName = 'policy_applications_' . date('Y-m-d_His') . '.xlsx';

        try {
            return Excel::download(
                new PolicyApplicationsExport($startDate, $endDate, $policyType, $status, $agentId, $cardFilter, $expiryYear),
                $fileName
            );
        } catch (\Exception $e) {
            Log::error('Failed to export policy applications', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to export data. Please try again.');
        }
    }

    /**
     * Generate sequential reference number that resets annually based on policy year
     * Format: MRCM26-0001, MRCM26-0002, etc.
     * Generated when admin approves the application
     * Resets based on policy_start_date from policy_pricings table
     * 
     * @param PolicyApplication $policyApplication
     * @return string
     */
    private function generateReferenceNumber(PolicyApplication $policyApplication)
    {
        // Get policy start date from policy_pricings table
        $policyPricing = $policyApplication->policyPricing;
        
        if (!$policyPricing || !$policyPricing->policy_start_date) {
            // Fallback: use current year + 1 if policy_start_date not available
            $policyYear = substr((string)(date('Y') + 1), -2);
        } else {
            // Extract year from policy_start_date and get last 2 digits
            $startYear = date('Y', strtotime($policyPricing->policy_start_date));
            $policyYear = substr((string)$startYear, -2);
        }
        
        // Get the highest reference number for this policy year (e.g., MRCM26-XXXX)
        // Query based on policy year in the reference number
        $lastPolicy = PolicyApplication::whereNotNull('reference_number')
            ->where('reference_number', 'LIKE', 'MRCM' . $policyYear . '-%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(reference_number, "-", -1) AS UNSIGNED) DESC')
            ->first();
        
        if ($lastPolicy && preg_match('/MRCM(\d{2})-(\d{4})/', $lastPolicy->reference_number, $matches)) {
            $nextNumber = intval($matches[2]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return 'MRCM' . $policyYear . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Reupload Certificate of Insurance (CI) document
     */
    public function reuploadCI(Request $request, $id)
    {
        $policyApplication = PolicyApplication::findOrFail($id);

        // Validate the uploaded file
        $request->validate([
            'certificate_document' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        DB::beginTransaction();

        try {
            // Delete old certificate document if exists
            if ($policyApplication->certificate_document) {
                Storage::disk('public')->delete($policyApplication->certificate_document);
                Log::info('Old CI document deleted', [
                    'policy_id' => $id,
                    'old_file' => $policyApplication->certificate_document,
                ]);
            }

            // Upload new certificate document
            $file = $request->file('certificate_document');
            $sanitizedRef = str_replace('#', '_', $policyApplication->reference_number ?? 'TEMP');
            $filename = 'CI_' . $sanitizedRef . '_' . time() . '.pdf';
            $path = $file->storeAs('certificates', $filename, 'public');

            // Update policy application
            $policyApplication->certificate_document = $path;
            $policyApplication->save();

            DB::commit();

            Log::info('CI document reuploaded successfully', [
                'policy_id' => $id,
                'new_file' => $path,
                'uploaded_by' => Auth::id(),
            ]);

            return redirect()
                ->route('for-your-action.show', $id)
                ->with('success', 'Certificate of Insurance document has been reuploaded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to reupload CI document', [
                'policy_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('for-your-action.show', $id)
                ->with('error', 'Failed to reupload CI document. Please try again.');
        }
    }

    /**
     * Remove Certificate of Insurance (CI) document
     */
    public function removeCI($id)
    {
        $policyApplication = PolicyApplication::findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete certificate document file if exists
            if ($policyApplication->certificate_document) {
                Storage::disk('public')->delete($policyApplication->certificate_document);
                
                Log::info('CI document file deleted', [
                    'policy_id' => $id,
                    'file' => $policyApplication->certificate_document,
                ]);
            }

            // Clear certificate_document field
            $policyApplication->certificate_document = null;
            $policyApplication->save();

            DB::commit();

            Log::info('CI document removed successfully', [
                'policy_id' => $id,
                'removed_by' => Auth::id(),
            ]);

            return redirect()
                ->route('for-your-action.show', $id)
                ->with('success', 'Certificate of Insurance document has been removed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to remove CI document', [
                'policy_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('for-your-action.show', $id)
                ->with('error', 'Failed to remove CI document. Please try again.');
        }
    }
}
