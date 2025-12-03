<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\PolicyApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
    /**
     * Display all claims for the client
     */
    public function index()
    {
        $claims = Claim::with(['policyApplication', 'claimDocuments'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.claim.index', compact('claims'));
    }

    /**
     * Show the form to file a new claim
     */
    public function create()
    {
        // Get all active policies for the user
        $policies = PolicyApplication::with(['user.healthcareService', 'policyPricing'])
            ->where('user_id', auth()->id())
            ->where('customer_status', 'paid')
            ->where('is_used', true)
            ->get();

        return view('pages.claim.create', compact('policies'));
    }

    /**
     * Store a new claim
     */
    public function store(Request $request)
    {
        $request->validate([
            'policy_application_id' => 'required|exists:policy_applications,id',
            'incident_date' => 'required|date',
            'notification_date' => 'required|date',
            'claim_title' => 'required|string|max:255',
            'claim_description' => 'required|string',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        DB::beginTransaction();

        try {
            // Create the claim
            $claim = Claim::create([
                'user_id' => auth()->id(),
                'policy_application_id' => $request->policy_application_id,
                'action' => 'new',
                'incident_date' => $request->incident_date,
                'notification_date' => $request->notification_date,
                'claim_title' => $request->claim_title,
                'claim_description' => $request->claim_description,
                'status' => 'pending',
            ]);

            // Store documents if any
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('claim-documents', 'public');
                    
                    $claim->claimDocuments()->create([
                        'document_name' => $file->getClientOriginalName(),
                        'document_path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('claim')->with('success', 'Claim submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to submit claim: ' . $e->getMessage());
        }
    }

    /**
     * Show claim details
     */
    public function show(Claim $claim)
    {
        // Ensure user can only view their own claims
        if ($claim->user_id !== auth()->id() && !auth()->user()->hasRole('Admin')) {
            abort(403);
        }

        $claim->load(['policyApplication.user.healthcareService', 'claimDocuments']);

        return view('pages.claim.show', compact('claim'));
    }

    /**
     * Download a claim document
     */
    public function downloadDocument(Claim $claim, $documentId)
    {
        // Ensure user can only download their own claim documents
        if ($claim->user_id !== auth()->id() && !auth()->user()->hasRole('Admin')) {
            abort(403);
        }

        $document = $claim->claimDocuments()->findOrFail($documentId);

        return Storage::disk('public')->download($document->document_path, $document->document_name);
    }
}
