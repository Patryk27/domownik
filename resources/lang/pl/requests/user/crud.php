<?php

return [
	'validation' => [
		'login.required' => 'Nazwa użytkownika jest wymagana.',
		'login.min' => 'Nazwa użytkownika musi się składać z minimum :min znaków.',
		'login.unique' => 'Konto o takiej nazwie użytkownika już istnieje.',

		'full_name.required' => 'Imię i nazwisko są wymagane.',
		'full_name.min' => 'Imię i nazwisko muszą w sumie składać się z minimum :min znaków.',

		'password.required' => 'Hasło jest wymagane.',

		'password_confirm.required' => 'Potwierdzenie hasła jest wymagane.',
		'password_confirm.same' => 'Hasła muszą się zgadzać.',

		'status.required' => 'Status użytkownika jest wymagany.',
	],

	'messages' => [
		'stored' => 'Użytkownik został powołany do życia.',
		'updated' => 'Użytkownik został zaktualizowany.',
		'deleted' => 'Użytkownik został usunięty.',
	],

	'prompts' => [
		'delete' => '<p>Czy na pewno chcesz usunąć tego użytkownika?<br>Niektóre dane (np. jego budżety) pozostaną nienaruszone i będziesz musiał usunąć je ręcznie.</p>Tej operacji <b>nie można</b> cofnąć.',
	],
];