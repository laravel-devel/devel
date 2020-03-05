<?php

namespace Modules\DevelCore\Console;

use Illuminate\Console\Command;
use Extensions\Modules\Facades\Module;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'devel:install';

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
        $this->info('Installing Devel...');

        // Generate an app key
        $this->call('key:generate');

        // Run `npm install`
        $this->info('Installing npm dependencies...');

        $this->runExternal('npm install', base_path());

        // Run `npm run production`
        $this->info('Building frontend assets...');

        $this->runExternal('npm run production', base_path());

        // Run the base migrations and the seeds
        $this->info('Running main migrations...');

        $this->call('migrate:fresh');

        $this->info('Seeding the database...');

        $this->call('db:seed');

        // Install each module
        $this->info('Installing modules...');

        foreach (Module::all() as $name => $module) {
            $this->info('---');
            $this->info("Installing [{$name}]...");

            if ($module->isEnabled()) {
                $this->call('devel:module:install', ['module' => $name]);
            }
        }

        $this->info('---');
        $this->info('DONE! Now you can use Devel.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            // ['example', InputArgument::REQUIRED, 'An example argument.'],
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
