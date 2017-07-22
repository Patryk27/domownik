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
	public function getEditUrl(): string {
		return route('dashboard.users.edit', $this->model->id);
	}

}