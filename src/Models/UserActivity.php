<?php

namespace Ite\IotCore\Models;

use Ite\IotCore\Builders\UserActivityBuilder;

class UserActivity
{
    /**
     * @var int | null
     */
    public ?int $id = null;
    /**
     * Name
     * @var string | null
     */
    public ?string $name = null;
    /**
     * Action
     * @var string | null
     */
    public ?string $action = null;
    /**
     * Message
     * @var string | null
     */
    public ?string $message = null;
    /**
     * Is Admin
     * @var bool
     */
    public bool $isAdmin = false;

    /**
     * Expire Date time
     * @var \DateTime | null
     */
    public ?\DateTime $expireAt = null;

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

    public function __toString()
    {
        return
            $this->id . ' '
            . $this->name . ' '
            . $this->action . ' '
            . $this->isAdmin . ' '
            . is_null($this->expireAt) ? "" : $this->expireAt->format("Y-m-d H:i:s");
    }

}
