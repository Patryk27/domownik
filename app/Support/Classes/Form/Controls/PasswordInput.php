<?php

namespace App\Support\Classes\Form\Controls;

class PasswordInput
	extends AbstractControl {

	use Traits\HasAddon, Traits\HasAutofocus, Traits\HasHelp, Traits\HasHelpBlock, Traits\HasIdAndName,Traits\HasLabel, Traits\HasPlaceholder, Traits\HasValue;

	/**
	 * @inheritdoc
	 */
	public function getViewName() {
		return 'common/form/password-input';
	}

}