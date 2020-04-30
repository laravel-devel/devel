<?php

namespace Devel\Console;

use Devel\Modules\Facades\Module;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Devel\Database\Seeders\DevelDatabaseSeeder;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $modulesToInstall = [
        'DevelDashboard' => 'devel/devel-dashboard-module',
        'DevelUserRoles' => 'devel/devel-user-roles-module',
        'DevelUsers' => 'devel/devel-users-module',
    ];

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
    protected $description = 'Install devel.';

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

        // Publish the config file
        $this->call('vendor:publish', [
            '--tag' => 'devel-config',
        ]);

        // Run the base migrations and the seeds
        $this->info('Running main migrations...');

        $this->call('migrate');

        // Seeding
        $this->info('Seeding the required data...');

        (new DevelDatabaseSeeder)->run();

        // Generate a User factory if there's none yet
        $this->info('Generating a User factory if there\'s none yet...');
        $userModel = config('auth.providers.users.model');
            
        try {
            factory($userModel)->make();
            // An exception would mean there's no factory
        } catch (\Exception $e) {
            $this->call('module:make-factory', [
                'name' => 'UserFactory',
                'module' => 'generate-main-user-factory',
                '--model' => $userModel,
            ]);
        }

        // Create the Modules folder if it doesn't exist yet
        if (!file_exists(config('devel-modules.paths.modules'))) {
            mkdir(config('devel-modules.paths.modules'));
        }

        // Download and install the default modules (require into the main project)
        $this->info('Downloading the default modules...');

        $downloadedModules = Module::all();

        foreach ($this->modulesToInstall as $name => $package) {
            if (isset($downloadedModules[$name])) {
                $this->info("Module [$name] already exists. Skipping...");

                continue;
            }

            // No exception catching. In case of an error the instalation will
            // be terminated at this point, because the default modules are
            // like a part of the Devel package and we should make sure they can
            // be installed.
            $this->call('module:download', [
                'name' => $package,
                '--no-dump' => true,
            ]);

            $this->info("Downloaded module [$name]!");
        }

        // Dump composer's autoload
        $this->runExternal('composer dump-autoload');

        // Install all the modules
        $this->call('module:install-all', [
            '--force' => true,
        ]);

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
