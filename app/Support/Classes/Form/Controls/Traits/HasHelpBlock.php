<?php

namespace App\Support\Classes\Form\Controls\Traits;

trait HasHelpBlock {

	/**
	 * @var bool
	 */
	protected $helpBlockEnabled = true;

	/**
	 * @return bool
	 */
	public function isHelpBlockEnabled(): bool {
		return $this->helpBlockEnabled;
	}

	/**
	 * @param bool $helpBlockEnabled
	 * @return $this
	 */
	public function setHelpBlockEnabled(bool $helpBlockEnabled) {
		$this->helpBlockEnabled = $helpBlockEnabled;
		return $this;
	}

}