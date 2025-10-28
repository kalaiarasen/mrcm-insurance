<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyApplication extends Model
{
    protected $table = 'policy_applications';

    protected $fillable = [
        'user_id',
        'agree_data_protection',
        'agree_declaration',
        'signature_data',
        'status',
        'reference_number',
        'remarks',
        'action_by',
        'submitted_at',
        'approved_at',
        'action_at',
        'is_used',
    ];

    protected $casts = [
        'agree_data_protection' => 'boolean',
        'agree_declaration' => 'boolean',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'action_at' => 'datetime',
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
}
