<?php

namespace App\GraphQL\Queries;

use App\Models\Wallet\Transaction;
use App\Models\User\Business;
use App\Models\User\Customer;
use Illuminate\Support\Facades\DB;

class UserQuery
{
    /**
     * Get admin dashboard metrics including total user counts, transaction volume,
     * and summarized financial overviews for merchants and customers.
     *
     * @param  mixed  $_     The parent resolver. Not used in this query.
     * @param  array  $args  Query arguments. Supports:
     *                       - range: (string) one of 'daily', 'weekly', 'monthly', or omitted for all-time.
     *
     * @return array{
     *     totalMerchants: int,
     *     totalCustomers: int,
     *     totalTransactions: int,
     *     totalVolume: float,
     *     merchantOverview: array,
     *     customerOverview: array,
     *     transactionOverview: array
     * } A comprehensive summary of metrics for the admin dashboard.
     */
    public function getDashboardMetrics($_, array $args)
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

            'merchantOverview' => $this->merchantOverview(clone $transactions),
            'customerOverview' => $this->customerOverview(clone $transactions),
            'transactionOverview' => $this->transactionOverview(clone $transactions),
        ];
    }

    /**
     * Resolve a date range based on a string keyword.
     *
     * @param  string $range  Time range keyword. Supported values: 'daily', 'weekly', 'monthly', or 'all'.
     *
     * @return array{0: \Illuminate\Support\Carbon, 1: \Illuminate\Support\Carbon}|null
     *         A two-element Carbon date range array, or null for all-time.
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
     * Compute merchant-specific financial summary.
     * NOTE: Placeholder values used since transaction types are not defined in the schema.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query  A base transaction query builder.
     *
     * @return array{
     *     income: float,
     *     withdrawals: float,
     *     shopSales: float,
     *     fee: float
     * } A summary of merchant-related metrics (stubbed).
     */
    protected function merchantOverview($query): array
    {
        return [
            'income' => 0.0,
            'withdrawals' => 0.0,
            'shopSales' => 0.0,
            'fee' => 0.0,
        ];
    }

    /**
     * Compute customer-specific financial summary.
     * NOTE: Placeholder values used since transaction types are not defined in the schema.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query  A base transaction query builder.
     *
     * @return array{
     *     sent: float,
     *     added: float,
     *     purchases: float,
     *     fee: float
     * } A summary of customer-related metrics (stubbed).
     */
    protected function customerOverview($query): array
    {
        return [
            'sent' => 0.0,
            'added' => 0.0,
            'purchases' => 0.0,
            'fee' => 0.0,
        ];
    }

    /**
     * Compute overall transaction statistics, including counts and flow direction.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query  A base transaction query builder.
     *
     * @return array{
     *     transactions: int,
     *     moneyIn: float,
     *     moneyOut: float,
     *     volume: float
     * } A summary of overall transaction activity.
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
