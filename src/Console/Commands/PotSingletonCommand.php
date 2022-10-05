<?php

declare(strict_types=1);

namespace NunoMaduro\LaravelPot\Console\Commands;

use Illuminate\Console\Command;

/**
 * @internal
 */
final class PotSingletonCommand extends PotCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'pot:singletons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists the container singeltons';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $instances = (fn () => $this->instances)->call($this->container);

        $this->section('Instances');

        foreach ($instances as $abstract => $instance) {
            if (isset($bindings[$abstract])) {
                continue;
            }

            $this->item($abstract, $instance);
        }

        $this->newLine();

        return self::SUCCESS;
    }
}
