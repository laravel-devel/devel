<?php

namespace Devel\Core\Console;

use Illuminate\Console\Command;
use Devel\Modules\Facades\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleUninstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'devel:module:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall a devel module.';

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

        $this->info("Uninstalling module [{$moduleName}]...");

        try {
            $module = Module::findOrFail($moduleName);
        } catch (\Exception $e) {
            $this->error("Module \"{$moduleName}\" not found!");

            exit(0);
        }

        DB::beginTransaction();

        try {
            // Rollback the module's migrations
            $this->info('Removing the seeded data...');

            $this->call('module:unseed', ['module' => $moduleName]);
            
            $this->info('Rolling the migrations back...');

            $this->call('module:migrate-rollback', ['module' => $moduleName]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $this->error('Could not uninstall the module. Rolling changes back...');

            throw $e;
        }

        // Delete the `.installed` meta file
        if (file_exists($module->getExtraPath('.installed'))) {
            File::delete($module->getExtraPath('.installed'));
        }

        // Disable the module
        $module->disable();

        $this->info("Module [{$moduleName}] have been successfully uninstalled!");
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
}
