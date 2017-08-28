<?php

return [
	'filename' => '_ide_helper',
	'format' => 'php',

	'include_fluent' => true,
	'write_model_magic_where' => false,

	'include_helpers' => true,
	'helper_files' => [
		base_path() . '/vendor/laravel/framework/src/Illuminate/Support/helpers.php',
	],

	'model_locations' => [
		'app',
	],

	'extra' => [
		'Eloquent' => ['Illuminate\Database\Eloquent\Builder', 'Illuminate\Database\Query\Builder'],
		'Session' => ['Illuminate\Session\Store'],
	],

	'magic' => [
		'Log' => [
			'debug' => 'Monolog\Logger::addDebug',
			'info' => 'Monolog\Logger::addInfo',
			'notice' => 'Monolog\Logger::addNotice',
			'warning' => 'Monolog\Logger::addWarning',
			'error' => 'Monolog\Logger::addError',
			'critical' => 'Monolog\Logger::addCritical',
			'alert' => 'Monolog\Logger::addAlert',
			'emergency' => 'Monolog\Logger::addEmergency',
		],
	],

	'interfaces' => [

	],

	'custom_db_types' => [

	],

	'model_camel_case_properties' => false,

	'type_overrides' => [
		'integer' => 'int',
		'boolean' => 'bool',
	],
];
