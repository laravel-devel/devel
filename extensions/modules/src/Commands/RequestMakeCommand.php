<?php

namespace Extensions\Modules\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Extensions\Modules\Support\Config\GenerateConfigReader;
use Extensions\Modules\Support\Stub;
use Extensions\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class RequestMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class for the specified module.';

    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.request.namespace') ?: $module->config('paths.generator.request.path', 'Http/Requests');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the form request class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
            ['model', InputArgument::OPTIONAL, 'Specify a model if you want some rules to be automatically generated.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/request.stub', [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS'     => $this->getClass(),
            'RULES'     => $this->getRules(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $requestPath = GenerateConfigReader::read('request');

        return $path . $requestPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Get CRUD model class name.
     *
     * @return string
     */
    protected function getModel(): string
    {
        return $this->argument('model') ?? '';
    }

    /**
     * Generate validation rules based on the model.
     *
     * @return string
     */
    protected function getRules(): string
    {
        if (!$model = $this->getModel()) {
            return '            //';
        }

        $model = new $model;

        $rules = '';

        // Only include the fillable fields
        foreach ($model->getFillable() as $field) {
            $label = ucwords(implode(' ', explode('_', $field)));

            // Determine the field type from the DB type
            $type = DB::getSchemaBuilder()
                ->getColumnType($model->getTable(), $field);
   
            $rules .= "            '{$field}' => [\n";

            if ($type === 'boolean') {
                $rules .= "                'sometimes',\n";
            } else {
                $rules .= "                'required',\n";
                $rules .= "                '{$type}',\n";
            }

            if ($type === 'string') {
                $rules .= "                'max:191',\n";
            }

            $rules .= "            ],\n";
        }

        return $rules;
    }
}
