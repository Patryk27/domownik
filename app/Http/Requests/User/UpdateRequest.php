<?php

namespace App\Http\Requests\User;

class UpdateRequest
	extends CrudRequest {

	/**
	 * @return array
	 */
	public function rules(): array {
		return $this->patchRules(parent::rules(), [
			'password' => 'nullable',
			'password_confirm' => 'nullable',
		]);
	}

}