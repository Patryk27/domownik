<?php

namespace App\Support\Classes\Form\Controls;

class TextArea
	extends AbstractControl {

	use Traits\HasHelp, Traits\HasHelpBlock, Traits\HasIdAndName, Traits\HasLabel, Traits\HasPlaceholder, Traits\HasValue;

	/**
	 * @inheritdoc
	 */
	public function getViewName() {
		return 'common/form/textarea';
	}

}