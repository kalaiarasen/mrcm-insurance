<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantProfile extends Model
{
    protected $table = 'applicant_profiles';

    protected $fillable = [
        'user_id',
        'title',
        'nationality_status',
        'nric_number',
        'passport_number',
        'gender',
        'registration_council',
        'other_council',
        'registration_number',
        'is_used',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the applicant profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
