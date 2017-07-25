<?php

namespace App\Http\Requests\Transaction\Crud;

class StoreRequest
	extends Request {

	/**
	 * @return array
	 */
	public function rules(): array {
		return $this->patchRules(parent::rules(), [
			'transactionName' => 'unique:transactions,name',
		]);
	}

}