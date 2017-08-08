<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

class LoginRequest
	extends FormRequest {

	/**
	 * @return array
	 */
	public function rules(): array {
		return [
			'login' => 'required',
			'password' => 'required',
		];
	}

	/**
	 * @return array
	 */
	public function messages(): array {
		return $this->getMessages('requests/auth/login.validation');
	}

}
