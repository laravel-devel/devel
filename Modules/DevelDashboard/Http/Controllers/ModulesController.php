<?php

namespace Modules\DevelDashboard\Http\Controllers;

use Devel\Core\Http\Controllers\Controller;
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

        $modules = Module::all();

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

        // Certain modules cannot be disabled
        if (in_array($module->getName(), $this->protectedModules)) {
            return response()->json([
                'message' => "Module \"{$alias}\" cannot be disabled.",
            ], 422);
        }

        if ($module->isEnabled()) {
            $module->disable();
        } else {
            $module->enable();
        }

        return response()->json([]);
    }
}
