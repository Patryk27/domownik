<?php

namespace App\Support\Classes\Form\Controls;

class RequiredFields
	extends AbstractControl {

	/**
	 * @inheritdoc
	 */
	public function getViewName() {
		return 'common/form/required-fields';
	}

}