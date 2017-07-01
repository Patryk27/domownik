<?php

namespace App\Support\Classes;

use Illuminate\Foundation\Application;

class Form {

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @param Application $app
	 */
	public function __construct(
		Application $app
	) {
		$this->app = $app;
	}

	/**
	 * @return Form\Controls\Checkbox
	 */
	public function checkbox(): Form\Controls\Checkbox {
		return $this->app->make(Form\Controls\Checkbox::class);
	}

	/**
	 * @return Form\Controls\HiddenInput
	 */
	public function hiddenInput(): Form\Controls\HiddenInput {
		return $this->app->make(Form\Controls\HiddenInput::class);
	}

	/**
	 * @return Form\Controls\PasswordInput
	 */
	public function passwordInput(): Form\Controls\PasswordInput {
		return $this->app->make(Form\Controls\PasswordInput::class);
	}

	/**
	 * @return Form\Controls\Select
	 */
	public function select(): Form\Controls\Select {
		return $this->app->make(Form\Controls\Select::class);
	}

	/**
	 * @return Form\Controls\TextArea
	 */
	public function textArea(): Form\Controls\TextArea {
		return $this->app->make(Form\Controls\TextArea::class);
	}

	/**
	 * @return Form\Controls\TextInput
	 */
	public function textInput(): Form\Controls\TextInput {
		return $this->app->make(Form\Controls\TextInput::class);
	}

}