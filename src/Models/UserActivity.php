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
     * Full name
     * @var string
     */
    public string $name;
    /**
     * Full name
     * @var string
     */
    public string $action;
    /**
     * Full name
     * @var string
     */
    public string $message;
    /**
     * Full name
     * @var bool
     */
    public bool $isAdmin;

    /**
     * Full name
     * @var DateTime
     */
    public DateTime $expireAt;

//    public function create(int $id, string $name, string $action, string $message, bool $isAdmin): UserActivity
//    {
//        $this->id = $id;
//        $this->name = $name;
//        $this->action = $action;
//        $this->message = $message;
//        $this->isAdmin = $isAdmin;
//        return $this;
//    }
//
//    public static function builder(): UserActivityBuilder
//    {
//        return new UserActivityBuilder();
//    }

}
