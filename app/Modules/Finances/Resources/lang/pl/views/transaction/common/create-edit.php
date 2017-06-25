<?php

return [
	'tabs' => [
		'basic' => [
			'title' => 'Podstawowe dane transakcji',
		],

		'value' => [
			'title' => 'Wartość transakcji',
		],

		'periodicity' => [
			'title' => 'Okresowość transakcji',
		],
	],

	'transaction-name' => [
		'label' => 'Nazwa transakcji:',
		'placeholder' => 'Wpisz nazwę, która pozwoli Ci jak najdokładniej określić tę transakcję, np. \'wypłata\'.',
	],

	'transaction-category' => [
		'label' => 'Kategoria transakcji:',
		'empty-option' => '------',
	],

	'transaction-description' => [
		'label' => 'Opis transakcji:',
		'placeholder' => 'Opcjonalny opis transakcji, np. \'Wypłata z firmy Xyz.\'.',
	],

	'transaction-type' => [
		'label' => 'Rodzaj transakcji:',
	],

	'transaction-value-type' => [
		'label' => 'Rodzaj wartości:',
	],

	'transaction-value-constant' => [
		'label' => 'Wartość transakcji:',
		'placeholder' => '',
	],

	'transaction-value-range-from' => [
		'label' => 'Dolna widełka wartości transakcji:',
		'placeholder' => '',
	],

	'transaction-value-range-to' => [
		'label' => 'Górna widełka wartości transakcji:',
		'placeholder' => '',
	],

	'transaction-periodicity-type' => [
		'label' => 'Okresowość transakcji:',
	],

	'transaction-periodicity' => [
		'one-shot' => [
			'title' => 'Wybierz dni roku, w których transakcja ma zostać zliczona:',
		],

		'daily' => [
			'title' => 'Dzienna transakcja zliczana jest każdego dnia.',
		],

		'weekly' => [
			'title' => 'Wybierz dni tygodnia, w których transakcja ma być zliczana:',
		],

		'monthly' => [
			'title' => 'Wybierz dni miesiąca, w których transakcja ma być zliczana:',
			'warning' => '(transakcja nie zostanie zliczona w dniach niewystępujących w danym miesiącu, np. 30 lutego, 31 sierpnia itd.)',
		],

		'yearly' => [
			'title' => 'Wybierz dni roku, w których transakcja ma być zliczana:',
		],
	],
	
	'delete-confirmation-message' => 'Czy na pewno chcesz usunąć tę transakcję?',
];