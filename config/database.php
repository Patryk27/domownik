<?php

return [
	'default' => env('DB_CONNECTION', 'mysql'),

	'connections' => [
		'mysql' => [
			'driver' => 'mysql',
			'host' => env('DB_HOST', '127.0.0.1'),
			'port' => env('DB_PORT', '3306'),
			'database' => env('DB_DATABASE', 'domownik'),
			'username' => env('DB_USERNAME', 'root'),
			'password' => env('DB_PASSWORD', ''),
			'charset' => 'utf8',
			'collation' => 'utf8_polish_ci',
			'prefix' => '',
			'strict' => true,
			'engine' => null,
		],
	],

	'redis' => [
		'client' => 'predis',

		'default' => [
			'host' => env('REDIS_HOST', '127.0.0.1'),
			'password' => env('REDIS_PASSWORD', null),
			'port' => env('REDIS_PORT', 6379),
			'database' => 0,
		],
	],

	'migrations' => 'migrations',
];
