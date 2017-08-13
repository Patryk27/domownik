<?php

use App\Models\Transaction;

return [
	'fields' => [
		'id' => 'Id transakcji',
		'category' => 'Kategoria',
		'type' => 'Typ',
		'name' => 'Nazwa',
		'description' => 'Opis',
		'value' => 'Wartość',
		'periodicity' => 'Okresowość',
		'date' => 'Data utworzenia',
	],

	'misc' => [
		'found-count' => 'Odnaleziono <b>1</b> transakcję.|Odnaleziono <b>:count</b> transakcje.|Odnaleziono <b>:count</b> transakcji.',
		'found-none' => 'Nie odnaleziono żadnych transakcji.',
	],

	'enums' => [
		'types' => [
			Transaction::TYPE_INCOME => 'Wpływ',
			Transaction::TYPE_EXPENSE => 'Wydatek',
		],

		'value-types' => [
			Transaction::VALUE_TYPE_CONSTANT => 'Stała',
			Transaction::VALUE_TYPE_RANGE => 'Przedział',
		],

		'periodicity-types' => [
			Transaction::PERIODICITY_TYPE_ONE_SHOT => 'Jednorazowa',
			Transaction::PERIODICITY_TYPE_DAILY => 'Dzienna',
			Transaction::PERIODICITY_TYPE_WEEKLY => 'Tygodniowa',
			Transaction::PERIODICITY_TYPE_MONTHLY => 'Miesięczna',
			Transaction::PERIODICITY_TYPE_YEARLY => 'Roczna',
		],
	],
];