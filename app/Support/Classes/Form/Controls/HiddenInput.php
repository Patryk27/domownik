<?php

namespace App\Support\Classes\Form\Controls;

class HiddenInput
	extends AbstractControl {

	use HasIdAndName, HasValue;

	/**
	 * @inheritdoc
	 */
	public function getViewName() {
		return 'common/form/hidden-input';
	}

}