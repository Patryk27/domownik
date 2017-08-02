<?php

return [
	'validation' => [
		'name.required' => 'Określ nazwę budżetu.',
		'name.min' => 'Nazwa budżetu musi składać się z minimum :min znaków.',
		'name.max' => 'Nazwa budżetu musi składać się z maksimum :max znaków.',
		'name.unique' => 'Budżet o takiej nazwie już istnieje.',

		'type.required' => 'Określ rodzaj budżetu',

		'consolidated_budgets.required_if' => 'Określ listę skonsolidowanych budżetów.',
	],

	'messages' => [
		'stored' => 'Budżet został utworzony.',
		'updated' => 'Budżet został zaktualizowany.',
	],
];