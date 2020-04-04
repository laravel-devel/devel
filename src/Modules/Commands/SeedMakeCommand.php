<?php

namespace Devel\Modules\Commands;

use Illuminate\Support\Str;
use Devel\Modules\Support\Config\GenerateConfigReader;
use Devel\Modules\Support\Stub;
use Devel\Modules\Traits\CanClearModulesCache;
use Devel\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SeedMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait, CanClearModulesCache;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new seeder for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the seeder.'],
            ['module', InputArgument::OPTIONAL, 'Module to generate the seeder for.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                'master',
                null,
                InputOption::VALUE_NONE,
                'Indicates the seeder will created is a master database seeder.',
            ],
            [
                '--permissions',
                null,
                InputOption::VALUE_REQUIRED,
                'Name of the model to generate permissions for.',
            ],
            [
                '--settings',
                null,
                InputOption::VALUE_NONE,
                'Generate the SettingsSeeder for the module.',
            ],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['devel-modules']->findOrFail($this->getModuleName());
        $model = $this->option('permissions');

        if ($model) {
            // Register the seeder
            $name = $this->getPermissionsSeederName();
            $this->registerSeeder($name);

            // Create the seeder
            return (new Stub('/seeder-permissions.stub', [
                'NAME' => $name,
                'MODULE' => $this->getModuleName(),
                'NAMESPACE' => $this->getClassNamespace($module),
                'PERMISSIONS' => $this->getCrudPermissions($this->getModuleName(), $model),
            ]))->render();
        } else if ($this->option('settings')) { // Create the SettingsSeeder
            // Register the seeder
            $name = $this->getSeederName();
            $this->registerSeeder($name);

            // Create the seeder
            return (new Stub('/seeder-settings.stub', [
                'NAME' => $name,
                'MODULE' => $this->getModuleName(),
                'NAMESPACE' => $this->getClassNamespace($module),
                'PERMISSIONS' => $this->getSettingsPermissions($this->getModuleName()),
            ]))->render();
        } else {
            $file = $this->option('master') ? '/seeder-master.stub' : '/seeder.stub';

            return (new Stub($file, [
                'NAME' => $this->getSeederName(),
                'MODULE' => $this->getModuleName(),
                'NAMESPACE' => $this->getClassNamespace($module),
            ]))->render();
        }
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $this->clearCache();

        $path = $this->laravel['devel-modules']->getModulePath($this->getModuleName());

        $seederPath = GenerateConfigReader::read('seeder');

        return $path . $seederPath->getPath() . '/' . $this->getSeederName() . '.php';
    }

    /**
     * Get seeder name.
     *
     * @return string
     */
    private function getSeederName()
    {
        if ($this->option('settings')) {
            return Str::studly($this->argument('name'));
        } elseif ($this->option('permissions')) {
            $end = 'Seeder';
        } else {
            $end = $this->option('master') ? 'DatabaseSeeder' : 'TableSeeder';
        }

        return Str::studly($this->argument('name')) . $end;
    }

    /**
     * Get permissions seeder name.
     *
     * @return string
     */
    protected function getPermissionsSeederName()
    {
        return Str::studly($this->argument('name')) . 'Seeder';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['devel-modules'];

        return $module->config('paths.generator.seeder.namespace') ?: $module->config('paths.generator.seeder.path', 'Database/Seeders');
    }

    /**
     * Get the replacement for $PERMISSIONS$ for a permissions seeder
     *
     * @param string $module
     * @param string $model
     * @return string
     */
    public function getCrudPermissions(string $module, string $model): string
    {
        $name = \Str::plural(class_basename($model));
        $nameLower = strtolower($name);

        $moduleParts = preg_split('/(?=[A-Z])/', $module, -1, PREG_SPLIT_NO_EMPTY);
        $module = implode(' ', $moduleParts);
        $moduleLower = strtolower(implode('_', $moduleParts));

        $permissions = '';
        $permissions .= "'{$moduleLower}.list_{$nameLower}' => '{$module} - List {$name}',\n";
        $permissions .= "        '{$moduleLower}.view_{$nameLower}' => '{$module} - View {$name}',\n";
        $permissions .= "        '{$moduleLower}.add_{$nameLower}' => '{$module} - Add {$name}',\n";
        $permissions .= "        '{$moduleLower}.edit_{$nameLower}' => '{$module} - Edit {$name}',\n";
        $permissions .= "        '{$moduleLower}.delete_{$nameLower}' => '{$module} - Delete {$name}',";

        return $permissions;
    }

    /**
     * Get the replacement for $PERMISSIONS$ for a SettingsSeeder
     *
     * @param string $module
     * @return string
     */
    public function getSettingsPermissions(string $module): string
    {
        $moduleParts = preg_split('/(?=[A-Z])/', $module, -1, PREG_SPLIT_NO_EMPTY);
        $moduleLower = strtolower(implode('_', $moduleParts));

        $permissions = "'{$moduleLower}.edit_settings' => 'Edit Module Settings',";

        return $permissions;
    }

    /**
     * Register a seeder
     *
     * @param string $className
     * @return void
     */
    protected function registerSeeder(string $className): void
    {
        // Register the seeder
        $path = module_path($this->getModuleName(), 'Database/Seeders/' . $this->getModuleName()) . 'DatabaseSeeder.php';

        // The 'run' method
        $contents = $this->laravel['files']->get($path);
        preg_match('/public function run\(\)\R[\s]{4}{((?>[^{}]++|(?R))*)}/', $contents, $matches);

        $contents = str_replace(
            $matches[1],
            $matches[1] . "    \$this->call({$className}::class);\n    ",
            $contents
        );

        $this->laravel['files']->put($path, $contents);

        // The 'revert' method
        $contents = $this->laravel['files']->get($path);
        preg_match('/public function revert\(\)\R[\s]{4}{((?>[^{}]++|(?R))*)}/', $contents, $matches);

        $contents = str_replace(
            $matches[1],
            $matches[1] . "    \$this->uncall({$className}::class);\n    ",
            $contents
        );

        $this->laravel['files']->put($path, $contents);
    }
}
