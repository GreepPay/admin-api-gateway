<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SearchUserTrait
{
    /**
     * Scope to search related user fields (case-insensitive).
     */
    public function scopeSearchUser(Builder $query, string $value): Builder
    {
        return $query->whereHas('user', function (Builder $q) use ($value) {
            $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($value) . '%'])
              ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($value) . '%'])
              ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($value) . '%']);
        });
    }
}
