<?php

namespace App\Support\Classes\Form\Controls;

class TextArea
	extends AbstractControl {

	use HasIdAndName, HasValue, HasPlaceholder, HasLabel, HasHelp;

	/**
	 * @inheritdoc
	 */
	public function getViewName() {
		return 'common/form/textarea';
	}

}