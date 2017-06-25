<?php

use App\Modules\Finances\Models\Budget;

return [
	'type' => [
		Budget::TYPE_REGULAR => 'Zwyczajny',
		Budget::TYPE_CONSOLIDATED => 'Skonsolidowany',
	],
];