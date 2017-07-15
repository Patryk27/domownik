<?php

use App\Models\Budget;

return [
	'type' => [
		Budget::TYPE_REGULAR => 'Zwyczajny',
		Budget::TYPE_CONSOLIDATED => 'Skonsolidowany',
	],
];