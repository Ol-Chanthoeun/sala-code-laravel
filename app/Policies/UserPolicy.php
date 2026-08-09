<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->isAdmin() || $actor->isSuperAdmin();
    }

    public function update(User $actor, User $target): bool
    {
        if ($actor->is($target) || $target->isSuperAdmin() || $target->isProtectedPrimarySuperAdmin()) return false;
        return $actor->isSuperAdmin() || ($actor->isAdmin() && $target->isUser());
    }

    public function changeStatus(User $actor, User $target): bool
    {
        return $this->update($actor, $target);
    }

    public function delete(User $actor, User $target): bool
    {
        return $this->update($actor, $target);
    }

    public function changeRole(User $actor, User $target): bool
    {
        return $actor->isSuperAdmin()
            && ! $actor->is($target)
            && ! $target->isSuperAdmin()
            && ! $target->isProtectedPrimarySuperAdmin();
    }

    public function sendPasswordReset(User $actor, User $target): bool
    {
        return $actor->isSuperAdmin()
            && ! $actor->is($target)
            && ! $target->usesGoogleAuthentication()
            && ! $target->isSuperAdmin()
            && ! $target->isProtectedPrimarySuperAdmin();
    }
}
