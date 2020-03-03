<?php

namespace Modules\DevelCore\Console;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ModuleInstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'devel:module:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a devel module.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $moduleName = $this->arguments()['module'];

        try {
            $module = Module::findOrFail($moduleName);
        } catch (\Exception $e) {
            $this->error("Module \"{$moduleName}\" not found!");

            exit(0);
        }

        $modulePath = $module->getPath();

        // Install the PHP dependencies
        $this->info('Installing PHP dependencies...');

        $this->runExternal('composer install', $modulePath);

        // Run the module's migrations
        $this->info('Running migrations...');

        $this->call('module:migrate', ['module' => $moduleName]);

        // Run the module's seeder
        $this->info('Seeding the database...');

        $this->call('module:seed', ['module' => $moduleName]);

        // NPM
        if ($module->json()->buildNpm === true) {
            // Install npm dependencies
            $this->info('Installing npm dependencies...');

            $this->runExternal('npm install', $modulePath);

            // Build npm for production
            $this->info('Building frontend assets...');

            $this->runExternal('npm run production', $modulePath);
        }

        // Enable the module
        $module->enable();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'Module name.'],
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
            // ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

    /**
     * Run an external command
     *
     * @param string $command
     * @param string $dir
     * @return void
     */
    protected function runExternal(string $command, string $dir): void
    {
        $process = new Process($command, $dir);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error("Error while running `{$command}`!");

            throw new ProcessFailedException($process);
        }
    }
}
