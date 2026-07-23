<?php

namespace App\Policies;

use App\Models\User;

class QuizPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->manage($user);
    }

    public function view(User $user): bool
    {
        return $this->manage($user);
    }

    public function create(User $user): bool
    {
        return $this->manage($user);
    }

    public function update(User $user): bool
    {
        return $this->manage($user);
    }

    public function delete(User $user): bool
    {
        return $this->manage($user);
    }

    private function manage(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true);
    }
}
