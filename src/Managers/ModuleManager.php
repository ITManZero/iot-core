<?php

namespace Ite\IotCore\Managers;

class ModuleManager extends BaseManager
{

    public function __construct()
    {
        parent::__construct(ModuleManager::class);
    }

    public function add(mixed $value): bool
    {
        return false;
    }

    public function remove(mixed $value): bool
    {
        return false;
    }

    public function initValue(): string
    {
        return env('MODULE_NAME') ?? "";
    }
}