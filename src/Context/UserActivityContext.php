<?php

namespace Ite\IotCore\Context;

use DateTime;
use Ite\IotCore\Models\UserActivity;

class UserActivityContext
{
    public array $userActivities;

    public function __construct()
    {
        $this->userActivities = [];
    }

    public function add(UserActivity $userActivity): bool
    {
        $size = count($this->userActivities);
        $this->userActivities[] = $userActivity;
        return count($this->userActivities) == $size + 1;
    }

    public function remove(UserActivity $userActivity): bool
    {
        $size = count($this->userActivities);
        $this->userActivities[] = $userActivity;
        return count($this->userActivities) == $size - 1;
    }

    public function clear(): void
    {
        $this->userActivities = [];
    }

    public function clearExpired(): void
    {
        array_filter($this->userActivities, function ($userActivity) {
            /**
             * @var UserActivity $userActivity
             */
            return $userActivity->expireAt > new DateTime();
        });
    }
}