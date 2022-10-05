<?php

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

test('are displayed', function () {
    $output = new BufferedOutput();

    Artisan::call('pot:singletons', [], $output);

    $contents = $output->fetch();

    expect($contents)->toContain('Kernel')
        ->toContain('Illuminate\Contracts › Kernel');
});
