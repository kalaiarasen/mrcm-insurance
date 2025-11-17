<?php

namespace App\Policies;

use App\Models\PolicyApplication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PolicyApplicationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PolicyApplication $policyApplication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     * Only admin users can update policy application status
     */
    public function update(User $user, PolicyApplication $policyApplication): bool
    {
        // Check if user has admin role or permission
        return $user->hasAnyRole(['Super Admin', 'Admin', 'Agent']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PolicyApplication $policyApplication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PolicyApplication $policyApplication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PolicyApplication $policyApplication): bool
    {
        return false;
    }
}
