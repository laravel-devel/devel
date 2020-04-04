<?php

namespace Devel\Modules\Traits;

trait ModuleCommandTrait
{
    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        $module = $this->argument('module') ?: app('devel-modules')->getUsedNow();

        $module = app('devel-modules')->findOrFail($module);

        return $module->getStudlyName();
    }

    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModuleAlias()
    {
        $module = $this->argument('module') ?: app('devel-modules')->getUsedNow();

        $module = app('devel-modules')->findOrFail($module);

        return $module->getAlias();
    }
}
