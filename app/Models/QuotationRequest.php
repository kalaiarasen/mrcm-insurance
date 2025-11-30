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
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'form_data' => 'array',
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
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'reviewed' => 'bg-info',
            'quoted' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get formatted status name
     */
    public function getStatusNameAttribute()
    {
        return ucfirst($this->status);
    }
}
