<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'form_data',
        'customer_status',
        'admin_status',
        'quoted_price',
        'wallet_amount_applied',
        'final_price',
        'quotation_details',
        'payment_document',
        'payment_uploaded_at',
        'admin_notes',
    ];

    protected $casts = [
        'form_data' => 'array',
        'payment_uploaded_at' => 'datetime',
        'quoted_price' => 'decimal:2',
        'wallet_amount_applied' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    /**
     * Get the product for this quotation request
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who submitted this quotation request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get customer status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->customer_status) {
            'submitted' => 'bg-secondary',
            'pay_now' => 'bg-warning text-dark',
            'paid' => 'bg-info',
            'processing' => 'bg-primary',
            'completed' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    /**
     * Get formatted customer status name
     */
    public function getStatusNameAttribute()
    {
        return match($this->customer_status) {
            'pay_now' => 'Pay Now',
            default => ucfirst($this->customer_status),
        };
    }

    /**
     * Get payment document URL
     */
    public function getPaymentDocumentUrlAttribute()
    {
        return $this->payment_document ? asset('storage/' . $this->payment_document) : null;
    }
}
