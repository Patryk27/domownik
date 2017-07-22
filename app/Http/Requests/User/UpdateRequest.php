<?php

namespace App\Http\Requests\User;

class UpdateRequest
	extends CrudRequest {

	/**
	 * @return array
	 */
	public function rules(): array {
		if ($this->has('password')) {
			return $this->patchRules(parent::rules(), [
				'password' => 'required',
				'password_confirm' => 'required|same:password',
			]);
		} else {
			return $this->patchRules(parent::rules(), [
				'password' => 'nullable',
				'password_confirm' => 'nullable',
			]);
		}
	}

}