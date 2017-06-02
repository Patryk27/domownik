<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function boot() {
		/** @noinspection PhpUnusedParameterInspection */
		Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
			$fieldName = $parameters[0];

			$fields = $validator->getData();
			$fieldValue = $fields[$fieldName];

			return $value > $fieldValue;
		});

		/** @noinspection PhpUnusedParameterInspection */
		Validator::extend('positive', function($attribute, $value, $parameters, $validator) {
			return $value > 0;
		});

		/** @noinspection PhpUnusedParameterInspection */
		Validator::replacer('greater_than_field', function($message, $attribute, $rule, $parameters) {
			return str_replace(':field', $parameters[0], $message);
		});
	}

}
