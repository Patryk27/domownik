<?php

namespace App\Modules\Dashboard\Http\Requests\User;

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
			'userLogin' => 'required',
			'userFullName' => 'required',
			'userStatus' => 'required',
		];
	}

	/**
	 * @return array
	 */
	public function messages() {
		return __('Dashboard::requests/user/store.validation');
	}

}