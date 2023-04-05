<?php

namespace Ite\IotCore\Context;

use Ite\IotCore\Managers\ModuleManager;

class ModuleContext
{
    /**
     * The Manager of Module.
     *
     * @var ModuleManager
     */
    private ModuleManager $manager;

    public function __construct(ModuleManager $manager)
    {
        $this->manager = $manager;
    }

    public function currentModule(): string
    {
        return $this->manager->get() ?? '';
    }

    public function isAdminModule(): string
    {
        return $this->currentModule() == 'admin' && $this->currentModule() != '';
    }

}