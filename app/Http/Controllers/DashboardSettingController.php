<?php

namespace App\Http\Controllers;

use App\Models\DashboardSetting;
use Illuminate\Http\Request;

class DashboardSettingController extends Controller
{
    /**
     * Show the form for editing dashboard settings
     */
    public function edit()
    {
        $setting = DashboardSetting::first();
        
        // Create default if doesn't exist
        if (!$setting) {
            $setting = DashboardSetting::create([
                'welcome_title' => 'Welcome to MRCM Services!',
                'welcome_description' => '<Place holder text to edit in admin>
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue more....',
            ]);
        }
        
        return view('pages.dashboard-settings.edit', compact('setting'));
    }

    /**
     * Update dashboard settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'welcome_title' => 'required|string|max:255',
            'welcome_description' => 'nullable|string',
        ]);

        $setting = DashboardSetting::first();
        
        if ($setting) {
            $setting->update($validated);
        } else {
            DashboardSetting::create($validated);
        }

        return redirect()->route('dashboard-settings.edit')
            ->with('success', 'Dashboard settings updated successfully!');
    }
}
