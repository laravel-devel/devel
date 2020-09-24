<?php

namespace Devel\Modules\Commands;

use Devel\Console\Command;
use Devel\Modules\Facades\Module;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallAllCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:install-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all modules.';

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
        $modules = Module::all();

        // Sort modules by their installation order
        uasort($modules, function ($a, $b) {
            return $a->json()->order - $b->json()->order;
        });

        $this->info('Installing all modules...');

        foreach ($modules as $name => $module) {
            $this->info('---');
            $this->info("Installing Module [{$name}]...");

            // Skip the already installed modules unless '--force'd
            if ($module->isInstalled() && !$this->option('force')) {
                $this->info('Module already installed!');

                continue;
            }

            try {
                $this->call('module:install', ['module' => $name]);
            } catch (\Exception $e) {
                $this->error("Module {$name} could not be installed! Reason: \"" . $e->getMessage() . '".');

                $this->error("Please fix all the errors and run \"php artisan module:install {$name}\" later.");
            }
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
            // ['module', InputArgument::REQUIRED, 'Module name.'],
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
            ['force', null, InputOption::VALUE_NONE, 'Install modules even if they are marked as installed already.', null],
        ];
    }
}
