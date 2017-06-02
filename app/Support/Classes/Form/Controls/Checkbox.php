<?php

namespace App\Support\Classes\Form\Controls;

class Checkbox
	extends AbstractControl {

	use HasIdAndName, HasValue, HasLabel, HasAutofocus;

	/**
	 * @inheritdoc
	 */
	public function getViewName() {
		return 'common/form/checkbox';
	}

}