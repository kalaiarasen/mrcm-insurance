<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Claim extends Model
{
    protected $table = 'claims';

    protected $guarded = [];

    protected $casts = [
        'incident_date' => 'date',
        'notification_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the policy application that owns the claim.
     */
    public function policyApplication(): BelongsTo
    {
        return $this->belongsTo(PolicyApplication::class);
    }

    /**
     * Get the user that owns the claim.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the claim documents for this claim.
     */
    public function claimDocuments(): HasMany
    {
        return $this->hasMany(ClaimDocument::class);
    }
}
