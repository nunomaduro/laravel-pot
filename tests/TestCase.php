<?php

declare(strict_types=1);

namespace Tests;

use NunoMaduro\LaravelPot\PotServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * @internal
 */
final class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PotServiceProvider::class,
        ];
    }
}
