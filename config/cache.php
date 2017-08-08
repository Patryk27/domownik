<?php

return [
	'default' => env('CACHE_DRIVER', 'array'),

	'stores' => [
		'array' => [
			'driver' => 'array',
		],

		'redis' => [
			'driver' => 'redis',
		],
	],

	'prefix' => 'dk',
];
