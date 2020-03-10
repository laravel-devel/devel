<?php

namespace Devel\Core\Services;

use Devel\Modules\Facades\Module;

class ModuleService
{
    /**
     * Check whether all the dependencies of a module are installed
     *
     * @param Module $module
     * @return array
     */
    public static function checkDependencies($module): array
    {
        $moduleName = $module->getName();
        $errors = [];

        foreach ($module->getRequires() as $name) {
            try {
                $dependency = Module::findOrFail($name);
            } catch (\Exception $e) {
                $errors[] = "Module \"{$name}\" required by \"{$moduleName}\" was not found!";
            }

            if (!$dependency->isEnabled()) {
                $errors[] = "Module \"{$name}\" required by \"{$moduleName}\" is not enabled!";
            }
        }

        return $errors;
    }
}
