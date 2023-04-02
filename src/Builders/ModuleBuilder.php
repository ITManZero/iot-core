<?php

namespace Ite\IotCore\Builders;

use Ite\IotCore\Models\Module;

class ModuleBuilder
{
    private string $name;

    public function setName(string $name): ModuleBuilder
    {
        $this->name = $name;
        return $this;
    }

    public function build(): Module
    {
        return (new Module())->create($this->name);
    }
}