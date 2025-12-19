<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Yajra\DataTables\Facades\DataTables;

class PolicyHolderController extends Controller
{
    /**
     * Display a listing of all policy holders.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['applicantProfile', 'applicantContact'])
                ->orderBy('created_at', 'desc');

            if (Auth::user()->hasRole('Agent')) {
                $query->where('agent_id', Auth::id());
            }

            // Apply date range filter if provided
            if ($request->has('start_date') && $request->get('start_date')) {
                $query->whereDate('created_at', '>=', $request->get('start_date'));
            }

            if ($request->has('end_date') && $request->get('end_date')) {
                $query->whereDate('created_at', '<=', $request->get('end_date'));
            }

            return DataTables::of($query)
                ->addColumn('date', function ($holder) {
                    return '<small>' . $holder->created_at->format('d-M-Y') . '</small>';
                })
                ->addColumn('name', function ($holder) {
                    $title = $holder->applicantProfile?->title ?? '';
                    $fullName = trim($title . ' ' . $holder->name);
                    return '<strong>' . e($fullName) . '</strong>';
                })
                ->addColumn('gender', function ($holder) {
                    return ucwords($holder->applicantProfile?->gender ?? '-');
                })
                ->addColumn('nation_status', function ($holder) {
                    $status = ucwords($holder->applicantProfile?->nationality_status ?? '-');
                    return '<span class="badge bg-info">' . e($status) . '</span>';
                })
                ->addColumn('nric_no', function ($holder) {
                    return $holder->applicantProfile?->nric_number ?? $holder->applicantProfile?->passport_number ?? '-';
                })
                ->addColumn('email', function ($holder) {
                    return e($holder->email);
                })
                ->addColumn('contact_no', function ($holder) {
                    return e($holder->contact_no);
                })
                ->addColumn('action', function ($holder) {
                    $editButton = '';
                    if (!Auth::user()->hasRole('Agent')) {
                        $editButton = '
                            <li class="edit">
                                <a href="#" onclick="editPolicyHolder(' . $holder->id . '); return false;" title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                            </li>';
                    }

                    return '
                        <ul class="action">' .
                            $editButton . '
                            <li class="view">
                                <a href="' . route('policy-holders.show', $holder->id) . '" title="View Details">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </li>
                        </ul>
                    ';
                })
                ->filterColumn('name', function($query, $keyword) {
                    $query->where('users.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('gender', function($query, $keyword) {
                    $query->whereHas('applicantProfile', function($q) use ($keyword) {
                        $q->where('gender', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('nation_status', function($query, $keyword) {
                    $query->whereHas('applicantProfile', function($q) use ($keyword) {
                        $q->where('nationality_status', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('nric_no', function($query, $keyword) {
                    $query->whereHas('applicantProfile', function($q) use ($keyword) {
                        $q->where('nric_number', 'like', "%{$keyword}%")
                          ->orWhere('passport_number', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('email', function($query, $keyword) {
                    $query->where('users.email', 'like', "%{$keyword}%");
                })
                ->filterColumn('contact_no', function($query, $keyword) {
                    $query->where('users.contact_no', 'like', "%{$keyword}%");
                })
                ->rawColumns(['date', 'name', 'nation_status', 'action'])
                ->make(true);
        }

        return view('pages.policy-holder.index');
    }

    /**
     * Show the form for editing a policy holder.
     */
    public function edit(User $user)
    {
        // Verify user has submitted an application (has applicant profile)
        // Load the relationship if not already loaded
        if (!$user->relationLoaded('applicantProfile')) {
            $user->load('applicantProfile');
        }

        if (!$user->applicantProfile) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have an applicant profile'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password_enc,
                'contact_no' => $user->contact_no,
                'loading' => $user->loading,
            ]
        ]);
    }

    /**
     * Update the specified policy holder.
     */
    public function update(Request $request, User $user)
    {
        // Block agents from editing
        if (Auth::user()->hasRole('Agent')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to edit customer information'
            ], 403);
        }

        // Verify user has submitted an application (has applicant profile)
        if (!$user->relationLoaded('applicantProfile')) {
            $user->load('applicantProfile');
        }

        if (!$user->applicantProfile) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have an applicant profile'
            ], 403);
        }

        // Initial validation for basic fields
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'contact_no' => ['required', 'string', 'max:20'],
            'loading' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];

        // Add password validation rules if password change is being attempted
        if ($request->filled('current_password') || $request->filled('new_password') || $request->filled('confirm_password')) {
            $rules['current_password'] = [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    // Check if current password matches the policy holder's password, not the authenticated user
                    if (!\Illuminate\Support\Facades\Hash::check($value, $user->password)) {
                        $fail('The current password is incorrect.');
                    }
                }
            ];
            $rules['new_password'] = [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'different:current_password'
            ];
            $rules['confirm_password'] = ['required', 'same:new_password'];
        }

        $validated = $request->validate($rules);

        // Handle password change if validated
        if (isset($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        // Update basic information
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->contact_no = $validated['contact_no'];
        $user->loading = $validated['loading'] ?? null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Policy holder updated successfully'
        ]);
    }

    /**
     * Display the specified policy holder details.
     */
    public function show(User $user)
    {
        // Filter for agents - only show their assigned clients
        if (Auth::user()->hasRole('Agent')) {
            if ($user->agent_id !== Auth::id()) {
                return redirect()->route('policy-holder')->with('error', 'You do not have permission to view this customer');
            }
        }


        // Load relationships
        if (!$user->relationLoaded('applicantProfile')) {
            $user->load([
                'applicantProfile',
                'applicantContact',
                'policyApplications.policyPricing',
                'policyApplications.healthcareService',
            ]);
        }

        // Get all policy applications for this user (including drafts), ordered by newest first
        $policyApplications = $user->policyApplications()
            ->with(['policyPricing', 'healthcareService'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.policy-holder.show', compact('user', 'policyApplications'));
    }

    /**
     * Display a specific policy application for a policy holder.
     */
    public function showApplication(User $user, $application)
    {
        // Filter for agents - only show their assigned clients
        if (Auth::user()->hasRole('Agent')) {
            if ($user->agent_id !== Auth::id()) {
                abort(403, 'You do not have permission to view this customer');
            }
        }

        // Find the policy application and verify it belongs to this user
        $policyApplication = \App\Models\PolicyApplication::where('id', $application)
            ->where('user_id', $user->id)
            ->with([
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
            ->firstOrFail();

        // Use the same view as for-your-action but in read-only mode from policy holder context
        return view('pages.your-action.show', compact('policyApplication'));
    }

    /**
     * Search for a client by client code (Agent only)
     */
    public function searchByCode(Request $request)
    {
        // Ensure user is an agent
        if (!Auth::user()->hasRole('Agent')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $request->validate([
            'client_code' => 'required|string'
        ]);

        // Find client by client code
        $client = User::where('client_code', $request->client_code)->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found with the provided code'
            ], 404);
        }

        // Check if client already has an agent
        if ($client->agent_id !== null) {
            return response()->json([
                'success' => false,
                'message' => 'This client is already assigned to an agent'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'contact_no' => $client->contact_no,
                'client_code' => $client->client_code
            ]
        ]);
    }

    /**
     * Assign a client to the logged-in agent
     */
    public function assignAgent(Request $request)
    {
        // Ensure user is an agent
        if (!Auth::user()->hasRole('Agent')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $request->validate([
            'client_id' => 'required|exists:users,id'
        ]);

        $client = User::findOrFail($request->client_id);

        // Check if client already has an agent
        if ($client->agent_id !== null) {
            return response()->json([
                'success' => false,
                'message' => 'This client is already assigned to an agent'
            ], 422);
        }

        // Assign the current agent to the client
        $client->agent_id = Auth::id();
        $client->save();

        return response()->json([
            'success' => true,
            'message' => 'Client successfully assigned to you as a referral'
        ]);
    }
}
