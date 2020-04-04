<?php

namespace Devel\Modules\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Devel\Modules\Support\Config\GenerateConfigReader;
use Devel\Modules\Support\Stub;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;
use Devel\Modules\Traits\ModuleCommandTrait;
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
        $module = $this->laravel['devel-modules'];

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
            ['module', InputArgument::OPTIONAL, 'Module to generate the form request for.'],
            ['model', InputArgument::OPTIONAL, 'Specify a model if you want some default rules to be generated for you.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['devel-modules']->findOrFail($this->getModuleName());

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
        $path = $this->laravel['devel-modules']->getModulePath($this->getModuleName());

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
            // Determine the field type from the DB type
            // TODO: This block of code repeats in 3 classes. Extract to a
            // service, something like SchemaService or DbService
            try {
                $type = DB::getSchemaBuilder()
                    ->getColumnType($model->getTable(), $field);
            } catch (SchemaException $e) {
                throw new \Exception($e->getMessage() . ' Did you run the migrations?');
            } catch (DBALException $e) {
                // Some column types like "enum" through a DBALException.
                // This is because "enum" is a custom type and is not supported
                // by all the DBs. We're going to default to the string type in
                // these cases.
                $type = 'string';
            } catch (\Exception $e) {
                throw $e;
            }
   
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

        // Remove the last extra "\n"
        $rules = substr($rules, 0, strlen($rules) - 2);

        return $rules;
    }
}
