<?php

namespace App\GraphQL\Queries;

use App\Services\WalletService;

final class WalletQuery
{
    protected $walletService;

    public function __construct()
    {
        $this->walletService = new WalletService();
    }

    /**
     * Get exchange rate for a given currency.
     *
     * @param  mixed  $_
     * @param  array  $args
     *
     * @return float
     */
    public function getExchangeRate($_, array $args): float
    {
        return $this->walletService->getExchangeRates(
            $args["to_currency"]
        )["data"];
    }

    /**
     * Get list of supported on-ramp currencies.
     *
     * @param  mixed  $_
     * @param  array  $args
     *
     * @return array
     */
    public function getOffRampCurrencies($_, array $args): array
    {
        return $this->walletService->getOffRampSupportedCountries()["data"];
    }
}
