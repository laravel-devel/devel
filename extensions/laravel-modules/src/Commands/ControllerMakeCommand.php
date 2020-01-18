<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'controller';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful controller for the specified module.';

    protected $dbToFormTypes = [
        'string' => 'text',
        'boolean' => 'checkbox',
    ];

    /**
     * Get controller name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $controllerPath = GenerateConfigReader::read('controller');

        return $path . $controllerPath->getPath() . '/' . $this->getControllerName() . '.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME'        => $module->getStudlyName(),
            'CONTROLLERNAME'    => $this->getControllerName(),
            'NAMESPACE'         => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getControllerNameWithoutNamespace(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODEL'             => $this->getModel(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
            'MODEL_DATATABLE'   => $this->generateDatatable(),
            'MODEL_FORM'        => $this->generateForm(),
            'REQUEST_CLASS'     => $this->getFormRequestClass(),
        ]))->render();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['controller', InputArgument::REQUIRED, 'The name of the controller class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain controller', null],
            ['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.'],
            ['model', null, InputOption::VALUE_OPTIONAL, 'A model to generate CRUD for.'],
        ];
    }

    /**
     * @return array|string
     */
    protected function getControllerName()
    {
        $controller = Str::studly($this->argument('controller'));

        if (Str::contains(strtolower($controller), 'controller') === false) {
            $controller .= 'Controller';
        }

        return $controller;
    }

    /**
     * @return array|string
     */
    private function getControllerNameWithoutNamespace()
    {
        return class_basename($this->getControllerName());
    }

    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.controller.namespace') ?: $module->config('paths.generator.controller.path', 'Http/Controllers');
    }

    /**
     * Get the stub file name based on the options
     * @return string
     */
    private function getStubName()
    {
        if ($this->option('plain') === true) {
            $stub = '/controller-plain.stub';
        } elseif ($this->option('api') === true) {
            $stub = '/controller-api.stub';
        } elseif (!empty($this->option('model'))) {
            $stub = '/controller-crud.stub';
        } else {
            $stub = '/controller.stub';
        }

        return $stub;
    }

    /**
     * Get CRUD model class name.
     *
     * @return void
     */
    protected function getModel()
    {
        return $this->option('model') ?? '';
    }

    /**
     * Get form request name.
     *
     * @return string
     */
    protected function getFormRequestName(): string
    {
        if ($model = $this->getModel()) {
            $modelName = explode('\\', $model);

            return array_pop($modelName) . 'Request';
        }
        
        return '';
    }

    /**
     * Get form request class name.
     *
     * @return string
     */
    protected function getFormRequestClass(): string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return 'Modules\\' . $module->getStudlyName() . '\Http\Requests\\' .$this->getFormRequestName();
    }

    /**
     * Generate datatable fields list.
     *
     * @return string
     */
    protected function generateDatatable(): string
    {
        if (!$model = $this->getModel()) {
            return '[]';
        }

        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        $model = new $model;
        $idKey = $model->getRouteKeyName();
        $table = $model->getTable();
        $fields = Schema::getColumnListing($table);

        // Exclude some generic fields
        $fields = array_diff($fields, [
            'id',
            'email_verified_at',
            'created_at',
            'updated_at',
        ]);

        // Exclude hidden fields
        $fields = array_diff($fields, $model->getHidden());

        $values = "[\n";

        foreach ($fields as $field) {
            $name = ucwords(implode(' ', explode('_', $field)));
            $values .= "            '{$field}' => [\n";
            $values .= "                'name' => '{$name}',\n";
            $values .= "                'sortable' => true,\n";
            $values .= "            ],\n";
        }

        $values .= "        ], [\n";
        $values .= "            'delete' => route('dashboard.{$module->getLowerName()}.destroy', ':{$idKey}'),\n";
        $values .= "            'create' => route('dashboard.{$module->getLowerName()}.create'),\n";
        $values .= "            'edit' => route('dashboard.{$module->getLowerName()}.edit', ':{$idKey}'),\n";
        $values .= "        ]";

        return $values;
    }

    /**
     * Generate form fields list.
     *
     * @return string
     */
    protected function generateForm(): string
    {
        if (!$model = $this->getModel()) {
            return '[]';
        }

        $model = new $model;
        
        $values = "[\n";

        // Only include the fillable fields
        foreach ($model->getFillable() as $field) {
            $label = ucwords(implode(' ', explode('_', $field)));

            // Determine the field type from the DB type
            $columnType = DB::getSchemaBuilder()
                ->getColumnType($model->getTable(), $field);

            $type = $this->dbToFormTypes[$columnType] ?? 'text';

            $values .= "            [\n";
            $values .= "                'type' => '{$type}',\n";
            $values .= "                'name' => '{$field}',\n";
            $values .= "                'label' => '{$label}',\n";
            $values .= "            ],\n";
        }

        $values .= "        ]";

        return $values;
    }

    /**
     * Additional actions to be performed after the generation.
     *
     * @return void
     */
    protected function after(): void
    {
        // Generate a Request if a model is specified
        if ($model = $this->getModel()) {
            $module = $this->laravel['modules']->findOrFail($this->getModuleName());

            Artisan::call('module:make-request', [
                'name' => $this->getFormRequestName(),
                'module' => $module->getName(),
                'model' => $model,
            ]);
        }
    }
}
