<?php

use App\Models\User;

return [
	'status' => [
		User::STATUS_ACTIVE => 'aktywny',
		User::STATUS_INACTIVE => 'nieaktywny',
	],

	'misc' => [
		'found-count' => 'Odnaleziono <b>1</b> użytkownika.|Odnaleziono <b>:count</b> użytkowników.|Odnaleziono <b>:count</b> użytkowników.',
	],
];