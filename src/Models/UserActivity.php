<?php

namespace Ite\IotCore\Models;


use DateTime;
use Ite\IotCore\Builders\UserActivityBuilder;

class UserActivity
{
    public int $id;
    public string $name;
    public string $action;
    public string $message;
    public bool $isAdmin;
    public DateTime $expireAt;

    public function create(int $id, string $name, string $action, string $message, bool $isAdmin): UserActivity
    {
        $this->id = $id;
        $this->name = $name;
        $this->action = $action;
        $this->message = $message;
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public static function builder(): UserActivityBuilder
    {
        return new UserActivityBuilder();
    }

}
