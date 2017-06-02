<?php

namespace App\Presenters;

use Illuminate\Database\Eloquent\Model;

interface PresenterContract {

	/**
	 * @param Model $model
	 * @return PresenterContract
	 */
	public function setModel(Model $model): PresenterContract;

	/**
	 * @return Model
	 */
	public function getModel(): Model;

}