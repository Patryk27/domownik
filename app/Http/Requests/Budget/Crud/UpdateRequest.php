<?php

namespace App\Http\Requests\Budget\Crud;

class UpdateRequest
	extends Request {

	/**
	 * @return array
	 */
	public function rules(): array {
		$budgetId = $this->route('budget');

		return $this->patchRules(parent::rules(), [
			'name' => sprintf('unique:budgets,name,%d', $budgetId),
		]);
	}

}