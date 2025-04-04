<?php

namespace App\GraphQL\Queries;

use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;

final class AuthQuery
{
    /**
     * Get the currently authenticated user.
     *
     * @param  mixed  $_      The parent resolver.  Not used in this query.
     * @param  array  $args   An array of arguments passed to the query.  Not used in this query.
     *
     * @return User|null The authenticated user, or null if not authenticated.
     */
    public function getAuthUser($_, array $args): ?User
    {
        $user = Auth::user();

        if ($user) {
            return $user;
        }

        return null;
    }
}
