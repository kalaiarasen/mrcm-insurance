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
            'new' => 'bg-secondary',
            'quote' => 'bg-warning text-dark',
            'active' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    /**
     * Get formatted customer status name
     */
    public function getStatusNameAttribute()
    {
        return match($this->customer_status) {
            'new' => 'New',
            'quote' => 'Quote',
            'active' => 'Active',
            default => ucfirst($this->customer_status),
        };
    }

    /**
     * Check if payment upload should be shown
     */
    public function getShouldShowPaymentUploadAttribute()
    {
        return $this->customer_status === 'quote' 
            && $this->quoted_price > 0 
            && !$this->payment_document;
    }

    /**
     * Get payment document URL
     */
    public function getPaymentDocumentUrlAttribute()
    {
        return $this->payment_document ? asset('storage/' . $this->payment_document) : null;
    }
}
