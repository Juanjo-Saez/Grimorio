<?php

namespace App\Policies;

use App\Models\SharedLink;
use App\Models\User;

class SharedLinkPolicy
{
    public function delete(User $user, SharedLink $link): bool
    {
        return $user->id === $link->owner_id;
    }
}
