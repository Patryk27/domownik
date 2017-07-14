<?php

return [
	'default' => 'app',

	'disks' => [
		'app' => [
			'driver' => 'local',
			'root' => app_path(),
		],
		
		'resources' => [
			'driver' => 'local',
			'root' => resource_path(),
		],
	],
];