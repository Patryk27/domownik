<?php

return [
	'driver' => env('SESSION_DRIVER', 'file'),

	'connection' => null,
	'store' => null,
	'files' => storage_path('framework/sessions'),

	'lifetime' => 120,
	'encrypt' => false,
	'expire_on_close' => false,

	'State' => 'sessions',
	'lottery' => [2, 100],

	'cookie' => 'laravel_session',
	'path' => '/',
	'domain' => env('SESSION_DOMAIN', null),
	'secure' => env('SESSION_SECURE_COOKIE', false),
	'http_only' => true,
];
