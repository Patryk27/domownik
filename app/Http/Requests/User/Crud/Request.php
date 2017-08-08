<?php

namespace App\Http\Requests\User\Crud;

use App\Http\Requests\FormRequest;

abstract class Request
	extends FormRequest {

	// @todo ACL

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
		return $this->getMessages('requests/user/crud.validation');
	}

}