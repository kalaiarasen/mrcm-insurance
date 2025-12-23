<?php

namespace App\Http\Controllers;

use App\Models\AgentCommission;
use App\Models\AgentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AgentCommissionController extends Controller
{
    /**
     * Display a listing of agent commissions.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = AgentCommission::with(['client', 'policyApplication'])
                ->where('agent_id', Auth::id())
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->addColumn('date', function ($commission) {
                    return '<small>' . $commission->created_at->format('d-M-Y') . '</small>';
                })
                ->addColumn('client_name', function ($commission) {
                    return '<strong>' . e($commission->client->name ?? '-') . '</strong>';
                })
                ->addColumn('client_code', function ($commission) {
                    return e($commission->client->client_code ?? '-');
                })
                ->addColumn('policy_ref', function ($commission) {
                    return e($commission->policyApplication->reference_number ?? '-');
                })
                ->addColumn('base_amount', function ($commission) {
                    return 'RM ' . number_format($commission->base_amount, 2);
                })
                ->addColumn('commission_rate', function ($commission) {
                    return $commission->commission_rate . '%';
                })
                ->addColumn('commission_amount', function ($commission) {
                    return '<strong>RM ' . number_format($commission->commission_amount, 2) . '</strong>';
                })
                ->addColumn('status', function ($commission) {
                    $status = $commission->status;
                    $badge = $status === 'active' ? 'success' : 'warning';
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($status) . '</span>';
                })
                ->filterColumn('client_name', function($query, $keyword) {
                    $query->whereHas('client', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('client_code', function($query, $keyword) {
                    $query->whereHas('client', function($q) use ($keyword) {
                        $q->where('client_code', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('policy_ref', function($query, $keyword) {
                    $query->whereHas('policyApplication', function($q) use ($keyword) {
                        $q->where('reference_number', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['date', 'client_name', 'commission_amount', 'status'])
                ->make(true);
        }

        // Calculate summary statistics
        $totalPending = AgentCommission::where('agent_id', Auth::id())
            ->whereHas('policyApplication', function($q) {
                $q->where('admin_status', '!=', 'active');
            })
            ->sum('commission_amount');

        $totalActive = AgentCommission::where('agent_id', Auth::id())
            ->whereHas('policyApplication', function($q) {
                $q->where('admin_status', 'active');
            })
            ->sum('commission_amount');

        $totalEarned = AgentCommission::where('agent_id', Auth::id())
            ->sum('commission_amount');

        $totalCommissions = AgentCommission::where('agent_id', Auth::id())->count();

        $totalPayments = AgentPayment::where('agent_id', Auth::id())->sum('amount');

        return view('pages.agent.commissions', compact(
            'totalPending',
            'totalActive',
            'totalEarned',
            'totalCommissions',
            'totalPayments'
        ));
    }

    /**
     * Display agent profile
     */
    public function profile()
    {
        if (!Auth::user()->hasRole('Agent')) {
            abort(403, 'Unauthorized access');
        }

        $agent = Auth::user();
        return view('pages.agent.profile', compact('agent'));
    }

    /**
     * Update agent profile
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::user()->hasRole('Agent')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $agent = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $agent->id,
            'contact_no' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'subscribe_newsletter' => 'nullable|boolean',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($agent->profile_image && Storage::disk('public')->exists($agent->profile_image)) {
                Storage::disk('public')->delete($agent->profile_image);
            }

            // Store new image
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $path;
        }

        $agent->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact_no' => $validated['contact_no'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'location' => $validated['location'] ?? null,
            'bank_account_number' => $validated['bank_account_number'] ?? null,
            'profile_image' => $validated['profile_image'] ?? $agent->profile_image,
            'subscribe_newsletter' => $request->has('subscribe_newsletter'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Get payment history for the logged-in agent
     */
    public function getPayments(Request $request)
    {
        $payments = AgentPayment::with('creator')
            ->where('agent_id', Auth::id())
            ->orderBy('payment_date', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'payment_date' => $payment->payment_date->format('d-M-Y'),
                    'payment_method' => $payment->payment_method,
                    'reference_number' => $payment->reference_number,
                    'notes' => $payment->notes,
                    'receipt_path' => $payment->receipt_path,
                    'receipt_url' => $payment->receipt_path ? asset('storage/' . $payment->receipt_path) : null,
                    'created_by' => $payment->creator->name ?? '-',
                    'created_at' => $payment->created_at->format('d-M-Y H:i'),
                ];
            });

        $totalPayments = AgentPayment::where('agent_id', Auth::id())->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'payments' => $payments,
                'total_payments' => $totalPayments,
            ]
        ]);
    }
}
