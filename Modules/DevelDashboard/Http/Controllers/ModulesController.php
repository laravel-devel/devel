<?php

namespace Modules\DevelDashboard\Http\Controllers;

use Devel\Core\Http\Controllers\Controller;
use Devel\Core\Services\ModuleService;
use Devel\Modules\Facades\Module;

class ModulesController extends Controller
{
    protected $protectedModules = [
        'DevelDashboard',
        'Main',
    ];

    /**
     * Modules list page
     *
     * @return void
     */
    public function index()
    {
        $this->setMeta('title', 'Manage Modules');

        $modules = Module::getOrdered();

        foreach ($modules as $key => $module) {
            // Some modules could not be disabled and shouldn't be visible
            if (in_array($key, $this->protectedModules)) {
                unset($modules[$key]);

                continue;
            }

            $modules[$key] = [
                'displayName' => config($module->getLowerName() . '.display_name'),
                'name' => $module->getName(),
                'alias' => $module->getAlias(),
                'description' => $module->getDescription(),
                'enabled' => $module->isEnabled(),
            ];
        }

        return view('develdashboard::modules.index', [
            'modules' => $modules,
        ]);
    }

    public function toggleEnabled(string $alias)
    {
        try {
            $module = Module::findOrFail($alias);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Module with alias \"{$alias}\" not found.",
            ], 404);
        }

        $name = $module->getName();

        // Certain modules cannot be disabled
        if (in_array($module->getName(), $this->protectedModules)) {
            return response()->json([
                'message' => "Module \"{$name}\" cannot be disabled.",
            ], 422);
        }

        // A module cannot be enabled if its dependencies are not met
        if (!$module->isEnabled()) { 
            $depenencyErrors = ModuleService::checkDependencies($module);

            if (count($depenencyErrors)) {
                $msg = "Module \"{$name}\" has unmet dependencies and cannot be enabled:";

                foreach ($depenencyErrors as $error) {
                    $msg .= "\n- {$error}";
                }

                return response()->json([
                    'message' => $msg,
                ], 422);
            }
        }

        if ($module->isEnabled()) {
            $module->disable();
        } else {
            $module->enable();
        }

        return response()->json([]);
    }
}
