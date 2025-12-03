<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('pages.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'contact_no' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->contact_no = $validated['contact_no'];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store new image
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $user->profile_image = $path;
        }

        // Update password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Current password is incorrect'])
                    ->withInput();
            }

            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
}
