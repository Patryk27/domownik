<?php

namespace App\Http\Requests\Budget\Crud;

use App\Http\Requests\FormRequest;

abstract class Request
	extends FormRequest {

	// @todo authorize()

	/**
	 * @return array
	 */
	public function rules(): array {
		return [
			'name' => 'required|min:3|max:64',
			'description' => 'nullable',
			'consolidated_budgets' => 'required_if:type,consolidated',
		];
	}

	/**
	 * @return array
	 */
	public function messages(): array {
		return $this->getMessages('requests/budget/crud.validation');
	}

}
