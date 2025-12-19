<?php

namespace App\Http\Controllers;

use App\Models\QuotationRequest;
use App\Models\Product;
use Illuminate\Http\Request;

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
        $quotationRequest->load(['product', 'user']);
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
}
