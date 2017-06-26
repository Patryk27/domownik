<?php

namespace App\Support\Classes\Form\Controls;

interface ControlContract {

	/**
	 * Returns control's HTML code.
	 * @return string
	 */
	public function __toString();

}