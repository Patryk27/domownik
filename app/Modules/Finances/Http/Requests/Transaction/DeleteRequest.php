<?php

namespace App\Modules\Finances\Http\Requests\Transaction;

use App\Http\Requests\IsLoggable;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest
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
			'transactionId' => 'required',
		];
	}

}
