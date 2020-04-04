<?php

namespace Devel\Modules\Commands;

use Illuminate\Support\Str;
use Devel\Modules\Support\Config\GenerateConfigReader;
use Devel\Modules\Support\Stub;
use Devel\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class TestMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test class for the specified module.';

    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['devel-modules'];

        if ($this->option('feature') || $this->option('crud')) {
            return $module->config('paths.generator.test-feature.namespace') ?: $module->config('paths.generator.test-feature.path', 'Tests/Feature');
        }

        return $module->config('paths.generator.test.namespace') ?: $module->config('paths.generator.test.path', 'Tests/Unit');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the test class.'],
            ['module', InputArgument::OPTIONAL, 'Module to generate the test for.'],
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
            ['feature', false, InputOption::VALUE_NONE, 'Create a feature test.'],
            ['crud', false, InputOption::VALUE_NONE, 'Create a feature CRUD test.'],
            ['model', false, InputOption::VALUE_OPTIONAL, 'Model class to generate the test for.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['devel-modules']->findOrFail($this->getModuleName());
        $stub = '/unit-test.stub';

        $replacements = [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getClass(),
        ];

        if ($this->option('feature')) {
            $stub = '/feature-test.stub';
        }

        if ($this->option('crud')) {
            $stub = '/feature-crud-test.stub';

            $replacements = array_merge($replacements, [
                'MODEL_IMPORT' => $this->getModelImportReplacement(),
                'MODEL_SHORT' => $this->getShortModel(),
                'CRUD_NAME' => $this->getCrudName(),
                'MODULE_ALIAS' => $this->getModuleAlias(),
                'PK' => $this->getModelPrimaryKey(),
                'MAKE_VISIBLE' => $this->getModelMakeVisibleFields(),
                'MODULE_SEEDER' => $this->getModuleSeederClass(),
            ]);
        }

        return (new Stub($stub, $replacements))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['devel-modules']->getModulePath($this->getModuleName());

        if ($this->option('feature') || $this->option('crud')) {
            $testPath = GenerateConfigReader::read('test-feature');
        } else {
            $testPath = GenerateConfigReader::read('test');
        }

        return $path . $testPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Get the "use ...;" statement for the model.
     * 
     * @return string
     */
    private function getModelImportReplacement(): string
    {
        $model = $this->option('model');

        return $model !== 'Devel\Models\Auth\User'
            ? "\nuse {$model};"
            : '';
    }

    /**
     * Get CRUD (class base) name.
     *
     * @return string
     */
    protected function getCrudName(): string
    {
        return strtolower(Str::plural(class_basename($this->option('model'))));
    }

    /**
     * Get model's short class name.
     *
     * @return string
     */
    protected function getShortModel(): string
    {
        return class_basename($this->option('model'));
    }

    /**
     * Get model's primary key.
     *
     * @return string
     */
    protected function getModelPrimaryKey(): string
    {
        $model = $this->option('model');
        $model = new $model;

        return $model->getRouteKeyName();
    }

    /**
     * Model's fields that are supposed to be made visible.
     *
     * @return string
     */
    protected function getModelMakeVisibleFields(): string
    {
        $model = $this->option('model');
        $model = new $model;
        
        $fields = array_values(
            array_intersect($model->getFillable(), $model->getHidden())
        );

        $string = count($fields) ? "\n" : '';

        foreach ($fields as $field) {
            $string .= "                '{$field}',\n";
        }

        if (count($fields)) {
            $string .= "            ";
        }

        return $string;
    }

    /**
     * Module's seeder class name.
     *
     * @return string
     */
    protected function getModuleSeederClass(): string
    {
        return 'Modules\\' . $this->getModuleName() . '\Database\Seeders\\'
            . $this->getModuleName() . 'DatabaseSeeder';
    }
}
