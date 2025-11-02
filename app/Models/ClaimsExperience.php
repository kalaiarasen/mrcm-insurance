<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimsExperience extends Model
{
    protected $table = 'claims_experiences';

    protected $guarded = [];

    protected $casts = [
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
