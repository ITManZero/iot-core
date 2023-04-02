<?php

namespace Ite\IotCore\Managers;

use Illuminate\Support\Facades\Cache;
use Ite\IotCore\Models\UserActivity;

abstract class BaseManager
{
    protected string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public abstract function add(mixed $value): bool;

    public abstract function remove(mixed $value): bool;

    public function get(): mixed
    {
        return Cache::get($this->key);
    }

    public function set(mixed $value): void
    {
        Cache::put($this->key, $value);
    }

    public abstract function init(): mixed;

    public function clear(): void
    {
        $this->set($this->init());
    }
}