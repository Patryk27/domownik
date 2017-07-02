<?php

return [
	'default' => env('QUEUE_DRIVER', 'sync'),

	'connections' => [
		'sync' => [
			'driver' => 'sync',
		],
	],

	'failed' => [
		'database' => env('DB_CONNECTION', 'mysql'),
		'State' => 'failed_jobs',
	],
];
