<?php

use App\Models\Budget;

return [
	'types' => [
		Budget::TYPE_REGULAR => 'Zwyczajny',
		Budget::TYPE_CONSOLIDATED => 'Skonsolidowany',
	],

	'misc' => [
		'found-count' => 'Odnaleziono <b>1</b> budżet.|Odnaleziono <b>:count</b> budżety.|Odnaleziono <b>:count</b> budżetów.',
	],
];