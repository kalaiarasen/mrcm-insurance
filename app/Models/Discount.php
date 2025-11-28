<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Discount extends Model
{
    protected $fillable = [
        'type',
        'product',
        'voucher_code',
        'start_date',
        'end_date',
        'percentage',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'percentage' => 'decimal:2',
    ];

    /**
     * Check if discount is currently active based on date range
     */
    public function isActive(): bool
    {
        $today = now()->startOfDay();
        return $today->between($this->start_date, $this->end_date);
    }

    /**
     * Generate a unique voucher code
     */
    public static function generateVoucherCode(): string
    {
        do {
            $code = 'MRCM-' . strtoupper(Str::random(8));
        } while (self::where('voucher_code', $code)->exists());

        return $code;
    }

    /**
     * Check if this is a voucher type
     */
    public function isVoucher(): bool
    {
        return $this->type === 'voucher';
    }

    /**
     * Check if this is a discount type
     */
    public function isDiscount(): bool
    {
        return $this->type === 'discount';
    }
}
