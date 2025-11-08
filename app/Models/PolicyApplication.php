<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PolicyApplication extends Model
{
    protected $table = 'policy_applications';

    protected $guarded = [];

    protected $casts = [
        'agree_data_protection' => 'boolean',
        'agree_declaration' => 'boolean',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'action_at' => 'datetime',
        'payment_received_at' => 'datetime',
        'sent_to_underwriter_at' => 'datetime',
        'activated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the policy application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who performed the last action.
     */
    public function actionBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    /**
     * Get the policy pricing for this application.
     */
    public function policyPricing(): HasOne
    {
        return $this->hasOne(PolicyPricing::class, 'user_id', 'user_id')->where('is_used', true);
    }

    /**
     * Get the healthcare service for this application.
     */
    public function healthcareService(): HasOne
    {
        return $this->hasOne(HealthcareService::class, 'user_id', 'user_id')->where('is_used', true);
    }
}
