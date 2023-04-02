<?php

namespace Ite\IotCore\Context;

use DateTime;
use Ite\IotCore\Models\UserActivity;

class UserActivityContext
{
    private array $userActivities;
    private static UserActivityContext|null $instance = null;

    private function __construct()
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

    public function getUserActivities(): array
    {
        return $this->userActivities;
    }

    public function setUserActivities(array $userActivities): void
    {
        $this->userActivities = $userActivities;
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

    public static function getInstance(): UserActivityContext
    {
        if (is_null(UserActivityContext::$instance))
            UserActivityContext::$instance = new UserActivityContext();
        return UserActivityContext::$instance;
    }
}