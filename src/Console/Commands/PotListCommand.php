<?php

declare(strict_types=1);

namespace NunoMaduro\LaravelPot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Throwable;

/**
 * @internal
 */
final class PotListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'pot:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists the container bindings / instances';

    /**
     * Creates a new instance of the Command.
     */
    public function __construct(private readonly Container $container)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $bindings = $this->container->getBindings();

        $this->section('Bindings');

        foreach ($bindings as $abstract => $_) {
            $instance = $this->tryToResolve($abstract);

            $this->item($abstract, $instance);
        }

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

    /**
     * Displays a new section.
     */
    private function section(string $title): void
    {
        $this->newLine();
        $this->components->twoColumnDetail('  <fg=green;options=bold>'.$title.'</>');
    }

    /**
     * Displays a new item in the section.
     */
    private function item(string $abstract, mixed $instance): void
    {
        $aliases = (fn () => $this->abstractAliases[$abstract] ?? [])->call($this->container);

        if (is_null($instance)) {
            return;
        }

        if (is_object($instance)) {
            $aliases = array_flip($aliases);
            unset($aliases[get_class($instance)]);
            $aliases = array_flip($aliases);
        }

        if (is_object($instance)) {
            $instanceAsString = $this->getShorterName(get_class($instance));
        } else {
            $instanceAsString = gettype($instance);
        }

        $abstract = $this->getShorterName($abstract);

        $this->components->twoColumnDetail(
            $this->getShorterName($abstract),
            $abstract === $instanceAsString ? null : $instanceAsString,
        );

        if (! empty($aliases)) {
            $this->components->bulletList(array_map(function ($alias) {
                return $this->getShorterName($alias);
            }, $aliases));
        }
    }

    /**
     * Tries to resolve the given abstract from the container.
     */
    private function tryToResolve(string $abstract): mixed
    {
        try {
            return $this->container->make($abstract);
        } catch (Throwable $e) {
            // ..
        }

        return null;
    }

    /**
     * Gets a shorter name, if the given name is a class.
     */
    private function getShorterName(string $name): string
    {
        if (class_exists($name) || interface_exists($name)) {
            $parts = explode('\\', $name);

            if (count($parts) > 1) {
                $name = $parts[0].'\\'.$parts[1].' <fg=gray>›</> '.$parts[count($parts) - 1];
            }
        }

        return $name;
    }
}
