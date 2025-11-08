<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
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
}
