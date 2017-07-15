<?php

namespace App\Presenters;

use App\Models\Budget;

/**
 * @property Budget $model
 */
class BudgetPresenter
	extends AbstractPresenter {

	/**
	 * @return string
	 */
	public function getShowUrl(): string {
		return route('finances.budget.show', $this->model->id);
	}

}