<?php

namespace App\Http\Requests\Transaction\Crud;

class UpdateRequest
	extends Request {

	/**
	 * @return array
	 */
	public function rules(): array {
		return $this->patchRules(parent::rules(), [
			// @todo check id
			'transactionName' => sprintf('unique:transactions,name,%d', $this->get('id')),
		]);
	}

}