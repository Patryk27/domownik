<?php

namespace App\Support\Classes\Form\Controls\Traits;

trait HasIdAndName {

	use HasId, HasName;

	/**
	 * @param string $idAndName
	 * @return $this
	 */
	public function setIdAndName($idAndName) {
		$this
			->setId($idAndName)
			->setName($idAndName);

		return $this;
	}

}