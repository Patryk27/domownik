<?php

/**
 * @see \App\Modules\Finances\Http\Requests\Transaction\StoreRequest
 */

return [
	'validation' => [
		'transactionValueRangeTo.greater_than_field' => 'Górna widełka wartości musi być większa niż dolna.',
	],

	'messages' => [
		'create-success' => 'Transakcja została utworzona.',
		'update-success' => 'Transakcja została zaktualizowana.',
	],
];