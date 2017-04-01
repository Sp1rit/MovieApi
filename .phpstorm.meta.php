<?php
namespace PHPSTORM_META {

   /**
    * PhpStorm Meta file, to provide autocomplete information for PhpStorm
    * Generated on 2017-03-25.
    *
    * @author Barry vd. Heuvel <barryvdh@gmail.com>
    * @see https://github.com/barryvdh/laravel-ide-helper
    */
    $STATIC_METHOD_TYPES = [
        new \Illuminate\Contracts\Container\Container => [
            '' == '@',
            'db' instanceof \Illuminate\Database\DatabaseManager,
            'config' instanceof \Illuminate\Config\Repository,
            'db.factory' instanceof \Illuminate\Database\Connectors\ConnectionFactory,
            'db.connection' instanceof \Illuminate\Database\MySqlConnection,
            'Illuminate\Contracts\Queue\EntityResolver' instanceof \Illuminate\Database\Eloquent\QueueEntityResolver,
            'events' instanceof \Illuminate\Events\Dispatcher,
            'view' instanceof \Illuminate\View\Factory,
            'view.finder' instanceof \Illuminate\View\FileViewFinder,
            'view.engine.resolver' instanceof \Illuminate\View\Engines\EngineResolver,
            'blade.compiler' instanceof \Illuminate\View\Compilers\BladeCompiler,
            'files' instanceof \Illuminate\Filesystem\Filesystem,
            'Illuminate\Contracts\Debug\ExceptionHandler' instanceof \App\Exceptions\Handler,
            'Illuminate\Contracts\Console\Kernel' instanceof \App\Console\Kernel,
            'command.ide-helper.generate' instanceof \Barryvdh\LaravelIdeHelper\Console\GeneratorCommand,
            'command.ide-helper.models' instanceof \Barryvdh\LaravelIdeHelper\Console\ModelsCommand,
            'command.ide-helper.meta' instanceof \Barryvdh\LaravelIdeHelper\Console\MetaCommand,
            'Curl' instanceof \Ixudra\Curl\CurlService,
            'Parser' instanceof \App\Services\ParserService,
            'cache' instanceof \Illuminate\Cache\CacheManager,
            'cache.store' instanceof \Illuminate\Cache\Repository,
            'memcached.connector' instanceof \Illuminate\Cache\MemcachedConnector,
            'queue' instanceof \Illuminate\Queue\QueueManager,
            'queue.connection' instanceof \Illuminate\Queue\SyncQueue,
            'queue.worker' instanceof \Illuminate\Queue\Worker,
            'queue.listener' instanceof \Illuminate\Queue\Listener,
            'queue.failer' instanceof \Illuminate\Queue\Failed\DatabaseFailedJobProvider,
            'migration.repository' instanceof \Illuminate\Database\Migrations\DatabaseMigrationRepository,
            'migrator' instanceof \Illuminate\Database\Migrations\Migrator,
            'migration.creator' instanceof \Illuminate\Database\Migrations\MigrationCreator,
            'command.cache.clear' instanceof \Illuminate\Cache\Console\ClearCommand,
            'command.cache.forget' instanceof \Illuminate\Cache\Console\ForgetCommand,
            'command.auth.resets.clear' instanceof \Illuminate\Auth\Console\ClearResetsCommand,
            'command.migrate' instanceof \Illuminate\Database\Console\Migrations\MigrateCommand,
            'command.migrate.install' instanceof \Illuminate\Database\Console\Migrations\InstallCommand,
            'command.migrate.refresh' instanceof \Illuminate\Database\Console\Migrations\RefreshCommand,
            'command.migrate.reset' instanceof \Illuminate\Database\Console\Migrations\ResetCommand,
            'command.migrate.rollback' instanceof \Illuminate\Database\Console\Migrations\RollbackCommand,
            'command.migrate.status' instanceof \Illuminate\Database\Console\Migrations\StatusCommand,
            'command.queue.failed' instanceof \Illuminate\Queue\Console\ListFailedCommand,
            'command.queue.flush' instanceof \Illuminate\Queue\Console\FlushFailedCommand,
            'command.queue.forget' instanceof \Illuminate\Queue\Console\ForgetFailedCommand,
            'command.queue.listen' instanceof \Illuminate\Queue\Console\ListenCommand,
            'command.queue.restart' instanceof \Illuminate\Queue\Console\RestartCommand,
            'command.queue.retry' instanceof \Illuminate\Queue\Console\RetryCommand,
            'command.queue.work' instanceof \Illuminate\Queue\Console\WorkCommand,
            'command.seed' instanceof \Illuminate\Database\Console\Seeds\SeedCommand,
            'Illuminate\Console\Scheduling\ScheduleFinishCommand' instanceof \Illuminate\Console\Scheduling\ScheduleFinishCommand,
            'Illuminate\Console\Scheduling\ScheduleRunCommand' instanceof \Illuminate\Console\Scheduling\ScheduleRunCommand,
            'command.cache.table' instanceof \Illuminate\Cache\Console\CacheTableCommand,
            'command.migrate.make' instanceof \Illuminate\Database\Console\Migrations\MigrateMakeCommand,
            'command.queue.failed-table' instanceof \Illuminate\Queue\Console\FailedTableCommand,
            'command.queue.table' instanceof \Illuminate\Queue\Console\TableCommand,
            'command.seeder.make' instanceof \Illuminate\Database\Console\Seeds\SeederMakeCommand,
            'composer' instanceof \Illuminate\Support\Composer,
        ],
        \Illuminate\Contracts\Container\Container::make('') => [
            '' == '@',
            'db' instanceof \Illuminate\Database\DatabaseManager,
            'config' instanceof \Illuminate\Config\Repository,
            'db.factory' instanceof \Illuminate\Database\Connectors\ConnectionFactory,
            'db.connection' instanceof \Illuminate\Database\MySqlConnection,
            'Illuminate\Contracts\Queue\EntityResolver' instanceof \Illuminate\Database\Eloquent\QueueEntityResolver,
            'events' instanceof \Illuminate\Events\Dispatcher,
            'view' instanceof \Illuminate\View\Factory,
            'view.finder' instanceof \Illuminate\View\FileViewFinder,
            'view.engine.resolver' instanceof \Illuminate\View\Engines\EngineResolver,
            'blade.compiler' instanceof \Illuminate\View\Compilers\BladeCompiler,
            'files' instanceof \Illuminate\Filesystem\Filesystem,
            'Illuminate\Contracts\Debug\ExceptionHandler' instanceof \App\Exceptions\Handler,
            'Illuminate\Contracts\Console\Kernel' instanceof \App\Console\Kernel,
            'command.ide-helper.generate' instanceof \Barryvdh\LaravelIdeHelper\Console\GeneratorCommand,
            'command.ide-helper.models' instanceof \Barryvdh\LaravelIdeHelper\Console\ModelsCommand,
            'command.ide-helper.meta' instanceof \Barryvdh\LaravelIdeHelper\Console\MetaCommand,
            'Curl' instanceof \Ixudra\Curl\CurlService,
            'Parser' instanceof \App\Services\ParserService,
            'cache' instanceof \Illuminate\Cache\CacheManager,
            'cache.store' instanceof \Illuminate\Cache\Repository,
            'memcached.connector' instanceof \Illuminate\Cache\MemcachedConnector,
            'queue' instanceof \Illuminate\Queue\QueueManager,
            'queue.connection' instanceof \Illuminate\Queue\SyncQueue,
            'queue.worker' instanceof \Illuminate\Queue\Worker,
            'queue.listener' instanceof \Illuminate\Queue\Listener,
            'queue.failer' instanceof \Illuminate\Queue\Failed\DatabaseFailedJobProvider,
            'migration.repository' instanceof \Illuminate\Database\Migrations\DatabaseMigrationRepository,
            'migrator' instanceof \Illuminate\Database\Migrations\Migrator,
            'migration.creator' instanceof \Illuminate\Database\Migrations\MigrationCreator,
            'command.cache.clear' instanceof \Illuminate\Cache\Console\ClearCommand,
            'command.cache.forget' instanceof \Illuminate\Cache\Console\ForgetCommand,
            'command.auth.resets.clear' instanceof \Illuminate\Auth\Console\ClearResetsCommand,
            'command.migrate' instanceof \Illuminate\Database\Console\Migrations\MigrateCommand,
            'command.migrate.install' instanceof \Illuminate\Database\Console\Migrations\InstallCommand,
            'command.migrate.refresh' instanceof \Illuminate\Database\Console\Migrations\RefreshCommand,
            'command.migrate.reset' instanceof \Illuminate\Database\Console\Migrations\ResetCommand,
            'command.migrate.rollback' instanceof \Illuminate\Database\Console\Migrations\RollbackCommand,
            'command.migrate.status' instanceof \Illuminate\Database\Console\Migrations\StatusCommand,
            'command.queue.failed' instanceof \Illuminate\Queue\Console\ListFailedCommand,
            'command.queue.flush' instanceof \Illuminate\Queue\Console\FlushFailedCommand,
            'command.queue.forget' instanceof \Illuminate\Queue\Console\ForgetFailedCommand,
            'command.queue.listen' instanceof \Illuminate\Queue\Console\ListenCommand,
            'command.queue.restart' instanceof \Illuminate\Queue\Console\RestartCommand,
            'command.queue.retry' instanceof \Illuminate\Queue\Console\RetryCommand,
            'command.queue.work' instanceof \Illuminate\Queue\Console\WorkCommand,
            'command.seed' instanceof \Illuminate\Database\Console\Seeds\SeedCommand,
            'Illuminate\Console\Scheduling\ScheduleFinishCommand' instanceof \Illuminate\Console\Scheduling\ScheduleFinishCommand,
            'Illuminate\Console\Scheduling\ScheduleRunCommand' instanceof \Illuminate\Console\Scheduling\ScheduleRunCommand,
            'command.cache.table' instanceof \Illuminate\Cache\Console\CacheTableCommand,
            'command.migrate.make' instanceof \Illuminate\Database\Console\Migrations\MigrateMakeCommand,
            'command.queue.failed-table' instanceof \Illuminate\Queue\Console\FailedTableCommand,
            'command.queue.table' instanceof \Illuminate\Queue\Console\TableCommand,
            'command.seeder.make' instanceof \Illuminate\Database\Console\Seeds\SeederMakeCommand,
            'composer' instanceof \Illuminate\Support\Composer,
        ],
        \App::make('') => [
            '' == '@',
            'db' instanceof \Illuminate\Database\DatabaseManager,
            'config' instanceof \Illuminate\Config\Repository,
            'db.factory' instanceof \Illuminate\Database\Connectors\ConnectionFactory,
            'db.connection' instanceof \Illuminate\Database\MySqlConnection,
            'Illuminate\Contracts\Queue\EntityResolver' instanceof \Illuminate\Database\Eloquent\QueueEntityResolver,
            'events' instanceof \Illuminate\Events\Dispatcher,
            'view' instanceof \Illuminate\View\Factory,
            'view.finder' instanceof \Illuminate\View\FileViewFinder,
            'view.engine.resolver' instanceof \Illuminate\View\Engines\EngineResolver,
            'blade.compiler' instanceof \Illuminate\View\Compilers\BladeCompiler,
            'files' instanceof \Illuminate\Filesystem\Filesystem,
            'Illuminate\Contracts\Debug\ExceptionHandler' instanceof \App\Exceptions\Handler,
            'Illuminate\Contracts\Console\Kernel' instanceof \App\Console\Kernel,
            'command.ide-helper.generate' instanceof \Barryvdh\LaravelIdeHelper\Console\GeneratorCommand,
            'command.ide-helper.models' instanceof \Barryvdh\LaravelIdeHelper\Console\ModelsCommand,
            'command.ide-helper.meta' instanceof \Barryvdh\LaravelIdeHelper\Console\MetaCommand,
            'Curl' instanceof \Ixudra\Curl\CurlService,
            'Parser' instanceof \App\Services\ParserService,
            'cache' instanceof \Illuminate\Cache\CacheManager,
            'cache.store' instanceof \Illuminate\Cache\Repository,
            'memcached.connector' instanceof \Illuminate\Cache\MemcachedConnector,
            'queue' instanceof \Illuminate\Queue\QueueManager,
            'queue.connection' instanceof \Illuminate\Queue\SyncQueue,
            'queue.worker' instanceof \Illuminate\Queue\Worker,
            'queue.listener' instanceof \Illuminate\Queue\Listener,
            'queue.failer' instanceof \Illuminate\Queue\Failed\DatabaseFailedJobProvider,
            'migration.repository' instanceof \Illuminate\Database\Migrations\DatabaseMigrationRepository,
            'migrator' instanceof \Illuminate\Database\Migrations\Migrator,
            'migration.creator' instanceof \Illuminate\Database\Migrations\MigrationCreator,
            'command.cache.clear' instanceof \Illuminate\Cache\Console\ClearCommand,
            'command.cache.forget' instanceof \Illuminate\Cache\Console\ForgetCommand,
            'command.auth.resets.clear' instanceof \Illuminate\Auth\Console\ClearResetsCommand,
            'command.migrate' instanceof \Illuminate\Database\Console\Migrations\MigrateCommand,
            'command.migrate.install' instanceof \Illuminate\Database\Console\Migrations\InstallCommand,
            'command.migrate.refresh' instanceof \Illuminate\Database\Console\Migrations\RefreshCommand,
            'command.migrate.reset' instanceof \Illuminate\Database\Console\Migrations\ResetCommand,
            'command.migrate.rollback' instanceof \Illuminate\Database\Console\Migrations\RollbackCommand,
            'command.migrate.status' instanceof \Illuminate\Database\Console\Migrations\StatusCommand,
            'command.queue.failed' instanceof \Illuminate\Queue\Console\ListFailedCommand,
            'command.queue.flush' instanceof \Illuminate\Queue\Console\FlushFailedCommand,
            'command.queue.forget' instanceof \Illuminate\Queue\Console\ForgetFailedCommand,
            'command.queue.listen' instanceof \Illuminate\Queue\Console\ListenCommand,
            'command.queue.restart' instanceof \Illuminate\Queue\Console\RestartCommand,
            'command.queue.retry' instanceof \Illuminate\Queue\Console\RetryCommand,
            'command.queue.work' instanceof \Illuminate\Queue\Console\WorkCommand,
            'command.seed' instanceof \Illuminate\Database\Console\Seeds\SeedCommand,
            'Illuminate\Console\Scheduling\ScheduleFinishCommand' instanceof \Illuminate\Console\Scheduling\ScheduleFinishCommand,
            'Illuminate\Console\Scheduling\ScheduleRunCommand' instanceof \Illuminate\Console\Scheduling\ScheduleRunCommand,
            'command.cache.table' instanceof \Illuminate\Cache\Console\CacheTableCommand,
            'command.migrate.make' instanceof \Illuminate\Database\Console\Migrations\MigrateMakeCommand,
            'command.queue.failed-table' instanceof \Illuminate\Queue\Console\FailedTableCommand,
            'command.queue.table' instanceof \Illuminate\Queue\Console\TableCommand,
            'command.seeder.make' instanceof \Illuminate\Database\Console\Seeds\SeederMakeCommand,
            'composer' instanceof \Illuminate\Support\Composer,
        ],
        \app('') => [
            '' == '@',
            'db' instanceof \Illuminate\Database\DatabaseManager,
            'config' instanceof \Illuminate\Config\Repository,
            'db.factory' instanceof \Illuminate\Database\Connectors\ConnectionFactory,
            'db.connection' instanceof \Illuminate\Database\MySqlConnection,
            'Illuminate\Contracts\Queue\EntityResolver' instanceof \Illuminate\Database\Eloquent\QueueEntityResolver,
            'events' instanceof \Illuminate\Events\Dispatcher,
            'view' instanceof \Illuminate\View\Factory,
            'view.finder' instanceof \Illuminate\View\FileViewFinder,
            'view.engine.resolver' instanceof \Illuminate\View\Engines\EngineResolver,
            'blade.compiler' instanceof \Illuminate\View\Compilers\BladeCompiler,
            'files' instanceof \Illuminate\Filesystem\Filesystem,
            'Illuminate\Contracts\Debug\ExceptionHandler' instanceof \App\Exceptions\Handler,
            'Illuminate\Contracts\Console\Kernel' instanceof \App\Console\Kernel,
            'command.ide-helper.generate' instanceof \Barryvdh\LaravelIdeHelper\Console\GeneratorCommand,
            'command.ide-helper.models' instanceof \Barryvdh\LaravelIdeHelper\Console\ModelsCommand,
            'command.ide-helper.meta' instanceof \Barryvdh\LaravelIdeHelper\Console\MetaCommand,
            'Curl' instanceof \Ixudra\Curl\CurlService,
            'Parser' instanceof \App\Services\ParserService,
            'cache' instanceof \Illuminate\Cache\CacheManager,
            'cache.store' instanceof \Illuminate\Cache\Repository,
            'memcached.connector' instanceof \Illuminate\Cache\MemcachedConnector,
            'queue' instanceof \Illuminate\Queue\QueueManager,
            'queue.connection' instanceof \Illuminate\Queue\SyncQueue,
            'queue.worker' instanceof \Illuminate\Queue\Worker,
            'queue.listener' instanceof \Illuminate\Queue\Listener,
            'queue.failer' instanceof \Illuminate\Queue\Failed\DatabaseFailedJobProvider,
            'migration.repository' instanceof \Illuminate\Database\Migrations\DatabaseMigrationRepository,
            'migrator' instanceof \Illuminate\Database\Migrations\Migrator,
            'migration.creator' instanceof \Illuminate\Database\Migrations\MigrationCreator,
            'command.cache.clear' instanceof \Illuminate\Cache\Console\ClearCommand,
            'command.cache.forget' instanceof \Illuminate\Cache\Console\ForgetCommand,
            'command.auth.resets.clear' instanceof \Illuminate\Auth\Console\ClearResetsCommand,
            'command.migrate' instanceof \Illuminate\Database\Console\Migrations\MigrateCommand,
            'command.migrate.install' instanceof \Illuminate\Database\Console\Migrations\InstallCommand,
            'command.migrate.refresh' instanceof \Illuminate\Database\Console\Migrations\RefreshCommand,
            'command.migrate.reset' instanceof \Illuminate\Database\Console\Migrations\ResetCommand,
            'command.migrate.rollback' instanceof \Illuminate\Database\Console\Migrations\RollbackCommand,
            'command.migrate.status' instanceof \Illuminate\Database\Console\Migrations\StatusCommand,
            'command.queue.failed' instanceof \Illuminate\Queue\Console\ListFailedCommand,
            'command.queue.flush' instanceof \Illuminate\Queue\Console\FlushFailedCommand,
            'command.queue.forget' instanceof \Illuminate\Queue\Console\ForgetFailedCommand,
            'command.queue.listen' instanceof \Illuminate\Queue\Console\ListenCommand,
            'command.queue.restart' instanceof \Illuminate\Queue\Console\RestartCommand,
            'command.queue.retry' instanceof \Illuminate\Queue\Console\RetryCommand,
            'command.queue.work' instanceof \Illuminate\Queue\Console\WorkCommand,
            'command.seed' instanceof \Illuminate\Database\Console\Seeds\SeedCommand,
            'Illuminate\Console\Scheduling\ScheduleFinishCommand' instanceof \Illuminate\Console\Scheduling\ScheduleFinishCommand,
            'Illuminate\Console\Scheduling\ScheduleRunCommand' instanceof \Illuminate\Console\Scheduling\ScheduleRunCommand,
            'command.cache.table' instanceof \Illuminate\Cache\Console\CacheTableCommand,
            'command.migrate.make' instanceof \Illuminate\Database\Console\Migrations\MigrateMakeCommand,
            'command.queue.failed-table' instanceof \Illuminate\Queue\Console\FailedTableCommand,
            'command.queue.table' instanceof \Illuminate\Queue\Console\TableCommand,
            'command.seeder.make' instanceof \Illuminate\Database\Console\Seeds\SeederMakeCommand,
            'composer' instanceof \Illuminate\Support\Composer,
        ],
    ];
}
