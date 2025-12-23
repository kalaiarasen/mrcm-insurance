<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgentPayment extends Model
{
    protected $fillable = [
        'agent_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
        'receipt_path',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the agent that received the payment.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the admin who created the payment.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the commissions associated with this payment.
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(AgentCommission::class, 'payment_id');
    }
}
