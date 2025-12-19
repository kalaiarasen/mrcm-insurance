<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'form_data',
        'customer_status',
        'admin_status',
        'quoted_price',
        'quotation_details',
        'admin_notes',
        'payment_document',
        'payment_uploaded_at',
        'wallet_amount_applied',
        'final_price',
        'selected_option_id',
        'policy_document',
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
     * Get all quotation options for this request
     */
    public function options()
    {
        return $this->hasMany(QuotationOption::class);
    }

    /**
     * Get the selected quotation option
     */
    public function selectedOption()
    {
        return $this->belongsTo(QuotationOption::class, 'selected_option_id');
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
        // If using new options system
        if ($this->options()->count() > 0) {
            return $this->customer_status === 'quote' 
                && $this->selected_option_id 
                && $this->selectedOption 
                && !$this->payment_document;
        }
        
        // Fallback to old system
        return $this->customer_status === 'quote' 
            && $this->quoted_price > 0 
            && !$this->payment_document;
    }

    /**
     * Get the price to use for payment (from selected option or quoted_price)
     */
    public function getPaymentPriceAttribute()
    {
        if ($this->selectedOption) {
            return $this->selectedOption->price;
        }
        return $this->quoted_price;
    }

    /**
     * Get payment document URL
     */
    public function getPaymentDocumentUrlAttribute()
    {
        return $this->payment_document ? asset('storage/' . $this->payment_document) : null;
    }
}
