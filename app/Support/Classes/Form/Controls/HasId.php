<?php

namespace App\Support\Classes\Form\Controls;

trait HasId {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

}