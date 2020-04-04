<?php

namespace Devel\Modules\Commands;

use Illuminate\Console\Command;
use Devel\Modules\Migrations\Migrator;
use Devel\Modules\Publishing\MigrationPublisher;
use Symfony\Component\Console\Input\InputArgument;

class PublishMigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:publish-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a module's migrations to the application.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($name = $this->argument('module')) {
            $module = $this->laravel['devel-modules']->findOrFail($name);

            $this->publish($module);

            return;
        }

        foreach ($this->laravel['devel-modules']->allEnabled() as $module) {
            $this->publish($module);
        }
    }

    /**
     * Publish migration for the specified module.
     *
     * @param \Devel\Modules\Module $module
     */
    public function publish($module)
    {
        with(new MigrationPublisher(new Migrator($module, $this->getLaravel())))
            ->setRepository($this->laravel['devel-modules'])
            ->setConsole($this)
            ->publish();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'Module to publish the migrations for.'],
        ];
    }
}
