<?php

namespace Ite\IotCore\Context;

use DateTime;
use Illuminate\Support\Facades\Cache;
use Ite\IotCore\Models\UserActivity;

class UserActivityManager
{
    public function add(UserActivity $userActivity): bool
    {
        $userActivities = $this->getUserActivities();
        $size = count($userActivities);
        $userActivities[$userActivity->id] = $userActivity;
        $this->setUserActivities($userActivities);
        return count($userActivities) == $size + 1;
    }

    public function remove(int $userId): bool
    {
        $userActivities = $this->getUserActivities();
        $size = count($userActivities);
        if (is_null($userActivities[$userId])) return false;
        unset($userActivities[$userId]);
        $this->setUserActivities($userActivities);
        return count($userActivities) == $size + -1;;
    }

    public function getUserActivities(): array|null
    {
        return Cache::get(UserActivityManager::class);
    }

    public function setUserActivities(array $userActivities): void
    {
        Cache::put(UserActivityManager::class, $userActivities);
    }

    public function clear(): void
    {
        $this->setUserActivities([]);
    }

    public function init(): void
    {
        if (is_null($this->getUserActivities()))
            $this->clear();
    }

    public function clearExpired(): void
    {
        $userActivities = array_filter($this->getUserActivities(), function ($userActivity) {
            return $userActivity->expireAt > new DateTime();
        });
        $this->setUserActivities($userActivities);
    }
}