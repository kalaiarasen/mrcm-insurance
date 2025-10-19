<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PolicyHolderController extends Controller
{
    /**
     * Display a listing of all policy holders.
     */
    public function index()
    {
        // Get all users who have submitted applications (have applicant profiles)
        $policyHolders = User::whereHas('applicantProfile')
            ->with(['applicantProfile', 'applicantContact'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.policy-holder.index', compact('policyHolders'));
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
                'contact_no' => $user->contact_no,
            ]
        ]);
    }

    /**
     * Update the specified policy holder.
     */
    public function update(Request $request, User $user)
    {
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

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'contact_no' => $user->contact_no,
                'created_at' => $user->created_at->format('M d, Y'),
                'submission_version' => $user->submission_version ?? 0,
            ]
        ]);
    }
}
