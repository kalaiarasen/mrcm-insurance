<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Qualification extends Model
{
    protected $table = 'qualifications';

    protected $fillable = [
        'user_id',
        'sequence',
        'institution',
        'degree_or_qualification',
        'year_obtained',
    ];

    protected $casts = [
        'year_obtained' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the qualification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
