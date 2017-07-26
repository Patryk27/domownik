<?php

namespace App\Http\Requests\User\Crud;

class UpdateRequest
	extends Request {

	/**
	 * @return array
	 */
	public function rules(): array {
		$rules = $this->patchRules(parent::rules(), [
			// 'login' => 'unique:users,login', @todo
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