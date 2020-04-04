<?php

namespace Devel\Modules\Commands;

use Illuminate\Console\Command;
use Devel\Modules\Module;
use Symfony\Component\Console\Input\InputOption;

class ListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a list of all modules.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->table(['Name', 'Status', 'Order', 'Path'], $this->getRows());
    }

    /**
     * Get table rows.
     *
     * @return array
     */
    public function getRows()
    {
        $rows = [];

        /** @var Module $module */
        foreach ($this->getModules() as $module) {
            $status = ($module->isInstalled() ? 'Installed' : 'Not Installed')
                . ($module->isEnabled() ? ' & Enabled' : '');

            $rows[] = [
                $module->getName(),
                $status,
                $module->get('order'),
                $module->getRelativePath(),
            ];
        }

        return $rows;
    }

    public function getModules()
    {
        switch ($this->option('only')) {
            case 'enabled':
                return $this->laravel['devel-modules']->getByStatus(1);
                break;

            case 'disabled':
                return $this->laravel['devel-modules']->getByStatus(0);
                break;

            case 'installed':
                return $this->laravel['devel-modules']->getInstalled();
                break;

            case 'uninstalled':
                return $this->laravel['devel-modules']->getInstalled(false);
                break;

            case 'ordered':
                return $this->laravel['devel-modules']->getAllOrdered($this->option('direction'));
                break;

            default:
                return $this->laravel['devel-modules']->all();
                break;
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['only', 'o', InputOption::VALUE_OPTIONAL, 'Show installed|uninstalled|enabled|disabled|ordered modules.', null],
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
        ];
    }
}
