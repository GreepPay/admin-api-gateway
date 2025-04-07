<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FiltersTransactions
{
    /**
     * Scope a query to filter transactions by search, type, and date range.
     *
     * @param  Builder $query
     * @param  array   $args
     * @return Builder
     */
    public function scopeFilterTransactions(Builder $query, array $args): Builder
    {
        $search = $args['search'] ?? null;
        $type = $args['type'] ?? null; // expected to be 'debit' or 'credit'
        $range = $args['date_range'] ?? [];

        return $query
            ->when($search, function ($q) use ($search) {
                $q->where('transaction_id', 'ilike', "%{$search}%")
                  ->orWhereHas('user', fn ($uq) =>
                      $uq->where('email', 'ilike', "%{$search}%")
                         ->orWhere('first_name', 'ilike', "%{$search}%")
                         ->orWhere('last_name', 'ilike', "%{$search}%")
                  );
            })
            ->when($type && $type !== 'all', fn ($q) => $q->where('dr_or_cr', (string) $type))
            ->when(data_get($range, 'from'), fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when(data_get($range, 'to'), fn ($q, $to) => $q->whereDate('created_at', '<=', $to));
    }
}
