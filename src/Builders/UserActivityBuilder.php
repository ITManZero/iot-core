<?php

namespace Ite\IotCore\Builders;

use Ite\IotCore\Models\UserActivity;

class UserActivityBuilder
{
    private int $id;
    private string $name;
    private string $action;
    private string $message;
    private bool $isAdmin;

    public function setId(int $id): UserActivityBuilder
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): UserActivityBuilder
    {
        $this->name = $name;
        return $this;
    }


    public function setAction(string $action): UserActivityBuilder
    {
        $this->action = $action;
        return $this;

    }

    public function setMessage(string $message): UserActivityBuilder
    {
        $this->message = $message;
        return $this;
    }

    public function setIsAdmin(bool $isAdmin): UserActivityBuilder
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public function build(): UserActivity
    {
        return (new UserActivity())->create(
            $this->id,
            $this->name,
            $this->action,
            $this->message,
            $this->isAdmin);
    }
}