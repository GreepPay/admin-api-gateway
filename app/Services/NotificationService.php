<?php

namespace App\Services;

use App\Datasource\NetworkHandler;

class NotificationService
{
    protected $serviceUrl;
    protected $notificationNetwork;

    /**
     * Constructor for the NotificationService class.
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
            "NOTIFICATION_API",
            env("SERVICE_BROKER_URL") .
                "/broker/greep-notification/" .
                env("APP_STATE")
        );
        $this->notificationNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    // Device token
    /**
     * Create a device token.
     *
     * @param array $request
     * @return mixed
     */
    public function createDeviceToken(array $request)
    {
        return $this->notificationNetwork->post("/v1/device-tokens", $request);
    }

    /**
     * Update a device token.
     *
     * @param array $request
     * @return mixed
     */
    public function updateDeviceToken(array $request)
    {
        return $this->notificationNetwork->put("/v1/device-tokens", $request);
    }

    // Notifications

    /**
     * Send a notification using a template.
     *
     * @param array $request
     * @return mixed
     */
    public function sendNotification(array $request)
    {
        return $this->notificationNetwork->post("/v1/notifications", $request);
    }

    /**
     * Update a notification.
     *
     * @param array $request
     * @return mixed
     */
    public function updateNotificationStatus(array $request)
    {
        return $this->notificationNetwork->put(
            "/v1/notifications/status",
            $request
        );
    }
}
