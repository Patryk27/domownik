<?php

namespace App\Support\Classes\Form\Controls;

trait HasName {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

}