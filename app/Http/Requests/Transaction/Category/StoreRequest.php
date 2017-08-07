<?php

namespace App\Http\Requests\Transaction\Category;

use App\Http\Requests\FormRequest;

class StoreRequest
	extends FormRequest {

	// @todo ACL

	/**
	 * @return array
	 */
	public function rules() {
		return [
			'newTree' => 'required|array|min:1',
			'newTree.*.id' => 'required',
			'newTree.*.text' => 'required|min:1',
		];
	}

}