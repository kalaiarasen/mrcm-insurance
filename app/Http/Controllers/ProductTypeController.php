<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductTypeController extends Controller
{
    /**
     * Get all product types for dropdown
     */
    public function index()
    {
        $types = ProductType::orderBy('display_name')->get();
        return response()->json($types);
    }

    /**
     * Store a new product type
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
        ]);

        // Auto-generate name from display_name (snake_case)
        $name = Str::snake(strtolower($validated['display_name']));

        // Check if name already exists
        if (ProductType::where('name', $name)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A product type with this name already exists.'
            ], 422);
        }

        $productType = ProductType::create([
            'name' => $name,
            'display_name' => $validated['display_name'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product type created successfully!',
            'data' => $productType
        ]);
    }

    /**
     * Delete a product type
     */
    public function destroy($id)
    {
        $productType = ProductType::findOrFail($id);

        // Check if type is being used
        $productsCount = Product::where('type', $productType->name)->count();
        
        if ($productsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this type. It is currently used by {$productsCount} product(s)."
            ], 422);
        }

        $productType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product type deleted successfully!'
        ]);
    }
}
