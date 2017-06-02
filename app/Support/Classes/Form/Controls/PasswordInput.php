<?php

namespace App\Support\Classes\Form\Controls;

class PasswordInput
	extends AbstractControl {

	use HasIdAndName, HasValue, HasPlaceholder, HasAddon, HasLabel, HasHelp, HasAutofocus;

	/**
	 * @inheritdoc
	 */
	public function getViewName() {
		return 'common/form/password-input';
	}

}