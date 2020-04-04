<?php

namespace Devel\Modules\Commands;

use Illuminate\Console\Command;
use Devel\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class UpdateCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dependencies for the specified module or for all modules.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('module');

        if ($name) {
            $this->updateModule($name);

            return;
        }

        /** @var \Devel\Modules\Module $module */
        foreach ($this->laravel['devel-modules']->getOrdered() as $module) {
            $this->updateModule($module->getName());
        }
    }

    protected function updateModule($name)
    {
        $this->line('Running for module: <info>' . $name . '</info>');

        $this->laravel['devel-modules']->update($name);

        $this->info("Module [{$name}] updated successfully.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The module to be updated.'],
        ];
    }
}
