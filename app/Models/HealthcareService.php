<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthcareService extends Model
{
    protected $table = 'healthcare_services';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the healthcare service.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
