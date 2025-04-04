<?php

namespace App\Services;

use App\Datasource\NetworkHandler;

class BlockchainService
{
    protected $serviceUrl;
    protected $blockchainNetwork;

    /**
     * Constructor for the BlockchainService class.
     *
     * @param bool $useCache Whether to use caching.
     * @param array $headers The headers to use.
     * @param string $apiType The type of API to use.
     */
    public function __construct(
        $useCache = true,
        $headers = [],
        $apiType = "graphql"
    ) {
        $this->serviceUrl = env(
            "BLOCKCHAIN_API",
            env("SERVICE_BROKER_URL") .
                "/broker/greep-blockchain/" .
                env("APP_STATE")
        );
        $this->blockchainNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    // Accounts

    /**
     * Create a new account on the blockchain.
     *
     * @param array $request The request data.
     * @return mixed The response from the blockchain network.
     */
    public function createAccount(array $request)
    {
        return $this->blockchainNetwork->post("/v1/accounts", $request);
    }

    /**
     * Activate an account on the blockchain.
     *
     * @param string $accountId The ID of the account to activate.
     * @return mixed The response from the blockchain network.
     */
    public function activateAccount(string $accountId)
    {
        return $this->blockchainNetwork->post("/v1/accounts/activate", [
            "account_id" => $accountId,
        ]);
    }

    /**
     * Update an account on the blockchain.
     *
     * @param array $request The request data.
     * @return mixed The response from the blockchain network.
     */
    public function updateAccount(array $request)
    {
        return $this->blockchainNetwork->post("/v1/accounts/update", $request);
    }

    /**
     * Delete an account on the blockchain.
     *
     * @param array $request The request data.
     * @return mixed The response from the blockchain network.
     */
    public function deleteAccount(array $request)
    {
        return $this->blockchainNetwork->post("/v1/accounts/delete", $request);
    }

    // Payments

    /**
     * Establish a trustline for an asset for an account on the blockchain.
     *
     * @param array $request The request data.
     * @return mixed The response from the blockchain network.
     */
    public function establishTrustline(array $request)
    {
        return $this->blockchainNetwork->post(
            "/v1/payments/trustline",
            $request
        );
    }

    /**
     * Send native payment (XLM)
     *
     * @param array $request The request data.
     * @return mixed The response from the blockchain network.
     */
    public function sendNativePayment(array $request)
    {
        return $this->blockchainNetwork->post("/v1/payments/native", $request);
    }

    /**
     * Send non native payment (non-XLM)
     *
     * @param array $request The request data.
     * @return mixed The response from the blockchain network.
     */
    public function sendNonNativePayment(array $request)
    {
        return $this->blockchainNetwork->post(
            "/v1/payments/non-native",
            $request
        );
    }
}
