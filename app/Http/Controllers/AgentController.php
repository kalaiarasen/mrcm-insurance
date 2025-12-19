<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
}
