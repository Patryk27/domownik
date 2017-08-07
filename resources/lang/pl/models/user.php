<?php

use App\Models\User;

return [
	'fields' => [
		'id' => 'Id użytkownika',
		'login' => 'Login',
		'full-name' => 'Nazwa',
		'password' => 'Hasło',
		'status' => 'Status',
	],

	'misc' => [
		'found-count' => 'Odnaleziono <b>1</b> użytkownika.|Odnaleziono <b>:count</b> użytkowników.|Odnaleziono <b>:count</b> użytkowników.',
	],

	'enums' => [
		'statuses' => [
			User::STATUS_ACTIVE => 'aktywny',
			User::STATUS_INACTIVE => 'nieaktywny',
		],
	],
];