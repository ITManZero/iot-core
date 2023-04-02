<?php

namespace Ite\IotCore\Context;

use DateTime;
use Illuminate\Support\Facades\Cache;
use Ite\IotCore\Models\UserActivity;

class UserActivityContext
{
    public function add(UserActivity $userActivity): bool
    {
        $context = $this->getUserActivities();
        dd($context);
        $size = count($context);
        $context[] = $userActivity;
        $this->setUserActivities($context);
        return count($context) == $size + 1;
    }

    public function remove(UserActivity $userActivity): bool
    {
        return true;
    }

    public function getUserActivities(): array|string
    {
        return Cache::get(UserActivityContext::class);
    }

    public function setUserActivities(array $userActivities): void
    {
        Cache::put(UserActivityContext::class, $userActivities);
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
        $context = array_filter($this->getUserActivities(), function ($userActivity) {
            return $userActivity->expireAt > new DateTime();
        });
        $this->setUserActivities($context);
    }
}