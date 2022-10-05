<?php

declare(strict_types=1);

namespace NunoMaduro\LaravelPot;

use Illuminate\Support\ServiceProvider;
use NunoMaduro\LaravelPot\Console\Commands;

/**
 * @internal
 */
final class PotServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        parent::register();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\PotListCommand::class,
                Commands\PotSingletonCommand::class,
            ]);
        }
    }
}
