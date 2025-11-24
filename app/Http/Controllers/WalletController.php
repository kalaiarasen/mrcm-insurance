<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['roles', 'applicantProfile'])
            ->whereHas('roles', function($query) {
                $query->where('name', 'Client');
            })
            ->orderBy('name', 'asc')
            ->get();
            
        return view('pages.wallet.index', compact('users'));
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
