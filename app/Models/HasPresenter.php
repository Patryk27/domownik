<?php

namespace App\Models;

use App\Exceptions\InternalException;
use App\Presenters\PresenterContract;

trait HasPresenter {

	/**
	 * @return PresenterContract
	 * @throws InternalException
	 */
	public function getPresenter() {
		/**
		 * @var Model $this
		 */

		if (!isset($this->presenterClass)) {
			throw new InternalException('Model does not have a \'presenterClass\' property.');
		}

		/**
		 * @var PresenterContract $presenter
		 */
		$presenter = app()->make($this->presenterClass);
		$presenter->setModel($this);

		return $presenter;
	}

}