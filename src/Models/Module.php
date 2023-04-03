<?php

namespace Ite\IotCore\Models;

use Ite\IotCore\Builders\ModuleBuilder;

class Module
{
    /**
     * Name
     * @var string | null
     */
    public string|null $name = null;

    public function create(string $name): Module
    {
        $this->name = $name;
        return $this;
    }

    public static function builder(): ModuleBuilder
    {
        return new ModuleBuilder();
    }

    public function __toString()
    {
        return $this->name;
    }

}
