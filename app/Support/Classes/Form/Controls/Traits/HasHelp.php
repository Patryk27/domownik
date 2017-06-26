<?php

namespace App\Support\Classes\Form\Controls\Traits;

/**
 * Shows a question mark after the label with link redirecting to given URL.
 */
trait HasHelp {

	/**
	 * @var string
	 */
	protected $helpUrl;

	/**
	 * @return string
	 */
	public function getHelpUrl() {
		return $this->helpUrl;
	}

	/**
	 * @param string $helpUrl
	 * @return $this
	 */
	public function setHelpUrl($helpUrl) {
		$this->helpUrl = $helpUrl;
		return $this;
	}

}