<?php

namespace Devel\Modules\Commands;

use Illuminate\Support\Str;
use Devel\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\Command;
use Devel\Modules\Generators\ModuleGenerator;
use Symfony\Component\Console\Input\InputOption;

class CrudMakeCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dashboard CRUD for the specified module and model.';

    /**
     * A module holder.
     *
     * @var object
     */
    protected $module;

    /**
     * A ModuleGenerator instance.
     *
     * @var object
     */
    protected $generator;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the dependencies
        $this->module = $this->laravel['devel-modules'];

        $this->generator = with(new ModuleGenerator($this->getModuleName()))
            ->setFilesystem($this->laravel['files'])
            ->setModule($this->laravel['devel-modules'])
            ->setConfig($this->laravel['config'])
            ->setConsole($this)
            ->setModel($this->getModel());

        // Create a controller
        $this->call('module:make-controller', [
            'controller' => $this->getControllerName(),
            'module' => $this->getModuleName(),
            '--model' => $this->getModel(),
        ]);

        // Create a permissions seeder class for the model
        $this->call('module:make-seed', [
            'name' => $this->getCrudName(),
            'module' => $this->getModuleName(),
            '--permissions' => $this->getModel(),
        ]);

        // Create views
        $this->generateViews();

        // Add routes
        $this->addRoutes();

        // Create a CRUD feature test
        $this->call('module:make-test', [
            'name' => $this->getCrudName() . 'CrudTest',
            'module' => $this->getModuleName(),
            '--crud' => true,
            '--model' => $this->getModel(),
        ]);

        // Create a factory for the model if it doesn't exist yet
        $this->call('module:make-factory', [
            'name' => Str::singular($this->getCrudName()) . 'Factory',
            'module' => $this->getModuleName(),
            '--model' => $this->getModel(),
        ]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'The path of the model class.'],
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
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
            ['name', null, InputOption::VALUE_REQUIRED, 'Specify a module display name. Do not use this option manually.'],
        ];
    }

    /**
     * Get module name.
     *
     * @return void
     */
    protected function getModuleName()
    {
        return $this->argument('module');
    }

    /**
     * Get module name.
     *
     * @return void
     */
    protected function getModuleDisplayName()
    {
        $name = config(strtolower($this->getModuleName()) . '.display_name');

        if (!$name) {
            $name = $this->option('name');
        }

        return $name ?: $this->getModuleName();
    }

    /**
     * Get CRUD model class name.
     *
     * @return void
     */
    protected function getModel()
    {
        return $this->argument('model');
    }

    /**
     * Get CRUD controller name without the "Controller" word.
     *
     * @return void
     */
    protected function getControllerName()
    {
        return $this->getCrudName();
    }

    /**
     * Get CRUD (class base) name.
     *
     * @return void
     */
    protected function getCrudName()
    {
        return Str::plural(class_basename($this->getModel()));
    }

    /**
     * Get the list of the CRUD files to be created.
     *
     * @return array
     */
    protected function getFiles()
    {
        return $this->module->config('stubs.files-crud');
    }

    /**
     * Get contents of a stub class with all the wildcards replaced with correct
     * values.
     *
     * @param string $stub
     * @return string
     */
    protected function getStubContents(string $stub): string
    {
        $moduleParts = preg_split('/(?=[A-Z])/', $this->getModuleName(), -1, PREG_SPLIT_NO_EMPTY);
        $permissionGroup = strtolower(implode('_', $moduleParts));
        $content = $this->generator->getStubContents($stub);

        $replacements = [
            '$LOWER_NAME$' => strtolower($this->getModuleName()),
            '$STUDLY_NAME$' => $this->getModuleName(),
            '$DISPLAY_NAME$' => $this->getModuleDisplayName(),
            '$MODEL_NAME$' => $this->getModel(),
            '$CRUD_NAME_LOWER$' => strtolower($this->getCrudName()),
            '$CRUD_NAME$' => $this->getCrudName(),
            '$CONTROLLER_NAME$' => $this->getControllerName() . 'Controller',
            '$PERMISSION_GROUP$' => $permissionGroup,
            '$PERMISSION_EDIT$' => $this->getEditPermissionName(),
        ];

        foreach ($replacements as $from => $to) {
            $content = str_replace($from, $to, $content);
        }

        return $content;
    }

    /**
     * Generate the CRUD views from stubs.
     * 
     * @return void
     */
    protected function generateViews(): void
    {
        foreach ($this->getFiles() as $stub => $file) {
            if (substr($stub, 0, 5) !== 'views') {
                continue;
            }

            $file = str_replace(
                '$CRUD_NAME_LOWER$',
                strtolower($this->getCrudName()),
                $file
            );

            $path = $this->module->getModulePath($this->getModuleName()) . $file;

            if (!$this->generator->getFilesystem()->isDirectory($dir = dirname($path))) {
                $this->generator->getFilesystem()->makeDirectory($dir, 0775, true);
            }

            $this->generator->getFilesystem()->put($path, $this->getStubContents($stub));

            $this->info("Created view: {$path}");
        }
    }

    /**
     * Add dashboard routes for the CRUD.
     * 
     * @return void
     */
    protected function addRoutes(): void
    {
        $file = $this->getFiles()['routes/dashboard'] ?? null;
        $routesStub = $this->getStubContents('routes/crud');
        $fileExisted = true;

        if (!$file || !$routesStub) {
            return;
        }

        // Create the `dashboard.php` routes file if it hasn't been created yet
        $path = $this->module->getModulePath($this->getModuleName()) . $file;

        if (!$this->generator->getFilesystem()->exists($path)) {
            $fileExisted = false;

            $this->generator->getFilesystem()->put(
                $path, $this->getStubContents('routes/dashboard')
            );
        }

        // Get the file's content up to the last '});'
        $contents = $this->generator->getFilesystem()->get($path);
        $contents = substr($contents, 0, strrpos($contents, '});'));

        // Append the new CRUD routes
        $contents .= ($fileExisted ? "\n" : '') . $routesStub . "});\n";

        // Add routes for the CRUD
        $this->generator->getFilesystem()->put($path, $contents);

        $this->info("Generated CRUD routes for: {$this->getModel()}");
    }

    /**
     * Get the name of the edit permission
     *
     * @return void
     */
    protected function getEditPermissionName()
    {
        $name = \Str::plural(class_basename($this->getModel()));
        $nameLower = strtolower($name);

        return "edit_{$nameLower}";
    }
}
