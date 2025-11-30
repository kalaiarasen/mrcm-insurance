<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of products (Admin)
     */
    public function index()
    {
        $products = Product::ordered()->get();
        return view('pages.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        return view('pages.products.create');
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        // DEBUG: Log ALL request data
        \Log::info('Product Store - ALL Request Data:', $request->all());
        \Log::info('Product Store - Raw form_fields from request:', [
            'form_fields' => $request->input('form_fields'),
            'has_form_fields' => $request->has('form_fields'),
            'filled_form_fields' => $request->filled('form_fields'),
            'type' => gettype($request->input('form_fields')),
            'length' => strlen($request->input('form_fields') ?? '')
        ]);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:car_insurance,rahmah_insurance,hiking_insurance,other',
            'coverage_benefits' => 'nullable|string',
            'brochure' => 'nullable|image|mimes:jpg,jpeg|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'pdf_title' => 'nullable|string|max:255',
            'notification_email' => 'required|email',
            'form_fields' => 'nullable|string', // Changed from 'json' to 'string'
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ]);

        \Log::info('Product Store - After validation:', ['form_fields' => $validated['form_fields'] ?? 'NOT SET']);

        // Handle brochure upload
        if ($request->hasFile('brochure')) {
            $validated['brochure_path'] = $request->file('brochure')->store('products/brochures', 'public');
        }

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $validated['pdf_path'] = $request->file('pdf')->store('products/pdfs', 'public');
        }

        // Decode form_fields if it's a JSON string
        if (isset($validated['form_fields']) && !empty($validated['form_fields'])) {
            $decoded = json_decode($validated['form_fields'], true);
            \Log::info('Product Store - Decoded form_fields:', ['decoded' => $decoded, 'error' => json_last_error_msg()]);
            // Only use decoded value if it's valid JSON
            if (json_last_error() === JSON_ERROR_NONE) {
                $validated['form_fields'] = $decoded;
            } else {
                $validated['form_fields'] = null;
            }
        } else {
            $validated['form_fields'] = null;
        }

        \Log::info('Product Store - Final form_fields to save:', ['form_fields' => $validated['form_fields']]);
        
        $product = Product::create($validated);
        
        \Log::info('Product Store - Saved product:', ['id' => $product->id, 'form_fields' => $product->form_fields]);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        return view('pages.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        return view('pages.products.edit', compact('product'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:car_insurance,rahmah_insurance,hiking_insurance,other',
            'coverage_benefits' => 'nullable|string',
            'brochure' => 'nullable|image|mimes:jpg,jpeg|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'pdf_title' => 'nullable|string|max:255',
            'notification_email' => 'required|email',
            'form_fields' => 'nullable|string', // Changed from 'json' to 'string'
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ]);

        // Handle brochure upload
        if ($request->hasFile('brochure')) {
            // Delete old brochure
            if ($product->brochure_path) {
                Storage::disk('public')->delete($product->brochure_path);
            }
            $validated['brochure_path'] = $request->file('brochure')->store('products/brochures', 'public');
        }

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            // Delete old PDF
            if ($product->pdf_path) {
                Storage::disk('public')->delete($product->pdf_path);
            }
            $validated['pdf_path'] = $request->file('pdf')->store('products/pdfs', 'public');
        }

        // Decode form_fields if it's a JSON string
        if (isset($validated['form_fields']) && !empty($validated['form_fields'])) {
            $decoded = json_decode($validated['form_fields'], true);
            // Only use decoded value if it's valid JSON
            if (json_last_error() === JSON_ERROR_NONE) {
                $validated['form_fields'] = $decoded;
            } else {
                $validated['form_fields'] = null;
            }
        } else {
            $validated['form_fields'] = null;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        // Delete associated files
        if ($product->brochure_path) {
            Storage::disk('public')->delete($product->brochure_path);
        }
        if ($product->pdf_path) {
            Storage::disk('public')->delete($product->pdf_path);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
