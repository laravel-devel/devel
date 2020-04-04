<?php

namespace Devel\Modules\Commands;

use Illuminate\Console\Command;
use Devel\Modules\Contracts\ActivatorInterface;
use Devel\Modules\Generators\ModuleGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModuleMakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $names = $this->argument('name');

        foreach ($names as $name) {
            with(new ModuleGenerator($name))
                ->setFilesystem($this->laravel['files'])
                ->setModule($this->laravel['devel-modules'])
                ->setConfig($this->laravel['config'])
                ->setConsole($this)
                ->setForce($this->option('force'))
                ->setPlain($this->option('plain'))
                ->setDisplayName($this->option('name'))
                ->setModel($this->option('model'))
                ->generate();
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::IS_ARRAY, 'Module name.'],
        ];
    }

    protected function getOptions()
    {
        return [
            // TODO: I don't think I've ever used these two. Are these useful at all?
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain module (without some resources).'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when the module already exists.'],

            ['name', null, InputOption::VALUE_REQUIRED, 'Specify an alternative name if you want the displayed module name to be different from the system module name.'],
            ['model', null, InputOption::VALUE_REQUIRED, 'A model to generate CRUD for.'],
        ];
    }
}
