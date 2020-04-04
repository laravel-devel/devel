<?php

namespace Devel\Modules\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ModuleDeleteCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a module from the application.';

    public function handle()
    {
        $this->laravel['devel-modules']->delete($this->argument('module'));

        $this->info("Module {$this->argument('module')} has been deleted.");
    }

    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'Module name.'],
        ];
    }
}
