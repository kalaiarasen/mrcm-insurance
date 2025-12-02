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
            'admin_status' => 'required|in:new,approved,send_uw,active,processing,rejected,cancelled',
            'quoted_price' => 'nullable|numeric|min:0',
            'quotation_details' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        // Auto-update customer_status based on admin_status (matching YourActionController logic)
        switch ($validated['admin_status']) {
            case 'approved':
                // When admin approves: customer sees "pay_now", admin sees "not_paid" internally
                // But we store approved in admin_status for the dropdown
                $validated['customer_status'] = 'pay_now';
                break;
                
            case 'active':
                // When quotation becomes active
                $validated['customer_status'] = 'active';
                break;
                
            case 'send_uw':
                // When sent to underwriter
                $validated['customer_status'] = 'processing';
                break;
                
            case 'rejected':
                // When admin rejects
                $validated['customer_status'] = 'submitted'; // Keep as submitted but rejected
                break;
                
            case 'processing':
                // When in processing
                $validated['customer_status'] = 'processing';
                break;
                
            default:
                // For 'new' and 'cancelled', keep customer_status as submitted
                if (!isset($validated['customer_status'])) {
                    $validated['customer_status'] = 'submitted';
                }
                break;
        }

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
