<?php

use App\Models\User;

return [
	'status' => [
		User::STATUS_ACTIVE => 'aktywny',
		User::STATUS_INACTIVE => 'nieaktywny',
	],
];