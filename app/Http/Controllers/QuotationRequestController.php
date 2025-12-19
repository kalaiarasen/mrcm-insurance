<?php

namespace App\Http\Controllers;

use App\Models\QuotationRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuotationRequestController extends Controller
{
    /**
     * Display a listing of quotation requests (Admin)
     */
    public function index(Request $request)
    {
        $query = QuotationRequest::with(['product', 'user']);
        
        // Filter by product if specified
        $selectedProduct = null;
        if ($request->has('product') && $request->product) {
            $query->where('product_id', $request->product);
            $selectedProduct = Product::find($request->product);
        }
        
        $quotations = $query->orderBy('created_at', 'desc')->get();
        $products = Product::orderBy('title')->get(); // For filter dropdown
        
        return view('pages.quotation-requests.index', compact('quotations', 'products', 'selectedProduct'));
    }

    /**
     * Display the specified quotation request
     */
    public function show(QuotationRequest $quotationRequest)
    {
        $quotationRequest->load(['product', 'user', 'options', 'selectedOption']);
        return view('pages.quotation-requests.show', compact('quotationRequest'));
    }

    /**
     * Update the status of quotation request
     */
    public function update(Request $request, QuotationRequest $quotationRequest)
    {
        $validated = $request->validate([
            'admin_status' => 'required|in:new,quote,active',
            'quoted_price' => 'nullable|numeric|min:0',
            'quotation_details' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        // Sync customer_status to match admin_status
        $validated['customer_status'] = $validated['admin_status'];

        $quotationRequest->update($validated);

        return redirect()->route('quotation-requests.show', $quotationRequest->id)
            ->with('success', 'Quotation request updated successfully.');
    }

    /**
     * Remove the specified quotation request
     */
    public function destroy(QuotationRequest $quotationRequest)
    {
        $quotationRequest->delete();

        return redirect()->route('quotation-requests.index')
            ->with('success', 'Quotation request deleted successfully.');
    }

    /**
     * Store a new quotation option
     */
    public function storeOption(Request $request, QuotationRequest $quotationRequest)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'details' => 'required|string',
            'pdf_document' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        // Handle PDF upload
        if ($request->hasFile('pdf_document')) {
            $validated['pdf_document'] = $request->file('pdf_document')->store('quotation-pdfs', 'public');
        }

        $quotationRequest->options()->create($validated);

        return redirect()->route('quotation-requests.show', $quotationRequest->id)
            ->with('success', 'Quotation option added successfully.');
    }

    /**
     * Update a quotation option
     */
    public function updateOption(Request $request, $optionId)
    {
        $option = \App\Models\QuotationOption::findOrFail($optionId);

        $validated = $request->validate([
            'option_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'details' => 'required|string',
            'pdf_document' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Handle PDF upload
        if ($request->hasFile('pdf_document')) {
            // Delete old PDF if exists
            if ($option->pdf_document) {
                \Storage::disk('public')->delete($option->pdf_document);
            }
            $validated['pdf_document'] = $request->file('pdf_document')->store('quotation-pdfs', 'public');
        }

        $option->update($validated);

        return redirect()->route('quotation-requests.show', $option->quotation_request_id)
            ->with('success', 'Quotation option updated successfully.');
    }

    /**
     * Delete a quotation option
     */
    public function deleteOption($optionId)
    {
        $option = \App\Models\QuotationOption::findOrFail($optionId);
        $quotationRequestId = $option->quotation_request_id;

        // Delete PDF if exists
        if ($option->pdf_document) {
            \Storage::disk('public')->delete($option->pdf_document);
        }

        $option->delete();

        return redirect()->route('quotation-requests.show', $quotationRequestId)
            ->with('success', 'Quotation option deleted successfully.');
    }

    /**
     * Upload policy document
     */
    public function uploadPolicy(Request $request, QuotationRequest $quotationRequest)
    {
        $request->validate([
            'policy_document' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        // Delete old policy if exists
        if ($quotationRequest->policy_document) {
            \Storage::disk('public')->delete($quotationRequest->policy_document);
        }

        // Store the policy document
        $path = $request->file('policy_document')->store('policy-documents', 'public');

        $quotationRequest->update([
            'policy_document' => $path,
        ]);

        return redirect()->route('quotation-requests.show', $quotationRequest->id)
            ->with('success', 'Policy document uploaded successfully.');
    }
}
