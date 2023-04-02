<?php

namespace Ite\IotCore\Managers;

class ModuleManager extends BaseManager
{

    public function add(mixed $value): bool
    {
        return false;
    }

    public function remove(mixed $value): bool
    {
        return false;
    }

    public function init(): mixed
    {
        return env('MODULE_NAME');
    }
}