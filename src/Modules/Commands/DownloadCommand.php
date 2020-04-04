<?php

namespace Devel\Modules\Commands;

use Devel\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DownloadCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download the specified module by given package name (vendor/name).';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->download($this->argument('name'), $this->argument('version'));
    }

    /**
     * Install the specified module.
     *
     * @param string $name
     * @param string $version
     */
    protected function download($name, $version = null)
    {
        $name = $name . ($version ? ':' . $version : '');

        $this->runExternal('composer require ' . $name . ' --no-scripts');

        if ($this->option('no-dump') === null) {
            // Dump composer's autoload
            $this->runExternal('composer dump-autoload');
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
            ['name', InputArgument::REQUIRED, 'The name of the module to be downloaded.'],
            ['version', InputArgument::OPTIONAL, 'The version of the module to be downloaded.'],
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
            ['no-dump', null, InputOption::VALUE_REQUIRED, 'Don\'t run `composer dump-autoload` after downloading.'],
        ];
    }
}
