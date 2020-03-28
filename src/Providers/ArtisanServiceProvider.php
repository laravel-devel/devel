<?php

namespace Devel\Providers;

use Illuminate\Console\Scheduling\ScheduleFinishCommand;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Foundation\Providers\ArtisanServiceProvider as IlluminateArtisanServiceProvider;

class ArtisanServiceProvider extends IlluminateArtisanServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'CacheClear' => 'command.cache.clear',
        'CacheForget' => 'command.cache.forget',
        'ClearCompiled' => 'command.clear-compiled',
        'ClearResets' => 'command.auth.resets.clear',
        'ConfigCache' => 'command.config.cache',
        'ConfigClear' => 'command.config.clear',
        'DbWipe' => 'command.db.wipe',
        'Down' => 'command.down',
        'Environment' => 'command.environment',
        'EventCache' => 'command.event.cache',
        'EventClear' => 'command.event.clear',
        'EventList' => 'command.event.list',
        'KeyGenerate' => 'command.key.generate',
        'Optimize' => 'command.optimize',
        'OptimizeClear' => 'command.optimize.clear',
        'PackageDiscover' => 'command.package.discover',
        'Preset' => 'command.preset',
        'QueueFailed' => 'command.queue.failed',
        'QueueFlush' => 'command.queue.flush',
        'QueueForget' => 'command.queue.forget',
        'QueueListen' => 'command.queue.listen',
        'QueueRestart' => 'command.queue.restart',
        'QueueRetry' => 'command.queue.retry',
        'QueueWork' => 'command.queue.work',
        'RouteCache' => 'command.route.cache',
        'RouteClear' => 'command.route.clear',
        'RouteList' => 'command.route.list',
        'Seed' => 'command.seed',
        'ScheduleFinish' => ScheduleFinishCommand::class,
        'ScheduleRun' => ScheduleRunCommand::class,
        'StorageLink' => 'command.storage.link',
        'Up' => 'command.up',
        'ViewCache' => 'command.view.cache',
        'ViewClear' => 'command.view.clear',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        'CacheTable' => 'command.cache.table',
        // 'ChannelMake' => 'command.channel.make',
        // 'ConsoleMake' => 'command.console.make',
        // 'ControllerMake' => 'command.controller.make',
        // 'EventGenerate' => 'command.event.generate',
        // 'EventMake' => 'command.event.make',
        // 'ExceptionMake' => 'command.exception.make',
        // 'FactoryMake' => 'command.factory.make',
        // 'JobMake' => 'command.job.make',
        // 'ListenerMake' => 'command.listener.make',
        // 'MailMake' => 'command.mail.make',
        // 'MiddlewareMake' => 'command.middleware.make',
        // 'ModelMake' => 'command.model.make',
        // 'NotificationMake' => 'command.notification.make',
        'NotificationTable' => 'command.notification.table',
        // 'ObserverMake' => 'command.observer.make',
        // 'PolicyMake' => 'command.policy.make',
        // 'ProviderMake' => 'command.provider.make',
        'QueueFailedTable' => 'command.queue.failed-table',
        'QueueTable' => 'command.queue.table',
        // 'RequestMake' => 'command.request.make',
        // 'ResourceMake' => 'command.resource.make',
        // 'RuleMake' => 'command.rule.make',
        // 'SeederMake' => 'command.seeder.make',
        'SessionTable' => 'command.session.table',
        'Serve' => 'command.serve',
        // 'TestMake' => 'command.test.make',
        'VendorPublish' => 'command.vendor.publish',
    ];
}
