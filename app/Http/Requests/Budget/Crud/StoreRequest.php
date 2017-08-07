<?php

namespace App\Http\Requests\Budget\Crud;

class StoreRequest
	extends Request {

	/**
	 * @return array
	 */
	public function rules(): array {
		return $this->patchRules(parent::rules(), [
			'name' => 'unique:budgets,name',
			'type' => 'required',
		]);
	}

}