<?php

namespace App\Services;

use App\Datasource\NetworkHandler;

class AuthService
{
    protected $serviceUrl;
    protected $authNetwork;

    /**
     * construct
     *
     * @param bool $useCache
     * @param array $headers
     * @param string $apiType
     * @return mixed
     */
    public function __construct(
        $useCache = true,
        $headers = [],
        $apiType = "graphql"
    ) {
        $this->serviceUrl = env(
            "AUTH_API",
            env("SERVICE_BROKER_URL") . "/broker/greep-auth/" . env("APP_STATE")
        );
        $this->authNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    // Authentication routes

    /**
     * Get the authenticated user.
     *
     * @return mixed
     */
    public function authUser()
    {
        return $this->authNetwork->get("/v1/auth/me");
    }

    /**
     * Create a new user.
     *
     * @param array $request
     * @return mixed
     */
    public function saveUser(array $request)
    {
        return $this->authNetwork->post("/v1/auth/users", $request);
    }

    /**
     * Authenticate a user.
     *
     * @param array $request
     * @return mixed
     */
    public function authenticateUser(array $request)
    {
        return $this->authNetwork->post("/v1/auth/login", $request);
    }

    /**
     * Reset user OTP.
     *
     * @param array $request
     * @return mixed
     */
    public function resetOtp(array $request)
    {
        return $this->authNetwork->post("/v1/auth/reset-otp", $request);
    }

    /**
     * Verify user OTP.
     *
     * @param array $request
     * @return mixed
     */
    public function verifyUserOtp(array $request)
    {
        return $this->authNetwork->post("/v1/auth/verify-otp", $request);
    }

    /**
     * Update user password.
     *
     * @param array $request
     * @return mixed
     */
    public function updatePassword(array $request)
    {
        return $this->authNetwork->post("/v1/auth/update-password", $request);
    }

    /**
     * Update user profile.
     *
     * @param array $request
     * @return mixed
     */
    public function updateAuthUserProfile(array $request)
    {
        return $this->authNetwork->post("/v1/auth/update-profile", $request);
    }

    /**
     * Log out the authenticated user.
     *
     * @return mixed
     */
    public function logOut()
    {
        return $this->authNetwork->post("/v1/auth/logout");
    }

    /**
     * Delete a user.
     *
     * @param string $userId
     * @return mixed
     */
    public function deleteUser(string $userId)
    {
        return $this->authNetwork->delete("/v1/auth/users/{$userId}");
    }

    // Authentication routes

    /**
     * Check if the user has permission
     *
     * @return mixed
     */
    public function userCan(string $permission_name)
    {
        return $this->authNetwork->get("/v1/auth/user-can/{$permission_name}");
    }
}
