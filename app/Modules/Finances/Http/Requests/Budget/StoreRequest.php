<?php

namespace App\Modules\Finances\Http\Requests\Budget;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest
	extends FormRequest {

	/**
	 * @return bool
	 */
	public function authorize() {
		// @todo ACL
		return true;
	}

	/**
	 * @return array
	 */
	public function rules() {
		return [
			'budgetName' => 'required|min:3|max:64|unique:budgets,name',
			'budgetType' => 'required',
			'consolidatedBudgets' => 'required_if:budgetType,consolidated',
		];
	}

}
