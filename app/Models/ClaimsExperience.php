<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimsExperience extends Model
{
    protected $table = 'claims_experiences';

    protected $fillable = [
        'user_id',
        'claims_made',
        'aware_of_errors',
        'disciplinary_action',
        'claim_date_of_claim',
        'claim_notified_date',
        'claim_claimant_name',
        'claim_allegations',
        'claim_amount_claimed',
        'claim_status',
        'claim_amounts_paid',
        'is_used',
    ];

    protected $casts = [
        'claims_made' => 'boolean',
        'aware_of_errors' => 'boolean',
        'disciplinary_action' => 'boolean',
        'claim_date_of_claim' => 'date',
        'claim_notified_date' => 'date',
        'claim_amount_claimed' => 'decimal:2',
        'claim_amounts_paid' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the claims experience.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
