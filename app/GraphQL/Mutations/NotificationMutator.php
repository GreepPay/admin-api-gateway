<?php

namespace App\GraphQL\Mutations;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

final class NotificationMutator
{
    /**
     * @var NotificationService
     */
    protected $notificationService;

    /**
     * NotificationMutator constructor.
     */
    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    /**
     * Mark specified notifications as read for the authenticated user.
     *
     * @param  mixed  $_      The root value (not used in this case).
     * @param  array  $args   The arguments passed to the mutation, containing an array of notification_ids.
     *
     * @return bool  True if the notifications were successfully marked as read, false otherwise.
     */
    public function markNotificationsAsRead($_, array $args): bool
    {
        $authUser = Auth::user();

        $allNotificationsToMark = $args["notification_ids"];

        foreach ($allNotificationsToMark as $notificationId) {
            $this->notificationService->updateNotificationStatus([
                "auth_user_id" => $authUser->id,
                "notification_id" => $notificationId,
                "is_read" => true,
            ]);
        }

        return true;
    }
}
