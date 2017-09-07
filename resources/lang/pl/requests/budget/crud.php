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
		'deleted' => 'Budżet został usunięty.',
	],

	'prompts' => [
		'delete' => '<p>Czy na pewno chcesz usunąć ten budżet?<br>Usunięte zostaną również wszystkie transakcje z nim powiązane.</p>Tej operacji <b>nie można</b> cofnąć.',
	],
];