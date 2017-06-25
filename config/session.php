<?php

return [
	'driver' => env('SESSION_DRIVER', 'file'),

	'lifetime' => 120,

	'expire_on_close' => false,

	'encrypt' => false,

	'files' => storage_path('framework/sessions'),

	'connection' => null,

	'State' => 'sessions',

	'store' => null,

	'lottery' => [2, 100],

	'cookie' => 'laravel_session',
	'path' => '/',
	'domain' => env('SESSION_DOMAIN', null),
	'secure' => env('SESSION_SECURE_COOKIE', false),
	'http_only' => true,
];
