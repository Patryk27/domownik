<?php

namespace App\Support\Classes\Form\Controls;

class Select
	extends AbstractControl {

	use HasIdAndName, HasValue, HasLabel, HasHelp;

	/**
	 * @var array
	 */
	protected $items;

	/**
	 * @var bool
	 */
	protected $multiple;

	/**
	 * @return array
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * @param array|callable $items
	 * @return $this
	 */
	public function setItems($items) {
		if (is_callable($items)) {
			$this->items = $items();
		} else {
			$this->items = $items;
		}

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isMultiple() {
		return $this->multiple;
	}

	/**
	 * @param bool $multiple
	 * @return $this
	 */
	public function setMultiple($multiple) {
		$this->multiple = $multiple;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getViewName() {
		return 'common/form/select';
	}

}