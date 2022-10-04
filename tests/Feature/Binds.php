<?php

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

test('are displayed', function () {
    $output = new BufferedOutput();

    Artisan::call('pot:list', [], $output);

    $contents = $output->fetch();

    expect($contents)->toContain('log')
        ->toContain('Illuminate\Log › LogManager');
});
