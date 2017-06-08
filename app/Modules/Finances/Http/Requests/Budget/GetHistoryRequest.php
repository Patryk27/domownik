<?php

namespace App\Modules\Finances\Http\Requests\Budget;

use Illuminate\Foundation\Http\FormRequest;

class GetHistoryRequest
	extends FormRequest {

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
		return [
			'budgetId' => 'required|numeric',
			'groupMode' => 'required',
		];
	}

}