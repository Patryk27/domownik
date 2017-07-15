<?php

namespace App\Http\Requests\User;

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
		$result = [
			'userLogin' => 'required',
			'userFullName' => 'required',
			'userStatus' => 'required',
		];

		if (!$this->has('userId')) {
			$result['userLogin'] .= '|unique:users,login';
			$result['userPassword'] = 'required';
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function messages() {
		return __('requests/user/store.validation');
	}

}