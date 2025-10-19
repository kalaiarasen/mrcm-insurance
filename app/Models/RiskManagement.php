<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskManagement extends Model
{
    protected $table = 'risk_managements';

    protected $fillable = [
        'user_id',
        'medical_records',
        'informed_consent',
        'adverse_incidents',
        'sterilisation_facilities',
        'is_used',
    ];

    protected $casts = [
        'medical_records' => 'boolean',
        'informed_consent' => 'boolean',
        'adverse_incidents' => 'boolean',
        'sterilisation_facilities' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the risk management.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
