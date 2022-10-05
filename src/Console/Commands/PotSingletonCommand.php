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
        $bindings = collect($this->container->getBindings())
            ->filter(fn (array $binding) => $binding['shared']);

        $this->section('Instances (Singletons)');

        foreach ($bindings as $abstract => $_) {
            $instance = $this->tryToResolve($abstract);

            $this->item($abstract, $instance);
        }

        $this->newLine();

        return self::SUCCESS;
    }
}
