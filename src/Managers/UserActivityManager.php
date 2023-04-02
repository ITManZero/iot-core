<?php

namespace Ite\IotCore\Managers;

class UserActivityManager extends BaseManager
{

    public function __construct()
    {
        parent::__construct(UserActivityManager::class);
    }

    public function add(mixed $userActivity): bool
    {
        $userActivities = $this->get();
        $size = count($userActivities);
        $userActivities[$userActivity->id] = $userActivity;
        $this->set($userActivities);
        return count($userActivities) == $size + 1;
    }

    public function remove(mixed $userId): bool
    {
        $userActivities = $this->get();
        $size = count($userActivities);
        if (is_null($userActivities[$userId])) return false;
        unset($userActivities[$userId]);
        $this->set($userActivities);
        return count($userActivities) == $size + -1;
    }

    public function clean(): void
    {
        $userActivities = array_filter($this->get(), function ($userActivity) {
            return $userActivity->expireAt > new \DateTime();
        });
        $this->set($userActivities);
    }

    public function initValue(): array
    {
        return [];
    }
}