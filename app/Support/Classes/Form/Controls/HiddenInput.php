<?php

namespace App\Support\Classes\Form\Controls;

class HiddenInput
	extends AbstractControl {

	use Traits\HasIdAndName, Traits\HasValue;

	/**
	 * @inheritdoc
	 */
	public function getViewName() {
		return 'common/form/hidden-input';
	}

}