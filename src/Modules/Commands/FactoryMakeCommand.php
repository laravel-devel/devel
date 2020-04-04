<?php

namespace Devel\Modules\Commands;

use Illuminate\Support\Str;
use Devel\Modules\Support\Config\GenerateConfigReader;
use Devel\Modules\Support\Stub;
use Devel\Modules\Traits\ModuleCommandTrait;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FactoryMakeCommand extends GeneratorCommand
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
    protected $name = 'module:make-factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model factory for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the factory.'],
            ['module', InputArgument::OPTIONAL, 'Module to generate the factory for.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', null, InputOption::VALUE_OPTIONAL, 'A model to generate the factory for.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        if (!$this->options('model')) {
            return (new Stub('/factory.stub'))->render();
        }

        return (new Stub('/factory-model.stub', [
            'CLASS' => $this->option('model'),
            'FIELDS' => $this->getModelFields(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        // Special case for a User factory
        if ($this->argument('module') === $this->argument('module')) {
            return database_path('factories') . '/' . $this->getFileName();
        }

        $path = $this->laravel['devel-modules']->getModulePath($this->getModuleName());

        $factoryPath = GenerateConfigReader::read('factory');

        return $path . $factoryPath->getPath() . '/' . $this->getFileName();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name')) . '.php';
    }

    protected function getModelFields(): string
    {
        $class = $this->option('model');
        $model = new $class;

        $code = '';

        // Only include the fillable fields
        foreach ($model->getFillable() as $field) {
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

            $type = $this->getFakerType($field, $columnType);

            $code .= "        '{$field}' => $type,\n";
        }

        // Remove the last \n
        $code = substr($code, 0, strlen($code) - 1);

        return $code;
    }

    protected function getFakerType(string $field, string $columnType): string
    {
        $nameTypes = [
            'name' => '$faker->name',
            'first_name' => '$faker->firstName()',
            'last_name' => '$faker->lastName()',
            'uuid' => '$faker->uuid',
            'email' => '$faker->unique()->safeEmail',
            'email_verified_at' => 'now()',
            'url' => '$faker->url',
            'password' => 'Illuminate\Support\Facades\Hash::make(\'qwerty\')',
            'remember_token' => 'Str::random(10)',
        ];

        $columnTypes = [
            'string' => '$faker->uuid',
            'text' => '$faker->paragraph',
            'boolean' => '$faker->boolean',
        ];

        if (isset($nameTypes[$field])) {
            return $nameTypes[$field];
        }

        return $columnTypes[$columnType] ?? '$faker->name';
    }
}
