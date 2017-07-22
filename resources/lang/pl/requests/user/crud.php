<?php

return [
	'validation' => [
		'login.required' => 'Nazwa użytkownika jest wymagana.',
		'login.min' => 'Nazwa użytkownika musi się składać z minimum :min znaków.',
		'login.unique' => 'Konto o takim loginie już istnieje.',

		'full-name.required' => 'Imię i nazwisko są wymagane.',
		'full-name.min' => 'Imię i nazwisko muszą w sumie składać się z minimum :min znaków.',

		'password.required' => 'Hasło jest wymagane.',

		'password-confirm.required' => 'Potwierdzenie hasła jest wymagane.',
		'password-confirm.same' => 'Hasła muszą się zgadzać.',

		'status.required' => 'Status użytkownika jest wymagany.',
	],

	'messages' => [
		'stored' => 'Użytkownik został powołany do życia.',
		'updated' => 'Użytkownik został zaktualizowany.',
		'deleted' => 'Użytkownik został usunięty.',
	],

	'prompts' => [
		'delete' => 'Czy na pewno chcesz usunąć tego użytkownika?',
	],
];