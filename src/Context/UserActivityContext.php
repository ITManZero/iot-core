<?php

namespace Ite\IotCore\Context;

use DateTime;
use Ite\IotCore\Models\UserActivity;

class UserActivityContext
{
    public array $userActivity;

    public function add(UserActivity $userActivity): bool
    {
        $size = count($this->userActivity);
        $this->userActivity[] = $userActivity;
        return count($this->userActivity) == $size + 1;
    }

    public function remove(UserActivity $userActivity): bool
    {
        $size = count($this->userActivity);
        $this->userActivity[] = $userActivity;
        return count($this->userActivity) == $size - 1;
    }

    public function clear(): void
    {
        $this->userActivity = [];
    }

    public function clearExpired(): void
    {
        array_filter($this->userActivity, function ($userActivity) {
            /**
             * @var UserActivity $userActivity
             */
            return $userActivity->expireAt > new DateTime();
        });
    }
}