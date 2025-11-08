<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_no',
        'application_status',
        'application_submitted_at',
        'submission_version',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'application_submitted_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the user's applicant profile.
     */
    public function applicantProfile(): HasOne
    {
        return $this->hasOne(ApplicantProfile::class)->where('is_used', true);
    }

    /**
     * Get the user's applicant contact.
     */
    public function applicantContact(): HasOne
    {
        return $this->hasOne(ApplicantContact::class)->where('is_used', true);
    }

    /**
     * Get the user's qualifications.
     */
    public function qualifications(): HasMany
    {
        return $this->hasMany(Qualification::class)->where('is_used', true);
    }

    /**
     * Get the user's addresses.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class)->where('is_used', true);
    }

    /**
     * Get the user's healthcare service.
     */
    public function healthcareService(): HasOne
    {
        return $this->hasOne(HealthcareService::class)->where('is_used', true);
    }

    /**
     * Get the user's policy pricing.
     */
    public function policyPricing(): HasOne
    {
        return $this->hasOne(PolicyPricing::class)->where('is_used', true);
    }

    /**
     * Get the user's risk management.
     */
    public function riskManagement(): HasOne
    {
        return $this->hasOne(RiskManagement::class)->where('is_used', true);
    }

    /**
     * Get the user's insurance history.
     */
    public function insuranceHistory(): HasOne
    {
        return $this->hasOne(InsuranceHistory::class)->where('is_used', true);
    }

    /**
     * Get the user's claims experience.
     */
    public function claimsExperience(): HasOne
    {
        return $this->hasOne(ClaimsExperience::class)->where('is_used', true);
    }

    /**
     * Get the user's policy application.
     */
    public function policyApplication(): HasOne
    {
        return $this->hasOne(PolicyApplication::class)->where('is_used', true);
    }

    /**
     * Get all of the user's policy applications (history).
     */
    public function policyApplications(): HasMany
    {
        return $this->hasMany(PolicyApplication::class);
    }
}