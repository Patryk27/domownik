<?php

namespace App\Http\Requests\Budget\Transaction;

class SearchScheduledRequest
	extends SearchRequest {

	/**
	 * @return array
	 */
	public function rules(): array {
		return $this->patchRules(parent::rules(), [
			'dateFrom' => 'future_or_today',
			'dateTo' => 'future_or_today',
		]);
	}

	/**
	 * @return array
	 */
	public function messages(): array {
		return $this->patchMessages(parent::messages(), __('requests/budget/transaction/search-scheduled'));
	}

}