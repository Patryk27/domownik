<?php

namespace App\Presenters;

/**
 * @property $model \App\Models\User
 */
class UserPresenter
	extends AbstractPresenter {

	/**
	 * @return string
	 */
	public function getEditUrl() {
		return route('dashboard.user.edit', $this->model->id);
	}

}