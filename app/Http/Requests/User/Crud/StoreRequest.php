<?php

namespace App\Http\Requests\User\Crud;

class StoreRequest
	extends Request {

	/**
	 * @return array
	 */
	public function rules(): array {
		return $this->patchRules(parent::rules(), [
			'login' => 'unique:users,login',
			'password' => 'required',
			'password_confirm' => 'required|same:password',
		]);
	}

}