<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        // if(auth()->user()->hasRole('Super Admin')) {
        //     return view('dashboard');
        // }

        $announcements = Announcement::latest()
            ->take(5) // Limit to 5 latest announcements
            ->get();

        // Get some basic statistics (you can expand this based on your needs)
        $totalUsers = User::count();
        $totalPolicies = 0; // You can add policy count when you have policy model
        $totalClaims = 0; // You can add claims count when you have claims model
        $monthlyRevenue = 0; // You can calculate monthly revenue

        return view('dashboard-client', compact(
            'announcements',
            'totalUsers',
            'totalPolicies',
            'totalClaims',
            'monthlyRevenue'
        ));
    }

    /**
     * Get announcements for AJAX requests
     */
    public function getAnnouncements()
    {
        $announcements = Announcement::latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }
}
