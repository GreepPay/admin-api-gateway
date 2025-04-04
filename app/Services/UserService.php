<?php

namespace App\Services;

use App\Datasource\NetworkHandler;

class UserService
{
    protected $serviceUrl;
    protected NetworkHandler $userNetwork;

    /**
     * construct
     *
     * @param bool $useCache Whether to use caching.
     * @param array $headers Headers to be sent with the request.
     * @param string $apiType Type of API to be used.
     * @return mixed
     */
    public function __construct(
        bool $useCache = true,
        array $headers = [],
        string $apiType = "graphql"
    ) {
        $this->serviceUrl = env(
            "USER_API",
            env("SERVICE_BROKER_URL") . "/broker/greep-user/" . env("APP_STATE")
        );

        $this->userNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    // Profile

    /**
     * Create a new profile.
     *
     * @param array $request
     * @return mixed
     */
    public function createProfile(array $request)
    {
        return $this->userNetwork->post("/v1/profiles", $request);
    }

    /**
     * Update a profile.
     *
     * @param array $request
     * @return mixed
     */
    public function updateProfile(array $request)
    {
        return $this->userNetwork->put("/v1/profiles", $request);
    }

    // Verifications

    /**
     * Verify user identity.
     *
     * @param array $request
     * @return mixed
     */
    public function verifyIdentity(array $request)
    {
        return $this->userNetwork->post("/v1/verify-identity", $request);
    }

    /**
     * Create verification request.
     *
     * @param array $request
     * @return mixed
     */
    public function createVerificationRequest(array $request)
    {
        return $this->userNetwork->post("/v1/verifications", $request);
    }
}
