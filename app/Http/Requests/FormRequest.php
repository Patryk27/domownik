<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

abstract class FormRequest
	extends BaseFormRequest {

	/**
	 * @return bool
	 */
	public function authorize(): bool {
		return true;
	}

	/**
	 * Patches $rules with $additionalRules.
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

	/**
	 * Patches $messages with $additionalMessages.
	 * @param array $messages
	 * @param mixed $additionalMessages
	 * @return array
	 */
	protected function patchMessages(array $messages, array $additionalMessages): array {
		return $messages + $additionalMessages;
	}

	/**
	 * @param string $translationKey
	 * @return array
	 */
	protected function getMessages(string $translationKey): array {
		$messages = __($translationKey);
		return is_array($messages) ? $messages : [];
	}

}