<?php

namespace App\Http\Controllers;

use App\Models\EmailSetting;
use Illuminate\Http\Request;

class EmailSettingController extends Controller
{
    /**
     * Display the email settings form
     */
    public function edit()
    {
        // Restrict to Super Admin only
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized access.');
        }

        $setting = EmailSetting::first();
        
        // Create default if doesn't exist
        if (!$setting) {
            $setting = EmailSetting::create([
                'mail_new_policy' => env('MAIL_NEW_POLICY', 'nurmaisarahmasaaud@greateasterngeneral.com'),
                'mail_cc_uw' => env('MAIL_CC_UW', 'kampuiyee@greateasterngeneral.com,mrcm.agent@gmail.com,wanaisyaramli@greateasterngeneral.com'),
                'mail_from_uw' => env('MAIL_FROM_UW', 'insurance@mrcm.com.my'),
                'mail_from_name' => 'MRCM Insurance',
            ]);
        }
        
        return view('pages.email-settings.edit', compact('setting'));
    }

    /**
     * Update email settings
     */
    public function update(Request $request)
    {
        // Restrict to Super Admin only
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'mail_new_policy' => 'required|email',
            'mail_cc_uw' => 'required|string',
            'mail_from_uw' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
        ]);

        // Validate CC emails
        $ccEmails = array_filter(array_map('trim', explode(',', $request->mail_cc_uw)));
        foreach ($ccEmails as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return back()->withErrors(['mail_cc_uw' => "Invalid email address: {$email}"])->withInput();
            }
        }

        $setting = EmailSetting::first();
        
        if ($setting) {
            $setting->update($request->only([
                'mail_new_policy',
                'mail_cc_uw',
                'mail_from_uw',
                'mail_from_name',
            ]));
        } else {
            EmailSetting::create($request->only([
                'mail_new_policy',
                'mail_cc_uw',
                'mail_from_uw',
                'mail_from_name',
            ]));
        }

        return back()->with('success', 'Email settings updated successfully!');
    }
}
