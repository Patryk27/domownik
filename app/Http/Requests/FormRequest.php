<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

abstract class FormRequest
	extends BaseFormRequest {

	/**
	 * Adds $additionalRules to the $rules.
	 * @param array $rules
	 * @param array $additionalRules
	 * @return array
	 */
	protected function patchRules(array $rules, array $additionalRules): array {
		foreach ($additionalRules as $ruleName => $ruleValue) {
			if (isset($rules[$ruleName])) {
				$rules[$ruleName] .= '|' . $ruleValue;
			} else {
				$rules[$ruleName] = $ruleValue;
			}
		}

		return $rules;
	}

}