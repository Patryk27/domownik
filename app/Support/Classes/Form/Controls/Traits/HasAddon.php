<?php

namespace App\Support\Classes\Form\Controls\Traits;

trait HasAddon {

	/**
	 * @var string
	 */
	protected $leftAddonIcon;

	/**
	 * @return string
	 */
	public function getLeftAddonIcon() {
		return $this->leftAddonIcon;
	}

	/**
	 * @param string $leftAddonIcon
	 * @return $this
	 */
	public function setLeftAddonIcon($leftAddonIcon) {
		$this->leftAddonIcon = $leftAddonIcon;
		return $this;
	}

}