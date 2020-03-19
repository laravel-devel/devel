<?php

namespace Devel\Modules\Publishing;

use Devel\Modules\Facades\Module;
use Devel\Modules\Migrations\Migrator;

class MigrationPublisher extends AssetPublisher
{
    /**
     * @var Migrator
     */
    private $migrator;

    /**
     * MigrationPublisher constructor.
     * @param Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        $this->migrator = $migrator;
        parent::__construct($migrator->getModule());
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        return Module::findOrFail('Main')->getExtraPath('Database/Migrations');
    }

    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->migrator->getPath();
    }
}
