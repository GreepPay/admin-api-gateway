<?php

namespace App\GraphQL\Queries;

use App\Models\Auth\User;
use App\Models\Wallet\Transaction;
use App\Models\User\Business;
use App\Models\User\Customer;
use Illuminate\Support\Facades\Auth;

class UserQuery
{
    /**
     * Get the currently authenticated user.
     *
     * @param  mixed  $_
     * @param  array  $args
     * @return User|null
     */
    public function getAuthUser($_, array $args): ?User
    {
        $user = Auth::user();

        if ($user) {
            return $user;
        }

        return null;
    }

    /**
     * Get general overview metrics for the admin dashboard.
     *
     * @param  mixed  $_
     * @param  array  $args ['range' => string|null]
     * @return array{
     *     totalMerchants: int,
     *     totalCustomers: int,
     *     totalTransactions: int,
     *     totalVolume: float
     * }
     */
    public function getGeneralOverview($_, array $args)
    {
        $range = $args['range'] ?? 'all';
        $dateRange = $this->resolveDateRange($range);

        $transactions = Transaction::query();
        if ($dateRange) {
            $transactions->whereBetween('created_at', $dateRange);
        }

        return [
            'totalMerchants' => Business::count(),
            'totalCustomers' => Customer::count(),
            'totalTransactions' => (clone $transactions)->count(),
            'totalVolume' => (clone $transactions)->sum('amount'),
        ];
    }

    /**
     * Get merchant-specific financial metrics.
     *
     * @param  mixed  $_
     * @param  array  $args ['range' => string|null]
     * @return array{
     *     income: float,
     *     withdrawals: float,
     *     shopSales: float,
     *     fee: float
     * }
     */
    public function getMerchantOverview($_, array $args)
    {
        $range = $args['range'] ?? 'all';
        $query = Transaction::query();
        if ($rangeData = $this->resolveDateRange($range)) {
            $query->whereBetween('created_at', $rangeData);
        }

        return $this->merchantOverview($query);
    }

    /**
     * Get customer-specific financial metrics.
     *
     * @param  mixed  $_
     * @param  array  $args ['range' => string|null]
     * @return array{
     *     sent: float,
     *     added: float,
     *     purchases: float,
     *     fee: float
     * }
     */
    public function getCustomerOverview($_, array $args)
    {
        $range = $args['range'] ?? 'all';
        $query = Transaction::query();
        if ($rangeData = $this->resolveDateRange($range)) {
            $query->whereBetween('created_at', $rangeData);
        }

        return $this->customerOverview($query);
    }

    /**
     * Get overall transaction flow metrics.
     *
     * @param  mixed  $_
     * @param  array  $args ['range' => string|null]
     * @return array{
     *     transactions: int,
     *     moneyIn: float,
     *     moneyOut: float,
     *     volume: float
     * }
     */
    public function getTransactionOverview($_, array $args)
    {
        $range = $args['range'] ?? 'all';
        $query = Transaction::query();
        if ($rangeData = $this->resolveDateRange($range)) {
            $query->whereBetween('created_at', $rangeData);
        }

        return $this->transactionOverview($query);
    }

    /**
     * Resolve a date range from a keyword.
     *
     * @param  string $range
     * @return array{0: \Illuminate\Support\Carbon, 1: \Illuminate\Support\Carbon}|null
     */
    protected function resolveDateRange(string $range): ?array
    {
        $now = now();

        return match ($range) {
            'daily' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'weekly' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'monthly' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            default => null,
        };
    }

    /**
     * Generate metrics for merchant transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return array{income: float, withdrawals: float, shopSales: float, fee: float}
     */
    protected function merchantOverview($query): array
    {
        $baseQuery = (clone $query)->whereProfileType('Business');

        return [
            'income' => (clone $baseQuery)->where('dr_or_cr', 'credit')->sum('amount'),
            'withdrawals' => (clone $baseQuery)->where('dr_or_cr', 'debit')->sum('amount'),
            'shopSales' => (clone $baseQuery)->whereIn('chargeable_type', ['Sale', 'POS'])->sum('amount'),
            'fee' => (clone $baseQuery)->sumCharges(),
        ];
    }

    /**
     * Generate metrics for customer transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return array{sent: float, added: float, purchases: float, fee: float}
     */
    protected function customerOverview($query): array
    {
        $baseQuery = (clone $query)->whereProfileType('Customer');

        return [
            'sent' => (clone $baseQuery)->where('dr_or_cr', 'debit')->sum('amount'),
            'added' => (clone $baseQuery)->where('dr_or_cr', 'credit')->sum('amount'),
            'purchases' => (clone $baseQuery)->where('chargeable_type', 'Purchase')->where('dr_or_cr', 'debit')->sum('amount'),
            'fee' => (clone $baseQuery)->sumCharges(),
        ];
    }

    /**
     * Generate overall transaction flow metrics.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return array{transactions: int, moneyIn: float, moneyOut: float, volume: float}
     */
    protected function transactionOverview($query): array
    {
        return [
            'transactions' => (clone $query)->count(),
            'moneyIn' => (clone $query)->where('dr_or_cr', 'credit')->sum('amount'),
            'moneyOut' => (clone $query)->where('dr_or_cr', 'debit')->sum('amount'),
            'volume' => (clone $query)->sum('amount'),
        ];
    }
}
