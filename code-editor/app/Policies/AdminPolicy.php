<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can access admin features.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function accessAdmin(User $user)
    {
        return $user->is_admin == 1; // Assuming is_admin is a boolean or integer
    }
}
