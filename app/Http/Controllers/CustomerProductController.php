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
            'customer_status' => 'new',
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

        // Validate that quotation is in quote status and has a quoted price
        if ($quotation->customer_status !== 'quote' || !$quotation->quoted_price) {
            return redirect()->back()->with('error', 'Payment upload is not available for this quotation.');
        }

        $request->validate([
            'payment_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'wallet_amount' => 'nullable|numeric|min:0|max:' . min(auth()->user()->wallet_amount, $quotation->quoted_price),
        ]);

        // Store the payment document
        $path = $request->file('payment_document')->store('quotation-payments', 'public');

        // Calculate wallet amount and final price
        $walletAmount = floatval($request->input('wallet_amount', 0));
        $quotedPrice = floatval($quotation->quoted_price);
        $finalPrice = max(0, $quotedPrice - $walletAmount);

        // Deduct wallet amount from user's balance
        if ($walletAmount > 0) {
            $user = auth()->user();
            $user->wallet_amount = max(0, $user->wallet_amount - $walletAmount);
            $user->save();
        }

        // Update quotation request - keep status as 'quote' until admin activates
        $quotation->update([
            'payment_document' => $path,
            'payment_uploaded_at' => now(),
            'wallet_amount_applied' => $walletAmount,
            'final_price' => $finalPrice,
        ]);

        $message = 'Payment proof uploaded successfully.';
        if ($walletAmount > 0) {
            $message .= ' RM ' . number_format($walletAmount, 2) . ' has been deducted from your wallet.';
        }

        return redirect()->route('customer.quotations.show', $quotation->id)
            ->with('success', $message);
    }
}
