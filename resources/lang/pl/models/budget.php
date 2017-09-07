<?php

use App\Models\Budget;

return [
	'fields' => [
		'id' => 'Id budżetu',
		'type' => 'Typ',
		'name' => 'Nazwa',
		'description' => 'Opis',
		'status' => 'Status',
	],

	'misc' => [
		'found-count' => 'Odnaleziono <b>1</b> budżet.|Odnaleziono <b>:count</b> budżety.|Odnaleziono <b>:count</b> budżetów.',
		'found-none' => 'Nie odnaleziono żadnych budżetów.',
	],

	'enums' => [
		'types' => [
			Budget::TYPE_REGULAR => 'Zwyczajny',
			Budget::TYPE_CONSOLIDATED => 'Skonsolidowany',
		],
	],
];