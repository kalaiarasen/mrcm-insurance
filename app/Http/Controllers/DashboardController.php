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
        if(auth()->user()->hasRole('Client')) {
           $announcements = Announcement::latest()
            ->take(5)
            ->get();

            $totalUsers = User::count();
            $totalPolicies = 0;
            $totalClaims = 0;
            $monthlyRevenue = 0;

            return view('dashboard-client', compact(
                'announcements',
                'totalUsers',
                'totalPolicies',
                'totalClaims',
                'monthlyRevenue'
            ));
        }

        return view('dashboard');
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
