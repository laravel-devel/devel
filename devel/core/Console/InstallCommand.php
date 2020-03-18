<?php

namespace Devel\Core\Console;

use Devel\Modules\Facades\Module;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Devel\Core\Database\Seeders\DevelCoreDatabaseSeeder;

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
        if (!env('APP_KEY')) {
            $this->call('key:generate');
        }

        // Run the base migrations and the seeds
        $this->info('Running main migrations...');

        $this->call('migrate:fresh');

        // Seed the core
        (new DevelCoreDatabaseSeeder)->run();

        // Install each module
        $this->info('Installing modules...');

        foreach (Module::all() as $name => $module) {
            $this->info('---');
            $this->info("Installing Module [{$name}]...");

            try {
                $this->call('devel:module:install', ['module' => $name]);
            } catch (\Exception $e) {
                $this->error("Module {$name} could not be installed! Reason: \"" . $e->getMessage() . '".');

                $this->error("Please fix all the errors and run \"php artisan devel:module:install {$name}\" later.");
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
}
