<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'coverage_benefits',
        'brochure_path',
        'pdf_path',
        'pdf_title',
        'form_fields',
        'notification_email',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'form_fields' => 'array',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the brochure URL
     */
    public function getBrochureUrlAttribute()
    {
        return $this->brochure_path ? Storage::url($this->brochure_path) : null;
    }

    /**
     * Get the PDF URL
     */
    public function getPdfUrlAttribute()
    {
        return $this->pdf_path ? Storage::url($this->pdf_path) : null;
    }

    /**
     * Get formatted type name
     */
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'car_insurance' => 'Car Insurance',
            'rahmah_insurance' => 'Rahmah Insurance',
            'hiking_insurance' => 'Hiking Insurance',
            'other' => 'Other',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    /**
     * Get short coverage excerpt
     */
    public function getCoverageExcerptAttribute()
    {
        $text = strip_tags($this->coverage_benefits);
        return strlen($text) > 150 ? substr($text, 0, 150) . '...' : $text;
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered products
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('created_at', 'desc');
    }
}
