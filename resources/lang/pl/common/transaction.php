<?php

use App\Models\Transaction;

return [
	'type' => [
		Transaction::TYPE_INCOME => 'Wpływ',
		Transaction::TYPE_EXPENSE => 'Wydatek',
	],

	'value-type' => [
		Transaction::VALUE_TYPE_CONSTANT => 'Stała',
		Transaction::VALUE_TYPE_RANGE => 'Przedział',
	],

	'periodicity-type' => [
		Transaction::PERIODICITY_TYPE_ONE_SHOT => 'Jednorazowa',
		Transaction::PERIODICITY_TYPE_DAILY => 'Dzienna',
		Transaction::PERIODICITY_TYPE_WEEKLY => 'Tygodniowa',
		Transaction::PERIODICITY_TYPE_MONTHLY => 'Miesięczna',
		Transaction::PERIODICITY_TYPE_YEARLY => 'Roczna',
	],
];