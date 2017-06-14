<?php

define('APP_RUN_TIMESTAMP', microtime(true));

require __DIR__.'/../bootstrap/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

/**
 * @var Illuminate\Contracts\Http\Kernel $kernel
 */
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

/**
 * @var Illuminate\Http\Request $response
 */
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
