<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PolicyApplication;
use App\Models\QuotationRequest;

class MyPoliciesController extends Controller
{
    /**
     * Display all policies for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();

        // Get Professional Indemnity policies
        $policyApplications = PolicyApplication::where('user_id', $user->id)
            ->with(['user.policyPricing'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get Other Policies (Quotation Requests)
        $quotationRequests = QuotationRequest::where('user_id', $user->id)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.my-policies.index', compact('policyApplications', 'quotationRequests'));
    }
}
