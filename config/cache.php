<?php

return [
    'default' => env('CACHE_DRIVER', 'redis'),

    'stores' => [
		'redis' => [
			'driver' => 'redis',
		],
    ],

    'prefix' => 'dk',
];
