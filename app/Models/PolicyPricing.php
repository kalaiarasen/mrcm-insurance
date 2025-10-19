<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyPricing extends Model
{
    protected $table = 'policy_pricings';

    protected $fillable = [
        'user_id',
        'policy_start_date',
        'policy_expiry_date',
        'liability_limit',
        'base_premium',
        'gross_premium',
        'locum_addon',
        'sst',
        'stamp_duty',
        'total_payable',
    ];

    protected $casts = [
        'policy_start_date' => 'date',
        'policy_expiry_date' => 'date',
        'base_premium' => 'decimal:2',
        'gross_premium' => 'decimal:2',
        'locum_addon' => 'decimal:2',
        'sst' => 'decimal:2',
        'stamp_duty' => 'decimal:2',
        'total_payable' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the policy pricing.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
