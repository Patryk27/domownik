<?php

namespace App\Support\Classes\Form\Controls\Traits;

trait HasPlaceholder {

	/**
	 * @var string
	 */
	protected $placeholder;

	/**
	 * @return string
	 */
	public function getPlaceholder() {
		return $this->placeholder;
	}

	/**
	 * @param string $placeholder
	 * @return $this
	 */
	public function setPlaceholder($placeholder) {
		$this->placeholder = $placeholder;
		return $this;
	}

}