<?php

namespace Devel\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Devel\Modules\Commands\CommandMakeCommand;
use Devel\Modules\Commands\ControllerMakeCommand;
use Devel\Modules\Commands\CrudMakeCommand;
use Devel\Modules\Commands\DisableCommand;
use Devel\Modules\Commands\DownloadCommand;
use Devel\Modules\Commands\DumpCommand;
use Devel\Modules\Commands\EnableCommand;
use Devel\Modules\Commands\EventMakeCommand;
use Devel\Modules\Commands\FactoryMakeCommand;
use Devel\Modules\Commands\InstallCommand;
use Devel\Modules\Commands\JobMakeCommand;
use Devel\Modules\Commands\LaravelModulesV6Migrator;
use Devel\Modules\Commands\ListCommand;
use Devel\Modules\Commands\ListenerMakeCommand;
use Devel\Modules\Commands\MailMakeCommand;
use Devel\Modules\Commands\MiddlewareMakeCommand;
use Devel\Modules\Commands\MigrateCommand;
use Devel\Modules\Commands\MigrateRefreshCommand;
use Devel\Modules\Commands\MigrateResetCommand;
use Devel\Modules\Commands\MigrateRollbackCommand;
use Devel\Modules\Commands\MigrateStatusCommand;
use Devel\Modules\Commands\MigrationMakeCommand;
use Devel\Modules\Commands\ModelMakeCommand;
use Devel\Modules\Commands\ModuleDeleteCommand;
use Devel\Modules\Commands\ModuleMakeCommand;
use Devel\Modules\Commands\NotificationMakeCommand;
use Devel\Modules\Commands\PolicyMakeCommand;
use Devel\Modules\Commands\ProviderMakeCommand;
use Devel\Modules\Commands\PublishCommand;
use Devel\Modules\Commands\PublishConfigurationCommand;
use Devel\Modules\Commands\PublishMigrationCommand;
use Devel\Modules\Commands\PublishTranslationCommand;
use Devel\Modules\Commands\RequestMakeCommand;
use Devel\Modules\Commands\ResourceMakeCommand;
use Devel\Modules\Commands\RouteProviderMakeCommand;
use Devel\Modules\Commands\RuleMakeCommand;
use Devel\Modules\Commands\SeedCommand;
use Devel\Modules\Commands\SeedMakeCommand;
use Devel\Modules\Commands\SetupCommand;
use Devel\Modules\Commands\TestMakeCommand;
use Devel\Modules\Commands\UninstallCommand;
use Devel\Modules\Commands\UnseedCommand;
use Devel\Modules\Commands\UpdateCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
        CommandMakeCommand::class,
        ControllerMakeCommand::class,
        CrudMakeCommand::class,
        DisableCommand::class,
        // TODO: Runs `composer dump -o -n -q` for a module. Not sure if I need
        // this but keeping it just in case for the future.
        // DumpCommand::class,
        EnableCommand::class,
        EventMakeCommand::class,
        JobMakeCommand::class,
        ListenerMakeCommand::class,
        MailMakeCommand::class,
        MiddlewareMakeCommand::class,
        NotificationMakeCommand::class,
        ProviderMakeCommand::class,
        RouteProviderMakeCommand::class,
        InstallCommand::class,
        UninstallCommand::class,
        DownloadCommand::class,
        ListCommand::class,
        ModuleDeleteCommand::class,
        ModuleMakeCommand::class,
        FactoryMakeCommand::class,
        PolicyMakeCommand::class,
        RequestMakeCommand::class,
        RuleMakeCommand::class,
        MigrateCommand::class,
        MigrateRefreshCommand::class,
        MigrateResetCommand::class,
        MigrateRollbackCommand::class,
        MigrateStatusCommand::class,
        MigrationMakeCommand::class,
        ModelMakeCommand::class,
        PublishCommand::class,
        PublishConfigurationCommand::class,
        PublishMigrationCommand::class,
        PublishTranslationCommand::class,
        SeedCommand::class,
        UnseedCommand::class,
        SeedMakeCommand::class,
        SetupCommand::class,
        // TODO: Installs/updates all the modules "required" and "required-dev"
        // from the module's `composer.json` to the main `vendor` folder. Not
        // sure if it's needed right now but it might be proven needed in the
        // future as I get deeper into module development. Maybe running
        // `composer install` for each module doesn't work as I think it does.
        // UpdateCommand::class,
        ResourceMakeCommand::class,
        TestMakeCommand::class,
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
