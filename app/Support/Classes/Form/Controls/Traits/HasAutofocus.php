<?php

namespace App\Support\Classes\Form\Controls\Traits;

trait HasAutofocus {

	/**
	 * @var bool
	 */
	protected $autofocus;

	/**
	 * @return bool
	 */
	public function isAutofocus() {
		return $this->autofocus;
	}

	/**
	 * @param bool $autofocus
	 * @return $this
	 */
	public function setAutofocus($autofocus) {
		$this->autofocus = $autofocus;
		return $this;
	}

}