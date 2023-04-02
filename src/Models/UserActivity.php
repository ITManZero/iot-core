<?php

namespace Ite\IotCore\Models;


use DateTime;
use Ite\IotCore\Builders\UserActivityBuilder;

class UserActivity
{
    /**
     * @var int
     */
    public int $id;
    /**
     * Name
     * @var string
     */
    public string $name;
    /**
     * Action
     * @var string
     */
    public string $action;
    /**
     * Message
     * @var string
     */
    public string $message;
    /**
     * Is Admin
     * @var bool
     */
    public bool $isAdmin;

    /**
     * Expire Date time
     * @var DateTime
     */
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
