<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyPricing extends Model
{
    protected $table = 'policy_pricings';

    protected $guarded = [];

    protected $casts = [
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
