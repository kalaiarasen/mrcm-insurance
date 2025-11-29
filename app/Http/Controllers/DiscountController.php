<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::orderBy('start_date', 'desc')->get();
        return view('pages.discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.discounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'type' => 'required|in:discount,voucher',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:255',
        ];

        // Conditional validation based on type
        if ($request->type === 'discount') {
            $rules['product'] = 'required|in:pharmacist,medical_practice,dental_practice';
        } else {
            // Voucher type should not have product
            $rules['product'] = 'prohibited';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Auto-generate voucher code for voucher type
            if ($validated['type'] === 'voucher') {
                $validated['voucher_code'] = Discount::generateVoucherCode();
            }

            Discount::create($validated);
            DB::commit();

            $message = $validated['type'] === 'voucher' 
                ? 'Voucher created successfully! Code: ' . $validated['voucher_code']
                : 'Discount created successfully!';

            return redirect()->route('discounts.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create ' . ($request->type ?? 'discount') . '. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        return view('pages.discounts.show', compact('discount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        return view('pages.discounts.edit', compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        // Base validation rules
        $rules = [
            'type' => 'required|in:discount,voucher',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:255',
        ];

        // Conditional validation based on type
        if ($request->type === 'discount') {
            $rules['product'] = 'required|in:pharmacist,medical_practice,dental_practice';
        } else {
            // Voucher type should not have product
            $rules['product'] = 'prohibited';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Auto-generate voucher code if changing to voucher type and no code exists
            if ($validated['type'] === 'voucher' && empty($discount->voucher_code)) {
                $validated['voucher_code'] = Discount::generateVoucherCode();
            }

            $discount->update($validated);
            DB::commit();

            $message = $validated['type'] === 'voucher' && isset($validated['voucher_code'])
                ? 'Voucher updated successfully! Code: ' . $validated['voucher_code']
                : ucfirst($validated['type']) . ' updated successfully!';

            return redirect()->route('discounts.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update ' . ($request->type ?? 'discount') . '. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        DB::beginTransaction();
        try {
            $discount->delete();
            DB::commit();

            return redirect()->route('discounts.index')
                ->with('success', 'Discount deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete discount. Please try again.');
        }
    }

    /**
     * Get active discount for a specific date and product (API endpoint)
     */
    public function getActiveDiscount(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'product' => 'required|in:pharmacist,medical_practice,dental_practice',
        ]);

        $date = $validated['date'];
        $product = $validated['product'];

        // Find discount where the date falls within start_date and end_date
        // AND product matches AND type is 'discount'
        $discount = Discount::where('type', 'discount')
            ->where('product', $product)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        if ($discount) {
            return response()->json([
                'success' => true,
                'discount' => [
                    'id' => $discount->id,
                    'percentage' => $discount->percentage,
                    'description' => $discount->description,
                    'start_date' => $discount->start_date->format('Y-m-d'),
                    'end_date' => $discount->end_date->format('Y-m-d'),
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No active discount found for the selected date and product.'
        ]);
    }

    /**
     * Validate voucher code (API endpoint)
     */
    public function validateVoucher(Request $request)
    {
        $validated = $request->validate([
            'voucher_code' => 'required|string|max:50',
            'date' => 'required|date',
        ]);

        $voucherCode = strtoupper(trim($validated['voucher_code']));
        $date = $validated['date'];

        // Find voucher by code, check if it's active and type is 'voucher'
        $voucher = Discount::where('type', 'voucher')
            ->where('voucher_code', $voucherCode)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        if ($voucher) {
            return response()->json([
                'success' => true,
                'voucher' => [
                    'id' => $voucher->id,
                    'code' => $voucher->voucher_code,
                    'percentage' => $voucher->percentage,
                    'description' => $voucher->description,
                    'start_date' => $voucher->start_date->format('Y-m-d'),
                    'end_date' => $voucher->end_date->format('Y-m-d'),
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired voucher code.'
        ], 422);
    }
}
