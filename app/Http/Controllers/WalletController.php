<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['roles', 'applicantProfile'])
                ->whereHas('roles', function($query) {
                    $query->where('name', 'Client');
                })
                ->orderBy('name', 'asc');

            return DataTables::of($query)
                ->addColumn('date_created', function ($user) {
                    return '<small>' . $user->created_at->format('d-M-Y') . '</small>';
                })
                ->addColumn('name', function ($user) {
                    $title = $user->applicantProfile?->title ?? '';
                    return '<strong>' . e($title . ($title ? '. ' : '') . $user->name) . '</strong>';
                })
                ->addColumn('gender', function ($user) {
                    return ucwords($user->applicantProfile?->gender ?? '-');
                })
                ->addColumn('nation_status', function ($user) {
                    $status = ucwords($user->applicantProfile?->nationality_status ?? '-');
                    return '<span class="badge bg-info">' . e($status) . '</span>';
                })
                ->addColumn('nric_no', function ($user) {
                    return $user->applicantProfile?->nric_number ?? $user->applicantProfile?->passport_number ?? '-';
                })
                ->addColumn('email', function ($user) {
                    return e($user->email);
                })
                ->addColumn('contact_no', function ($user) {
                    return e($user->contact_no);
                })
                ->addColumn('wallet_amount', function ($user) {
                    return '<span class="badge bg-primary wallet-amount" id="wallet-' . $user->id . '" data-amount="' . floatval($user->wallet_amount) . '">RM ' . number_format($user->wallet_amount, 2) . '</span>';
                })
                ->addColumn('action', function ($user) {
                    $userName = addslashes($user->name);
                    return '
                        <button type="button" class="btn btn-sm btn-success me-1" onclick="openAddModal(' . $user->id . ', \'' . $userName . '\')" title="Add Amount">
                            <i class="fa fa-plus"></i> Add
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="openDeductModal(' . $user->id . ', \'' . $userName . '\')" title="Deduct Amount">
                            <i class="fa fa-minus"></i> Deduct
                        </button>
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
                ->filterColumn('wallet_amount', function($query, $keyword) {
                    $query->where('users.wallet_amount', 'like', "%{$keyword}%");
                })
                ->rawColumns(['date_created', 'name', 'nation_status', 'wallet_amount', 'action'])
                ->make(true);
        }
            
        return view('pages.wallet.index');
    }

    /**
     * Add amount to user wallet via AJAX
     */
    public function addAmount(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($validated['user_id']);
            $oldAmount = $user->wallet_amount;
            $newAmount = $oldAmount + $validated['amount'];
            
            $user->wallet_amount = $newAmount;
            $user->save();

            Log::info('Wallet amount added', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'old_amount' => $oldAmount,
                'added_amount' => $validated['amount'],
                'new_amount' => $newAmount,
                'admin_id' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wallet amount added successfully!',
                'new_amount' => number_format($newAmount, 2),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to add wallet amount', [
                'user_id' => $validated['user_id'],
                'amount' => $validated['amount'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add wallet amount. Please try again.',
            ], 500);
        }
    }

    /**
     * Deduct amount from user wallet via AJAX
     */
    public function deductAmount(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($validated['user_id']);
            $oldAmount = $user->wallet_amount;
            
            // Check if user has sufficient balance
            if ($oldAmount < $validated['amount']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance!',
                ], 400);
            }
            
            $newAmount = $oldAmount - $validated['amount'];
            
            $user->wallet_amount = $newAmount;
            $user->save();

            Log::info('Wallet amount deducted', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'old_amount' => $oldAmount,
                'deducted_amount' => $validated['amount'],
                'new_amount' => $newAmount,
                'admin_id' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wallet amount deducted successfully!',
                'new_amount' => number_format($newAmount, 2),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to deduct wallet amount', [
                'user_id' => $validated['user_id'],
                'amount' => $validated['amount'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to deduct wallet amount. Please try again.',
            ], 500);
        }
    }
}
