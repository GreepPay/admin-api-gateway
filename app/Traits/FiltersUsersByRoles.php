<?php

namespace App\Traits;

use App\Models\Auth\Role;
use Illuminate\Database\Eloquent\Builder;

trait FiltersUsersByRoles
{
    /**
     * Scope to filter users by role names.
     *
     * @param  Builder $query
     * @param  array   $args
     * @return Builder
     */
    public function scopeFilterByRoles(Builder $query, array $args): Builder
    {
        $roleNames = $args['roles'] ?? [];

        if (empty($roleNames)) {
            return $query;
        }

        $roleIds = Role::whereIn('name', $roleNames)->pluck('id');

        if ($roleIds->isEmpty()) {
            return $query->whereRaw('0 = 1');
        }

        return $query->whereIn('role_id', $roleIds);
    }
}
