<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ClaimDocument;
use App\Models\PolicyApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ClaimsController extends Controller
{
    /**
     * Store a newly created claim
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'policy_application_id' => 'required|exists:policy_applications,id',
            'action' => 'required|in:new,processing,closed,approved,rejected',
            'incident_date' => 'required|date',
            'notification_date' => 'required|date',
            'claim_title' => 'required|string|max:255',
            'claim_description' => 'required|string',
            'claim_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB per file
        ]);

        DB::beginTransaction();

        try {
            // Verify the policy belongs to the user
            $policy = PolicyApplication::where('id', $validated['policy_application_id'])
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Create the claim
            $claim = new Claim();
            $claim->user_id = auth()->id();
            $claim->policy_application_id = $validated['policy_application_id'];
            $claim->action = $validated['action'];
            $claim->incident_date = $validated['incident_date'];
            $claim->notification_date = $validated['notification_date'];
            $claim->claim_title = $validated['claim_title'];
            $claim->claim_description = $validated['claim_description'];
            $claim->status = 'pending';
            $claim->save();

            // Handle document uploads
            if ($request->hasFile('claim_documents')) {
                foreach ($request->file('claim_documents') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('claim-documents', $filename, 'public');

                    // Create document record
                    ClaimDocument::create([
                        'claim_id' => $claim->id,
                        'document_path' => $path,
                        'document_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Claim submitted successfully! Reference: ' . $claim->id);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create claim', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to submit claim. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show claim details
     */
    public function show($id)
    {
        $user = auth()->user();
        
        // Admin/Agent can see any claim, Client sees only their own
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Agent'])) {
            $claim = Claim::with(['claimDocuments', 'policyApplication', 'user'])
                ->findOrFail($id);
        } else {
            $claim = Claim::with(['claimDocuments', 'policyApplication'])
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();
        }

        return view('pages.claims.show', compact('claim'));
    }

    /**
     * List all claims for the user or admin
     */
    public function index()
    {
        $user = auth()->user();
        
        // Admin/Agent can see all claims, Client sees only their own
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Agent'])) {
            $claims = Claim::with(['policyApplication', 'user'])
                ->latest()
                ->paginate(15);
        } else {
            $claims = Claim::where('user_id', auth()->id())
                ->with('policyApplication')
                ->latest()
                ->paginate(15);
        }

        return view('pages.claims.index', compact('claims'));
    }

    /**
     * View/Stream a specific claim document
     */
    public function downloadDocument($claimId, $documentId)
    {
        $user = auth()->user();
        
        // Admin/Agent can view any document, Client sees only their own
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Agent'])) {
            $claim = Claim::findOrFail($claimId);
        } else {
            $claim = Claim::where('id', $claimId)
                ->where('user_id', auth()->id())
                ->firstOrFail();
        }

        // Get the document and verify it belongs to this claim
        $document = ClaimDocument::where('id', $documentId)
            ->where('claim_id', $claimId)
            ->firstOrFail();

        // Check if file exists
        if (!Storage::disk('public')->exists($document->document_path)) {
            abort(404, 'File not found');
        }

        // Stream the file to view in browser
        $path = Storage::disk('public')->path($document->document_path);
        
        return response()->file($path, [
            'Content-Type' => $document->mime_type ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $document->document_name . '"'
        ]);
    }

    /**
     * Update claim with additional documents
     */
    public function update(Request $request, $id)
    {
        $claim = Claim::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Only allow uploads if claim is pending
        if ($claim->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'Cannot add documents to a claim that is not pending.');
        }

        // Validate documents
        $validated = $request->validate([
            'claim_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // Handle additional document uploads
            if ($request->hasFile('claim_documents')) {
                foreach ($request->file('claim_documents') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('claim-documents', $filename, 'public');

                    // Create document record
                    ClaimDocument::create([
                        'claim_id' => $claim->id,
                        'document_path' => $path,
                        'document_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Documents added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to add documents to claim', [
                'claim_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to add documents. Please try again.')
                ->withInput();
        }
    }

    /**
     * Update claim status (Admin only)
     */
    public function updateStatus(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,closed',
            'admin_notes' => 'nullable|string',
            'claim_amount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $claim = Claim::findOrFail($id);
            
            // Update claim
            $claim->status = $validated['status'];
            if (isset($validated['admin_notes'])) {
                $claim->admin_notes = $validated['admin_notes'];
            }
            if (isset($validated['claim_amount'])) {
                $claim->claim_amount = $validated['claim_amount'];
            }
            $claim->save();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Claim status updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update claim status', [
                'claim_id' => $id,
                'admin_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to update claim status. Please try again.');
        }
    }
}
