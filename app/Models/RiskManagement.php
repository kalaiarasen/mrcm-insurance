<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskManagement extends Model
{
    protected $table = 'risk_managements';

    protected $guarded = [];

    protected $casts = [
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
