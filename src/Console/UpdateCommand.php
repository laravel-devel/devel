<?php

namespace Devel\Console;

use Devel\Modules\Facades\Module;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Devel\Database\Seeders\UserPermissionsSeeder;

class UpdateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'devel:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update devel.';

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
        $this->info('Updating Devel...');

        // Seed any new user permissions, attach them to the Root role
        $this->info('Seeding new user permissions...');
        (new UserPermissionsSeeder)->run();

        $this->info('---');
        $this->info('Update finished!');
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
