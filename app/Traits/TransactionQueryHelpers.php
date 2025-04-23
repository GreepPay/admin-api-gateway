<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait TransactionQueryHelpers
{
    /**
     * Filter transactions by user type through the user relationship.
     */
    public function scopeWhereProfileType(Builder $query, string $type): Builder
    {
        return $query->whereHas('user.profile', function ($q) use ($type) {
            $q->where('user_type', $type);
        });
    }

    /**
     * Sum charges column safely as decimal.
     */
    public function scopeSumCharges(Builder $query): float
    {
        return (float) $query
            ->whereNotNull('charges')
            ->sum(DB::raw('CAST(charges AS DECIMAL(10,2))'));
    }
}
