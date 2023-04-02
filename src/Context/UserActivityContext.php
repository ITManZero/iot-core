<?php

namespace Ite\IotCore\Context;

use Ite\IotCore\Models\User;

class UserActivityContext
{
    /**
     * The Manager of Users Activities.
     *
     * @var UserActivityManager
     */
    public UserActivityManager $manager;

    public function __construct(UserActivityManager $manager)
    {
        $this->manager = $manager;
    }

    public function getUsersActivities(): array
    {
        return $this->manager->getUserActivities();
    }

    public function hasUser(User|int $user): bool
    {
        $userActivities = $this->manager->getUserActivities();
        if (gettype($user) == 'integer')
            return !is_null($userActivities[$user]);
        return false;
    }

}