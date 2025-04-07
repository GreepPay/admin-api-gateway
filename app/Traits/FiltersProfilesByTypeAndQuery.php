<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FiltersProfilesByTypeAndQuery
{
    /**
     * Scope a query to filter profile by user type and query string.
     *
     * @param  Builder $query
     * @param  array   $args
     * @return Builder
     */
    public function scopeFilterByUserTypeAndQuery(Builder $query, array $args): Builder
    {
        $userType = $args['userType'] ?? null;
        $searchQuery = $args['query'] ?? null;

        return $query
            ->when($userType, fn($q) => $q->where('user_type', $userType))
            ->when($searchQuery, function ($q) use ($searchQuery, $userType) {
                $q->where(function ($sub) use ($searchQuery, $userType) {
                    // Filter user fields (first_name, last_name, email)
                    $sub->whereHas('user', function ($userQuery) use ($searchQuery) {
                        $userQuery->where('first_name', 'ilike', "%{$searchQuery}%")
                                  ->orWhere('last_name', 'ilike', "%{$searchQuery}%")
                                  ->orWhere('email', 'ilike', "%{$searchQuery}%");
                    });

                    // Filter by business fields if userType is 'Business'
                    if ($userType === 'Business') {
                        $sub->orWhereHas('business', function ($bizQuery) use ($searchQuery) {
                            $bizQuery->where('business_name', 'ilike', "%{$searchQuery}%")
                                     ->orWhere('country', 'ilike', "%{$searchQuery}%");
                        });
                    }

                    // Filter by customer fields if userType is 'Customer'
                    if ($userType === 'Customer') {
                        $sub->orWhereHas('customer', function ($custQuery) use ($searchQuery) {
                            $custQuery->where('country', 'ilike', "%{$searchQuery}%");
                        });
                    }
                });
            });
    }
}
