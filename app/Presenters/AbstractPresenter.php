<?php

namespace App\Presenters;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractPresenter
	implements PresenterContract {

	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * @inheritDoc
	 */
	public function setModel(Model $model): PresenterContract {
		$this->model = $model;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getModel(): Model {
		return $this->model;
	}

}