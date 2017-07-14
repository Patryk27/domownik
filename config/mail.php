<?php

return [
	'driver' => env('MAIL_DRIVER', 'array'),

	'from' => [
		'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
		'name' => env('MAIL_FROM_NAME', 'Example'),
	],

	'encryption' => env('MAIL_ENCRYPTION', 'tls'),

	'markdown' => [
		'theme' => 'default',

		'paths' => [
			resource_path('views/vendor/mail'),
		],
	],
];
