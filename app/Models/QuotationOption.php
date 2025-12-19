<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_request_id',
        'option_name',
        'price',
        'details',
        'pdf_document',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the quotation request that owns this option
     */
    public function quotationRequest()
    {
        return $this->belongsTo(QuotationRequest::class);
    }

    /**
     * Get PDF document URL
     */
    public function getPdfDocumentUrlAttribute()
    {
        return $this->pdf_document ? asset('storage/' . $this->pdf_document) : null;
    }

    /**
     * Check if this option is selected
     */
    public function getIsSelectedAttribute()
    {
        return $this->quotationRequest && $this->quotationRequest->selected_option_id === $this->id;
    }
}
