<?php

namespace App\Http\Requests\User\Crud;

class UpdateRequest
	extends Request {

	/**
	 * @return array
	 */
	public function rules(): array {
		$userId = $this->route('user');

		$rules = $this->patchRules(parent::rules(), [
			'login' => sprintf('unique:users,login,%d', $userId),
		]);

		if ($this->has('password')) {
			return $this->patchRules($rules, [
				'password' => 'required',
				'password_confirm' => 'required|same:password',
			]);
		} else {
			return $this->patchRules($rules, [
				'password' => 'nullable',
				'password_confirm' => 'nullable',
			]);
		}
	}

}