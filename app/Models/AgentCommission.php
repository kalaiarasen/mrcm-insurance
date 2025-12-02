<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentCommission extends Model
{
    protected $guarded = [];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the agent that owns the commission.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the client for this commission.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the policy application for this commission.
     */
    public function policyApplication(): BelongsTo
    {
        return $this->belongsTo(PolicyApplication::class);
    }

    /**
     * Get the commission status based on policy application status.
     * Returns 'active' if policy is active, otherwise 'pending'
     */
    public function getStatusAttribute(): string
    {
        $policyStatus = $this->policyApplication?->admin_status;
        return $policyStatus === 'active' ? 'active' : 'pending';
    }
}
