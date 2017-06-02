<?php

namespace App\Modules\Finances\Http\Requests\TransactionCategory;

use App\Http\Requests\IsLoggable;
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
		return [
			'newTree' => 'required|array|min:1',
			'newTree.*.id' => 'required',
			'newTree.*.text' => 'required|min:1',
		];
	}

}