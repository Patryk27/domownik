<?php

namespace App\Http\Requests\Transaction\Crud;

use App\Http\Requests\FormRequest;
use App\Models\Transaction;

abstract class Request
	extends FormRequest {

	// @todo ACL

	/**
	 * @return array
	 */
	public function rules(): array {
		$rules = [
			'parent_id' => 'required',
			'parent_type' => 'required',

			'type' => 'required',
			'name' => 'required|min:2|max:128',
			'category_id' => 'nullable|numeric',
			'value_type' => 'required',
			'periodicity_type' => 'required',
		];

		switch ($this->get('value_type')) {
			case Transaction::VALUE_TYPE_CONSTANT:
				$rules['value_constant_value'] = 'required|numeric|positive';
				break;

			case Transaction::VALUE_TYPE_RANGE:
				$rules['value_range_from'] = 'required|numeric|positive';
				$rules['value_range_to'] = 'required|numeric|positive|greater_than_field:value_range_from';
				break;
		}

		switch ($this->get('periodicity_type')) {
			case Transaction::PERIODICITY_TYPE_ONE_SHOT:
			case Transaction::PERIODICITY_TYPE_YEARLY:
				$rules['calendar_dates'] = 'required|min:1';
				break;

			case Transaction::PERIODICITY_TYPE_WEEKLY:
				$rules['periodicity_weekly_days'] = 'required|min:1';
				break;

			case Transaction::PERIODICITY_TYPE_MONTHLY:
				$rules['periodicity_monthly_days'] = 'required|min:1';
				break;
		}

		return $rules;
	}

	/**
	 * @return array
	 */
	public function messages(): array {
		return $this->getMessages('requests/transaction/crud.validation');
	}

}
