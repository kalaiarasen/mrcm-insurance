<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\QuotationRequest as QuotationRequestModel;
use App\Mail\QuotationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerProductController extends Controller
{
    /**
     * Display all active products to customers
     */
    public function index()
    {
        $products = Product::active()->ordered()->get();
        return view('pages.customer-products.index', compact('products'));
    }

    /**
     * Display a single product detail
     */
    public function show($id)
    {
        $product = Product::active()->findOrFail($id);
        
        // Get previous quotation requests for this product by the current user
        $previousRequests = QuotationRequestModel::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.customer-products.show', compact('product', 'previousRequests'));
    }

    /**
     * Handle quotation form submission
     */
    public function submitQuotation(Request $request, $id)
    {
        $product = Product::active()->findOrFail($id);

        // Validate based on form_fields
        $rules = [];
        if ($product->form_fields) {
            foreach ($product->form_fields['fields'] ?? [] as $field) {
                if (!empty($field['validation'])) {
                    $rules[$field['name']] = $field['validation'];
                }
            }
        }

        $validated = $request->validate($rules);

        // Save quotation request to database
        $quotationRequest = QuotationRequestModel::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'form_data' => $validated,
            'customer_status' => 'submitted',
            'admin_status' => 'new',
        ]);

        // Send email notification
        Mail::to($product->notification_email)->send(
            new QuotationRequest($product, $validated)
        );

        return redirect()->route('customer.products.show', $product->id)
            ->with('success', 'Your quotation request has been submitted successfully. We will contact you soon.');
    }

    /**
     * Show quotation request details for client
     */
    public function showQuotation($id)
    {
        $quotation = QuotationRequestModel::with(['product', 'user'])
            ->where('id', $id)
            ->where('user_id', auth()->id()) // Ensure user can only view their own quotations
            ->firstOrFail();

        return view('pages.customer-products.quotation-show', compact('quotation'));
    }

    /**
     * Upload payment proof for quotation request
     */
    public function uploadPayment(Request $request, $id)
    {
        $quotation = QuotationRequestModel::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Validate that quotation is in pay_now status
        if ($quotation->customer_status !== 'pay_now') {
            return redirect()->back()->with('error', 'Payment upload is not available for this quotation.');
        }

        $request->validate([
            'payment_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Store the payment document
        $path = $request->file('payment_document')->store('quotation-payments', 'public');

        // Update quotation request
        $quotation->update([
            'payment_document' => $path,
            'payment_uploaded_at' => now(),
            'customer_status' => 'paid',
            'admin_status' => 'processing', // Changed from 'paid' to 'processing'
        ]);

        return redirect()->route('customer.quotations.show', $quotation->id)
            ->with('success', 'Payment proof uploaded successfully. We will process your payment shortly.');
    }
}
