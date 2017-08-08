<?php

$app = new Illuminate\Foundation\Application(
	realpath(__DIR__ . '/../')
);

$app->detectEnvironment(function() use ($app) {
	if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'testing.domownik.dev') {
		$app->loadEnvironmentFrom('.env.testing');
	}
});

$app->singleton(
	Illuminate\Contracts\Http\Kernel::class,
	App\Http\Kernel::class
);

$app->singleton(
	Illuminate\Contracts\Console\Kernel::class,
	App\Console\Kernel::class
);

$app->singleton(
	Illuminate\Contracts\Debug\ExceptionHandler::class,
	App\Exceptions\Handler::class
);

return $app;
