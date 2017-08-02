<?php

namespace App\Http\Requests\Budget\Crud;

class UpdateRequest
	extends Request {

	/**
	 * @return array
	 */
	public function rules(): array {
		return $this->patchRules(parent::rules(), [
			// @todo fix 'unique'
			'name' => 'unique:budgets,name',
		]);
	}

}