<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthcareService extends Model
{
    protected $table = 'healthcare_services';

    protected $fillable = [
        'user_id',
        'professional_indemnity_type',
        'employment_status',
        'specialty_area',
        'cover_type',
        'locum_practice_location',
        'service_type',
        'practice_area',
        'is_used',
    ];

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
