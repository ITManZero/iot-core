<?php

namespace Ite\IotCore\Context;

use DateTime;
use Ite\IotCore\Models\UserActivity;

class UserActivityContext
{
    private static array $userActivities = [];
    

    public function add(UserActivity $userActivity): bool
    {
        $size = count(UserActivityContext::$userActivities);
        UserActivityContext::$userActivities[] = $userActivity;
        return count(UserActivityContext::$userActivities) == $size + 1;
    }

    public function remove(UserActivity $userActivity): bool
    {
        $size = count(UserActivityContext::$userActivities);
        UserActivityContext::$userActivities[] = $userActivity;
        return count(UserActivityContext::$userActivities) == $size - 1;
    }

    public function getUserActivities(): array
    {
        return UserActivityContext::$userActivities;
    }

    public function setUserActivities(array $userActivities): void
    {
        UserActivityContext::$userActivities = $userActivities;
    }

    public function clear(): void
    {
        UserActivityContext::$userActivities = [];
    }

    public function clearExpired(): void
    {
        array_filter(UserActivityContext::$userActivities, function ($userActivity) {
            /**
             * @var UserActivity $userActivity
             */
            return $userActivity->expireAt > new DateTime();
        });
    }
}