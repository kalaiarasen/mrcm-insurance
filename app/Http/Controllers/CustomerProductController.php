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
        return view('pages.customer-products.show', compact('product'));
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
            'status' => 'pending',
        ]);

        // Send email notification
        Mail::to($product->notification_email)->send(
            new QuotationRequest($product, $validated)
        );

        return redirect()->route('customer.products.show', $product->id)
            ->with('success', 'Your quotation request has been submitted successfully. We will contact you soon.');
    }
}
