<?php

namespace Devel\Modules\Commands;

use Devel\Console\Command;
use Devel\Modules\Facades\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Devel\Services\ModuleService;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a module.';

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

        $this->info('Checking the dependencies...');

        $errors = ModuleService::checkDependencies($module);

        if (count($errors)) {
            foreach ($errors as $error) {
                $this->error($error);
            }

            throw new \Exception('Unmet dependencies!');
        }

        $modulePath = $module->getPath();

        // Install the PHP dependencies
        $this->info('Installing PHP dependencies...');

        if (!file_exists($module->getExtraPath('vendor'))) {
            $this->runExternal('composer install', $modulePath);
        }

        // Run the module's migrations
        $this->info('Running migrations...');

        DB::beginTransaction();

        try {
            $this->call('module:migrate', ['module' => $moduleName]);

            // Run the module's seeder
            $this->info('Seeding the database...');

            $this->call('module:seed', ['module' => $moduleName]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $this->error('Could not install the module. Rolling changes back...');

            throw $e;
        }

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
        $this->info('Enabling the module...');
        $module->enable();

        $this->call('config:cache');

        // Publish the config file
        if (!in_array($moduleName, ['Main'])) {
            $this->call('module:publish-config', ['module' => $moduleName]);
        }

        $this->call('config:clear');

        // Mark the module as "installed" with a special meta file
        File::put($module->getExtraPath('.installed'), '');
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
