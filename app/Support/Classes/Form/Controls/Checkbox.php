<?php

namespace App\Support\Classes\Form\Controls;

class Checkbox
	extends AbstractControl {

	use Traits\HasAutofocus, Traits\HasHelpBlock, Traits\HasIdAndName, Traits\HasLabel, Traits\HasValue;

	/**
	 * @inheritdoc
	 */
	public function getViewName() {
		return 'common/form/checkbox';
	}

}