<?php

namespace Devel\Modules\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Devel\Modules\Support\Config\GenerateConfigReader;
use Devel\Modules\Support\Stub;
use Devel\Modules\Traits\ModuleCommandTrait;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\SchemaException;
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
    protected $description = 'Generate new controller for the specified module.';

    protected $dbToFormTypes = [
        'string' => 'text',
        'boolean' => 'checkbox',
    ];

    protected $relationshipTypes = [
        'HasOne' => 'select',
        'BelongsToOne' => 'select',
        'HasMany' => 'multiselect',
        'BelongsToMany' => 'multiselect',
    ];

    /**
     * Get controller name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['devel-modules']->getModulePath($this->getModuleName());

        $controllerPath = GenerateConfigReader::read('controller');

        return $path . $controllerPath->getPath() . '/' . $this->getControllerName() . '.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['devel-modules']->findOrFail($this->getModuleName());

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
            'MODEL_LOWER'       => $this->getModelLowerName(),
            'MODULE_NAMESPACE'  => $this->laravel['devel-modules']->config('namespace'),
            'MODEL_DATATABLE'   => $this->generateDatatable(),
            'MODEL_FORM'        => $this->generateForm(),
            'REQUEST_CLASS'     => $this->getFormRequestClass(),
            'FORM_COLLECTIONS'  => $this->getFormCollections(),
            'EDIT_MODEL_LOADS'  => $this->getEditModalLoads(),
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
            ['module', InputArgument::OPTIONAL, 'Module to generate the controller for.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain controller', null],
            ['api', null, InputOption::VALUE_NONE, 'Generate an API controller.'],
            ['settings', null, InputOption::VALUE_NONE, 'Generate the SettingsController for the module.'],
            ['model', null, InputOption::VALUE_OPTIONAL, 'A model to generate CRUD for.'],
        ];
    }

    /**
     * @return array|string
     */
    protected function getControllerName()
    {
        if ($this->option('settings') === true) {
            return 'SettingsController';
        }

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

    public function getDefaultNamespace(): string
    {
        $module = $this->laravel['devel-modules'];

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
        } elseif ($this->option('settings') === true) {
            $stub = '/controller-settings.stub';
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
     * @return string
     */
    protected function getModel(): string
    {
        return $this->option('model') ?? '';
    }

    /**
     * Get CRUD model lower name name.
     *
     * @return void
     */
    protected function getModelLowerName()
    {
        return Str::plural(strtolower(class_basename($this->getModel())));
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
        $module = $this->laravel['devel-modules']->findOrFail($this->getModuleName());

        return 'Modules\\' . $module->getStudlyName() . '\Http\Requests\\' . $this->getFormRequestName();
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

        $module = $this->laravel['devel-modules']->findOrFail($this->getModuleName());

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
        $values .= "            'delete' => ['dashboard.{$module->getLowerName()}.{$this->getModelLowerName()}.destroy', ':{$idKey}'],\n";
        $values .= "            'create' => ['dashboard.{$module->getLowerName()}.{$this->getModelLowerName()}.create'],\n";
        $values .= "            'edit' => ['dashboard.{$module->getLowerName()}.{$this->getModelLowerName()}.edit', ':{$idKey}'],\n";
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
        $values .= "            'Main' => [\n";

        // Only include the fillable fields
        foreach ($model->getFillable() as $field) {
            $label = ucwords(implode(' ', explode('_', $field)));

            // Determine the field type from the DB type
            // TODO: This block of code repeats in 3 classes. Extract to a
            // service, something like SchemaService or DbService
            try {
                $columnType = DB::getSchemaBuilder()
                    ->getColumnType($model->getTable(), $field);
            } catch (SchemaException $e) {
                throw new \Exception($e->getMessage() . ' Did you run the migrations?');
            } catch (DBALException $e) {
                // Some column types like "enum" through a DBALException.
                // This is because "enum" is a custom type and is not supported
                // by all the DBs. We're going to default to the string type in
                // these cases.
                $columnType = 'string';
            } catch (\Exception $e) {
                throw $e;
            }

            $type = $this->dbToFormTypes[$columnType] ?? 'text';

            $values .= "                [\n";
            $values .= "                    'type' => '{$type}',\n";
            $values .= "                    'name' => '{$field}',\n";
            $values .= "                    'label' => '{$label}',\n";
            $values .= "                ],\n";
        }

        // Include the Model's relationships
        $relationships = $model->getRelationships();

        foreach ($relationships as $name => $attrs) {
            if (!isset($this->relationshipTypes[$attrs['type']])) {
                continue;
            }

            $label = ucwords(implode(' ', explode('_', $name)));
            $type = $this->relationshipTypes[$attrs['type']];

            $idField = $attrs['relation']->getRelated()->getRouteKeyName();
            $multipleChoice = ($type === 'multiselect') ? 'true' : 'false';

            // The multiselect is just a select with $multipleChoice === true
            if ($type === 'multiselect') {
                $type = 'select';
            }

            $values .= "                [\n";
            $values .= "                    'type' => '{$type}',\n";
            $values .= "                    'name' => '{$name}',\n";
            $values .= "                    'label' => '{$label}',\n";
            $values .= "                    'attrs' => [\n";
            $values .= "                        'idField' => '{$idField}',\n";
            $values .= "                        'textField' => '{$idField}',\n";
            $values .= "                        'multipleChoice' => {$multipleChoice},\n";
            $values .= "                    ],\n";
            $values .= "                ],\n";
        }

        $values .= "            ],\n";
        $values .= "        ]";

        return $values;
    }

    /**
     * Get collections of all the values to use in the form for the
     * relationships.
     *
     * @return string
     */
    protected function getFormCollections(): string
    {
        if (!$model = $this->getModel()) {
            return '[]';
        }

        $model = new $model;

        $relationships = $model->getRelationships();

        if (!count($relationships)) {
            return '[]';
        }

        $values = "[\n";

        foreach ($relationships as $name => $attrs) {
            if (!isset($this->relationshipTypes[$attrs['type']])) {
                continue;
            }

            $relatedModel = get_class($attrs['relation']->getRelated());

            $values .= "                '{$name}' => \\{$relatedModel}::all(),\n";
        }

        $values .= '            ]';

        return $values;
    }

    /**
     * Get the list of relationships to load with the model when editing an
     * item.
     *
     * @return string
     */
    protected function getEditModalLoads(): string
    {
        if (!$model = $this->getModel()) {
            return '';
        }

        $model = new $model;

        $relationships = $model->getRelationships();

        if (!count($relationships)) {
            return '';
        }

        $values = "->load([\n";

        foreach ($relationships as $name => $attrs) {
            if (!isset($this->relationshipTypes[$attrs['type']])) {
                continue;
            }

            $values .= "            '{$name}',\n";
        }

        $values .= '        ])';

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
            $module = $this->laravel['devel-modules']->findOrFail($this->getModuleName());

            Artisan::call('module:make-request', [
                'name' => $this->getFormRequestName(),
                'module' => $module->getName(),
                'model' => $model,
            ]);
        }
    }
}
