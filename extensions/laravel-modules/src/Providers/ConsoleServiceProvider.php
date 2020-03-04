<?php

namespace Extensions\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Extensions\Modules\Commands\CommandMakeCommand;
use Extensions\Modules\Commands\ControllerMakeCommand;
use Extensions\Modules\Commands\CrudMakeCommand;
use Extensions\Modules\Commands\DisableCommand;
use Extensions\Modules\Commands\DumpCommand;
use Extensions\Modules\Commands\EnableCommand;
use Extensions\Modules\Commands\EventMakeCommand;
use Extensions\Modules\Commands\FactoryMakeCommand;
use Extensions\Modules\Commands\InstallCommand;
use Extensions\Modules\Commands\JobMakeCommand;
use Extensions\Modules\Commands\LaravelModulesV6Migrator;
use Extensions\Modules\Commands\ListCommand;
use Extensions\Modules\Commands\ListenerMakeCommand;
use Extensions\Modules\Commands\MailMakeCommand;
use Extensions\Modules\Commands\MiddlewareMakeCommand;
use Extensions\Modules\Commands\MigrateCommand;
use Extensions\Modules\Commands\MigrateRefreshCommand;
use Extensions\Modules\Commands\MigrateResetCommand;
use Extensions\Modules\Commands\MigrateRollbackCommand;
use Extensions\Modules\Commands\MigrateStatusCommand;
use Extensions\Modules\Commands\MigrationMakeCommand;
use Extensions\Modules\Commands\ModelMakeCommand;
use Extensions\Modules\Commands\ModuleDeleteCommand;
use Extensions\Modules\Commands\ModuleMakeCommand;
use Extensions\Modules\Commands\NotificationMakeCommand;
use Extensions\Modules\Commands\PolicyMakeCommand;
use Extensions\Modules\Commands\ProviderMakeCommand;
use Extensions\Modules\Commands\PublishCommand;
use Extensions\Modules\Commands\PublishConfigurationCommand;
use Extensions\Modules\Commands\PublishMigrationCommand;
use Extensions\Modules\Commands\PublishTranslationCommand;
use Extensions\Modules\Commands\RequestMakeCommand;
use Extensions\Modules\Commands\ResourceMakeCommand;
use Extensions\Modules\Commands\RouteProviderMakeCommand;
use Extensions\Modules\Commands\RuleMakeCommand;
use Extensions\Modules\Commands\SeedCommand;
use Extensions\Modules\Commands\SeedMakeCommand;
use Extensions\Modules\Commands\SetupCommand;
use Extensions\Modules\Commands\TestMakeCommand;
use Extensions\Modules\Commands\UnUseCommand;
use Extensions\Modules\Commands\UpdateCommand;
use Extensions\Modules\Commands\UseCommand;

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
        DumpCommand::class,
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
        SeedMakeCommand::class,
        SetupCommand::class,
        UnUseCommand::class,
        UpdateCommand::class,
        UseCommand::class,
        ResourceMakeCommand::class,
        TestMakeCommand::class,
        LaravelModulesV6Migrator::class,
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
