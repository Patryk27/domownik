<?php

namespace App\Support\Classes;

class Form {

	/**
	 * @return Form\Controls\Checkbox
	 */
	public function checkbox() {
		return new Form\Controls\Checkbox();
	}

	/**
	 * @return Form\Controls\HiddenInput
	 */
	public function hiddenInput() {
		return new Form\Controls\HiddenInput();
	}

	/**
	 * @return Form\Controls\PasswordInput
	 */
	public function passwordInput() {
		return new Form\Controls\PasswordInput();
	}

	/**
	 * @return Form\Controls\RequiredFields
	 */
	public function requiredFields() {
		return new Form\Controls\RequiredFields();
	}

	/**
	 * @return Form\Controls\Select
	 */
	public function select() {
		return new Form\Controls\Select();
	}

	/**
	 * @return Form\Controls\TextArea
	 */
	public function textArea() {
		return new Form\Controls\TextArea();
	}

	/**
	 * @return Form\Controls\TextInput
	 */
	public function textInput() {
		return new Form\Controls\TextInput();
	}

}