<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;

abstract class CrudRequest
	extends FormRequest {

	/**
	 * @return bool
	 */
	public function authorize(): bool {
		// @todo ACL
		return true;
	}

	/**
	 * @return array
	 */
	public function rules(): array {
		return [
			'login' => 'required|min:2',
			'full_name' => 'required|min:2',
			'status' => 'required',
		];
	}

	/**
	 * @return array
	 */
	public function messages(): array {
		return __('requests/user/crud.validation');
	}

}