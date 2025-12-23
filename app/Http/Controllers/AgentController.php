<?php

namespace App\Http\Controllers;

use App\Models\AgentCommission;
use App\Models\AgentPayment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AgentController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->whereNot('id', Auth::id())
            ->whereHas('roles', function($query) {
                $query->where('name', 'Agent');
            })
            ->get();
        return view('pages.agent.index', compact('users'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'date_of_birth' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'subscribe_newsletter' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_enc'=>$request->password,
            'commission_percentage' => $request->commission_percentage ?? 0,
            'date_of_birth' => $request->date_of_birth,
            'location' => $request->location,
            'bank_account_number' => $request->bank_account_number,
            'subscribe_newsletter' => $request->subscribe_newsletter ?? false,
            'approval_status' => 'approved', // Auto-approve when created by admin
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->hasRole('Super Admin')) {
            $user->assignRole('Agent');
        }

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'data' => $user->load('roles')
        ]);
    }

    public function show($user): JsonResponse
    {
        $user = User::findOrFail($user);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $user): JsonResponse
    {
        $user = User::findOrFail($user);
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'date_of_birth' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'subscribe_newsletter' => 'boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        $request->validate($rules);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'commission_percentage' => $request->commission_percentage ?? 0,
            'date_of_birth' => $request->date_of_birth,
            'location' => $request->location,
            'bank_account_number' => $request->bank_account_number,
            'subscribe_newsletter' => $request->subscribe_newsletter ?? false,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
            $updateData['password_enc'] = $request->password;
        }

        $user->assignRole('Agent');
        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'data' => $user->fresh()
        ]);
    }

    public function destroy($user): JsonResponse
    {
        $user = User::findOrFail($user);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }

    public function approve(Request $request, $user): JsonResponse
    {
        $user = User::findOrFail($user);

        $request->validate([
            'commission_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $user->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'commission_percentage' => $request->commission_percentage,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Agent approved successfully with commission!'
        ]);
    }

    public function reject($user): JsonResponse
    {
        $user = User::findOrFail($user);

        $user->update([
            'approval_status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Agent rejected successfully!'
        ]);
    }

    /**
     * Get commissions for a specific agent
     */
    public function getCommissions($agentId): JsonResponse
    {
        $agent = User::findOrFail($agentId);
        
        $commissions = AgentCommission::with(['client', 'policyApplication'])
            ->where('agent_id', $agentId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($commission) {
                return [
                    'id' => $commission->id,
                    'date' => $commission->created_at->format('d-M-Y'),
                    'client_name' => $commission->client->name ?? '-',
                    'client_code' => $commission->client->client_code ?? '-',
                    'policy_ref' => $commission->policyApplication->reference_number ?? '-',
                    'base_amount' => $commission->base_amount,
                    'commission_rate' => $commission->commission_rate,
                    'commission_amount' => $commission->commission_amount,
                    'status' => $commission->status, // Policy status: active or pending
                ];
            });

        // Calculate summary statistics (based on policy status only)
        $totalEarned = AgentCommission::where('agent_id', $agentId)->sum('commission_amount');
        
        // Active commissions = commissions from active policies
        $totalActive = AgentCommission::where('agent_id', $agentId)
            ->whereHas('policyApplication', function($q) {
                $q->where('admin_status', 'active');
            })
            ->sum('commission_amount');
        
        // Pending = commissions from policies that are NOT active yet
        $totalPending = AgentCommission::where('agent_id', $agentId)
            ->where(function($query) {
                $query->whereDoesntHave('policyApplication')
                    ->orWhereHas('policyApplication', function($q) {
                        $q->where('admin_status', '!=', 'active');
                    });
            })
            ->sum('commission_amount');
        
        // Calculate total payments made to this agent
        $totalPayments = AgentPayment::where('agent_id', $agentId)->sum('amount');
        
        // Unpaid = Active commissions - Total payments made
        $totalUnpaid = max(0, $totalActive - $totalPayments);

        return response()->json([
            'success' => true,
            'data' => [
                'agent' => [
                    'id' => $agent->id,
                    'name' => $agent->name,
                    'commission_percentage' => $agent->commission_percentage,
                ],
                'commissions' => $commissions,
                'summary' => [
                    'total_earned' => $totalEarned,
                    'total_active' => $totalActive,
                    'total_pending' => $totalPending,
                    'total_unpaid' => $totalUnpaid,
                    'total_payments' => $totalPayments,
                ]
            ]
        ]);
    }

    /**
     * Get payment history for a specific agent
     */
    public function getPaymentHistory($agentId): JsonResponse
    {
        $payments = AgentPayment::with('creator')
            ->where('agent_id', $agentId)
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

        $totalPayments = AgentPayment::where('agent_id', $agentId)->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'payments' => $payments,
                'total_payments' => $totalPayments,
            ]
        ]);
    }

    /**
     * Issue a payment to an agent
     */
    public function issuePayment(Request $request, $agentId): JsonResponse
    {
        $agent = User::findOrFail($agentId);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'commission_ids' => 'nullable|array',
            'commission_ids.*' => 'exists:agent_commissions,id',
        ]);

        DB::beginTransaction();
        try {
            // Handle receipt upload
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $receiptPath = $request->file('receipt')->store('agent-payment-receipts', 'public');
            }

            // Create payment record
            $payment = AgentPayment::create([
                'agent_id' => $agentId,
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'receipt_path' => $receiptPath,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment issued successfully!',
                'data' => $payment
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to issue payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a payment
     */
    public function deletePayment($paymentId): JsonResponse
    {
        DB::beginTransaction();
        try {
            $payment = AgentPayment::findOrFail($paymentId);
            
            // Check authorization - only creator or super admin can delete
            if ($payment->created_by !== Auth::id() && !Auth::user()->hasRole('Super Admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this payment'
                ], 403);
            }

            // Delete receipt file if exists
            if ($payment->receipt_path && Storage::disk('public')->exists($payment->receipt_path)) {
                Storage::disk('public')->delete($payment->receipt_path);
            }

            // Delete payment record
            $payment->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
