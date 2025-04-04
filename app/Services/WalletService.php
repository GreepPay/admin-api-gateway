<?php

namespace App\Services;

use App\Datasource\NetworkHandler;

class WalletService
{
    protected $serviceUrl;
    protected NetworkHandler $walletNetwork;

    /**
     * construct
     *
     * @param bool $useCache Whether to use cache
     * @param array $headers Headers to send with the request
     * @param string $apiType Type of API to use
     * @return mixed
     */
    public function __construct(
        bool $useCache = true,
        array $headers = [],
        string $apiType = "graphql"
    ) {
        $this->serviceUrl = env(
            "WALLET_API",
            env("SERVICE_BROKER_URL") .
                "/broker/greep-wallet/" .
                env("APP_STATE")
        );

        $this->walletNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    // Wallet

    /**
     * Create a wallet
     *
     * @param array $data
     * @return mixed
     */
    public function createWallet(array $data)
    {
        return $this->walletNetwork->post("/v1/wallets", $data);
    }

    // Saved Bank Account

    /**
     * Create a saved bank account
     *
     * @param array $data
     * @return mixed
     */
    public function createSavedBankAccount(array $data)
    {
        return $this->walletNetwork->post("/v1/user-banks", $data);
    }

    /**
     * Soft delete a saved bank account
     *
     * @param string $id
     * @return mixed
     */
    public function softDeleteSavedBankAccount(string $id)
    {
        return $this->walletNetwork->post("/v1/user-banks/{$id}/soft-delete");
    }

    // Settlement (offRamp)

    /**
     * Get OffRamp Supported Countries
     *
     * @return mixed
     */
    public function getOffRampSupportedCountries()
    {
        return $this->walletNetwork->get("/v1/offramp/supported-countries");
    }

    /**
     * Get channels by country code
     *
     * @param string $countryCode
     * @return mixed
     */
    public function getOnRampChannelsByCountryCode(string $countryCode)
    {
        return $this->walletNetwork->get("/v1/onramp/channels/{$countryCode}");
    }

    /**
     * Get network by country code
     *
     * @param string $countryCode
     * @return mixed
     */
    public function getOnRampNetworkByCountryCode(string $countryCode)
    {
        return $this->walletNetwork->get("/v1/onramp/networks/{$countryCode}");
    }

    /**
     * Get exchange rates
     *
     * @param string $toCurrency. Default from currency is USD
     * @return mixed
     */
    public function getExchangeRates(string $toCurrency)
    {
        return $this->walletNetwork->get(
            "/v1/onramp/exchange-rates/{$toCurrency}"
        );
    }

    /**
     * Get payment settlements
     *
     * @param int $id
     * @return mixed
     */
    public function getPaymentSettlement($id)
    {
        return $this->walletNetwork->get("/v1/offramp/settlement/{$id}");
    }

    /**
     * Create payment settlement
     *
     * @param array $data
     * @param int $wallet_id
     * @param int $user_id
     * @return mixed
     */
    public function createPaymentSettlement(array $data, $wallet_id, $user_id)
    {
        return $this->walletNetwork->post(
            "/v1/offramp/{$wallet_id}/{$user_id}",
            $data
        );
    }

    /**
     * Accept payment settlement
     * @param int $id
     * @return mixed
     */
    public function acceptPaymentSettlement($id)
    {
        return $this->walletNetwork->post("/v1/offramp/accept/{$id}", []);
    }

    /**
     * Deny payment settlement
     * @param int $id
     * @return mixed
     */
    public function denyPaymentSettlement($id)
    {
        return $this->walletNetwork->post("/v1/offramp/deny/{$id}", []);
    }

    /**
     * Resolve bank account
     * @param array $data
     * @return mixed
     */
    public function resolveBankAccount(array $data)
    {
        return $this->walletNetwork->post(
            "/v1/offramp/resolve-bank-account",
            $data
        );
    }

    // Point transactions

    /**
     * Create point transaction
     * @param array $data
     * @return mixed
     */
    public function createPointTransaction(array $data)
    {
        return $this->walletNetwork->post("/v1/point-transactions", $data);
    }

    // Update point transaction status
    /**
     * Update point transaction status
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updatePointTransactionStatus($id, $status)
    {
        return $this->walletNetwork->post(
            "/v1/point-transactions/{$id}/status",
            ["status" => $status]
        );
    }

    // Transaction (normal)

    /**
     * Create transaction
     * @param array $data
     * @return mixed
     */
    public function createTransaction(array $data)
    {
        return $this->walletNetwork->post("/v1/transactions", $data);
    }

    /**
     * Update transaction status
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateTransactionStatus($id, $status)
    {
        return $this->walletNetwork->post("/v1/transactions/{$id}/status", [
            "status" => $status,
        ]);
    }
}
