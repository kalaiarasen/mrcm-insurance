<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceHistory extends Model
{
    protected $table = 'insurance_histories';

    protected $fillable = [
        'user_id',
        'current_insurance',
        'insurer_name',
        'period_of_insurance',
        'policy_limit_myr',
        'excess_myr',
        'retroactive_date',
        'previous_claims',
        'claims_details',
    ];

    protected $casts = [
        'current_insurance' => 'boolean',
        'previous_claims' => 'boolean',
        'policy_limit_myr' => 'decimal:2',
        'excess_myr' => 'decimal:2',
        'retroactive_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the insurance history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
