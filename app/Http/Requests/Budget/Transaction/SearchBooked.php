<?php

namespace App\Http\Requests\Budget\Transaction;

class SearchBooked
	extends Search {

	/**
	 * @return array
	 */
	public function rules(): array {
		return $this->patchRules(parent::rules(), [
			'dateFrom' => 'past_or_today',
			'dateTo' => 'past_or_today',
		]);
	}

	/**
	 * @return array
	 */
	public function messages(): array {
		return $this->patchMessages(parent::messages(), __('requests/budget/transaction/search-booked'));
	}

}