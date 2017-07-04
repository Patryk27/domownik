<?php

namespace App\Support\Classes\Form\Controls\Traits;

trait HasAddon {

	/**
	 * @var string
	 */
	protected $leftAddonIcon;

	/**
	 * @var string
	 */
	protected $rightAddonIcon;

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
	public function setLeftAddonIcon(string $leftAddonIcon) {
		$this->leftAddonIcon = $leftAddonIcon;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRightAddonIcon() {
		return $this->rightAddonIcon;
	}

	/**
	 * @param string $rightAddonIcon
	 * @return HasAddon
	 */
	public function setRightAddonIcon(string $rightAddonIcon) {
		$this->rightAddonIcon = $rightAddonIcon;
		return $this;
	}

}