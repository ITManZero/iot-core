<?php

namespace Ite\IotCore\Context;

use Ite\IotCore\Managers\UserActivityManager;
use Ite\IotCore\Models\PayloadUser;

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
        return $this->manager->get();
    }

    public function hasUser(PayloadUser|int $user): bool
    {
        $userActivities = $this->manager->get();
        if (gettype($user) == 'integer') {
            $user = $userActivities[$user] ?? null;
            return !is_null($user);
        }
        return false;
    }

}