<?php

namespace Devel\Modules\Commands;

use Illuminate\Console\Command;
use Devel\Modules\Facades\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UninstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall a module.';

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
        if (file_exists($path = $module->getExtraPath('.installed'))) {
            File::delete($path);
        }

        // Remove the published config file
        if (file_exists($path = base_path('config/' . $module->getAlias() . '.php'))) {
            File::delete($path);
        }

        // Remove the module from the modules list JSON file
        $path = config('devel-modules.activators')[config('devel-modules.activator')]['statuses-file'];
        $modulesFile = File::get($path);

        $regex = '/\s{0,4}"' . $module->getName() . '.*?\n/';
        preg_match($regex, $modulesFile, $match, PREG_UNMATCHED_AS_NULL);

        if (isset($match[0])) {
            // Remove the module from the list
            $modulesFile = str_replace($match[0], '', $modulesFile);

            // Remove comma after the last item in the list if any
            $regex = '/(\s{0,4}".*?),(\n})/';
            $modulesFile = preg_replace($regex, '$1$2', $modulesFile);

            File::put($path, $modulesFile);
        }

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
