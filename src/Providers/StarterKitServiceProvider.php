<?php

namespace Luchavez\StarterKit\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Luchavez\StarterKit\Abstracts\BaseStarterKitServiceProvider as ServiceProvider;
use Luchavez\StarterKit\Enums\OnDeleteAction;
use Luchavez\StarterKit\Enums\OnUpdateAction;
use Luchavez\StarterKit\Http\Middleware\ChangeAppLocaleMiddleware;
use Luchavez\StarterKit\Interfaces\ProviderHttpKernelInterface;
use Luchavez\StarterKit\Services\PackageDomain;
use Luchavez\StarterKit\Services\SimpleResponse;
use Luchavez\StarterKit\Services\StarterKit;

/**
 * Class StarterKitServiceProvider
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class StarterKitServiceProvider extends ServiceProvider implements ProviderHttpKernelInterface
{
    /**
     * @var string[]
     */
    protected array $commands = [];

    /**
     * Publishable Environment Variables
     *
     * @example [ 'SK_OVERRIDE_EXCEPTION_HANDLER' => true ]
     */
    protected array $env_vars = [
        'SK_OVERRIDE_EXCEPTION_HANDLER' => false,
        'SK_ENFORCE_MORPH_MAP' => false,
        'SK_VERIFY_SSL' => true,
        'SK_CHANGE_LOCALE_KEY' => 'lang',
        'SK_CHANGE_LOCALE_ENABLED' => true,
        'SK_DISABLER_REQUIRED' => false,
        'SK_DISABLE_REASON_REQUIRED' => false,
    ];

    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        parent::boot();

        // Register custom migration functions
        Blueprint::macro('expires', function (
            ?string $column = null
        ) {
            $column ??= config('starter-kit.columns.expires.column');
            $this->timestamp($column)->nullable();
        });

        Blueprint::macro('disables', function (
            ?string $column = null,
            ?string $disabler_column = null,
            ?string $disable_reason = null,
            ?OnDeleteAction $on_delete = null,
            ?OnUpdateAction $on_update = null
        ) {
            $column ??= config('starter-kit.columns.disables.column');
            $disabler_column ??= config('starter-kit.columns.disables.disabler_column');
            $disable_reason ??= config('starter-kit.columns.disables.disable_reason_column');
            $on_delete ??= config('starter-kit.columns.disables.on_delete');
            $on_update ??= config('starter-kit.columns.disables.on_update');

            $model = starterKit()->getUserModel();
            $table = starterKit()->getUserQueryBuilder()->getModel()->getTable();

            $this->timestamp($column)->nullable();
            $this->foreignIdFor($model, $disabler_column)
                ->nullable()
                ->constrained($table)
                ->onUpdate($on_update->value)
                ->onDelete($on_delete->value);
            $this->text($disable_reason)->nullable();
        });

        Blueprint::macro('owned', function (
            ?string $column = null,
            ?OnDeleteAction $on_delete = null,
            ?OnUpdateAction $on_update = null
        ) {
            $column ??= config('starter-kit.columns.owned.column');
            $on_delete ??= config('starter-kit.columns.owned.on_delete');
            $on_update ??= config('starter-kit.columns.owned.on_update');

            $model = starterKit()->getUserModel();
            $table = starterKit()->getUserQueryBuilder()->getModel()->getTable();

            $this->foreignIdFor($model, $column)
                ->nullable()
                ->constrained($table)
                ->onUpdate($on_update->value)
                ->onDelete($on_delete->value);
        });

        Blueprint::macro('usage', function (
            ?string $column = null,
            ?int $default = null,
        ) {
            $column ??= config('starter-kit.columns.usage.column');
            $default ??= config('starter-kit.columns.usage.default');
            $this->unsignedTinyInteger($column)->nullable()->default($default);
        });
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/starter-kit.php', 'starter-kit');

        $this->app->singleton('starter-kit', fn () => new StarterKit);

        $this->app->singleton('package-domain', function (Application $app) {
            return new PackageDomain(
                app: $app,
                starter_kit: $app->make('starter-kit'),
                config: $app->make('config'),
                migrator: $app->make('migrator'),
                translator: $app->make('translator'),
            );
        });

        $this->app->bind('simple-response', fn ($app, $params) => new SimpleResponse(...$params));
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['starter-kit', 'simple-response', 'package-domain'];
    }

    /**
     * Console-specific booting.
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes(
            [
                __DIR__.'/../../config/starter-kit.php' => config_path('starter-kit.php'),
            ],
            'starter-kit.config'
        );

        // Registering package commands.
        $this->commands($this->commands);
    }

    public function getRoutePrefix(): ?string
    {
        return 'starter-kit';
    }

    public function areHelpersEnabled(): bool
    {
        return false;
    }

    public function areConfigsEnabled(): bool
    {
        return false;
    }

    /***** LANG RELATED *****/

    public function registerToHttpKernel(Router $router): void
    {
        // Register and add 'change_locale' middleware globally
        $middleware = 'change_locale';

        $router->aliasMiddleware($middleware, ChangeAppLocaleMiddleware::class);

        collect($router->getMiddlewareGroups())
            ->each(fn ($v, $k) => $router->pushMiddlewareToGroup($k, $middleware));
    }
}
