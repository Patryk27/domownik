<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\IsLoggable;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest
	extends FormRequest {

	use IsLoggable;

	/**
	 * @return bool
	 */
	public function authorize() {
		// @todo autoryzacja (prawa dostępu)
		return true;
	}

	/**
	 * @return array
	 */
	public function rules() {
		$rules = [
			'transactionType' => 'required',
			'transactionName' => 'required|min:2|max:128',
			'transactionCategoryId' => 'nullable|numeric',
			'transactionValueType' => 'required',
			'transactionPeriodicityType' => 'required',
		];

		if ($this->has('transactionId')) {
			$rules['transactionId'] = 'numeric';
		} else {
			$rules['transactionName'] .= '|unique:transactions,name';
			$rules['transactionParentId'] = 'required';
			$rules['transactionParentType'] = 'required';
		}

		// add appropriate transaction value type filters
		switch ($this->get('transactionValueType')) {
			case Transaction::VALUE_TYPE_CONSTANT:
				$rules['transactionValueConstantValue'] = 'required|numeric|positive';
				break;

			case Transaction::VALUE_TYPE_RANGE:
				$rules['transactionValueRangeFrom'] = 'required|numeric|positive';
				$rules['transactionValueRangeTo'] = 'required|numeric|positive|greater_than_field:transactionValueRangeFrom';
				break;
		}

		// add appropriate transaction periodicity type filters
		switch ($this->get('transactionPeriodicityType')) {
			case Transaction::PERIODICITY_TYPE_ONE_SHOT:
			case Transaction::PERIODICITY_TYPE_YEARLY:
				$rules['calendarDates'] = 'required|min:1';
				break;

			case Transaction::PERIODICITY_TYPE_WEEKLY:
				$rules['transactionPeriodicityWeeklyDays'] = 'required|min:1';
				break;

			case Transaction::PERIODICITY_TYPE_MONTHLY:
				$rules['transactionPeriodicityMonthlyDays'] = 'required|min:1';
				break;
		}

		return $rules;
	}

	/**
	 * @return array
	 */
	public function messages() {
		return __('requests/transaction/store.validation');
	}

}
