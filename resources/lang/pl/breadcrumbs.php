<?php

return [
	'users' => [
		'index' => 'Lista użytkowników',
		'create' => 'Tworzenie nowego użytkownika',
		'show' => 'Użytkownik: userName',
		'edit' => 'Edycja użytkownika: :userName',
	],

	'budgets' => [
		'index' => 'Lista budżetów',
		'create' => 'Tworzenie nowego budżetu',
		'show' => 'Budżet: :budgetName',
		'edit' => 'Edycja budżetu: :budgetName',
		'summary' => 'Podsumowanie budżetu',

		'transactions' => [
			'booked' => 'Spis zaksięgowanych transakcji',
			'scheduled' => 'Spis zbliżających się transakcji',
		],
	],

	'transactions' => [
		'create' => 'Tworzenie nowej transakcji',
		'show' => 'Transakcja: :transactionName',
		'edit' => 'Edycja transakcji: :transactionName',
	],

	'transaction-categories' => [
		'index' => 'Zarządzanie kategoriami transakcji',
	],

	'help' => [
		'http-error' => 'Błąd HTTP :errorCode',
	],
];