<?php

namespace App\Modules\Finances\Presenters;

use App\Modules\Finances\Models\Transaction;
use App\Presenters\AbstractPresenter;

class TransactionPresenter
	extends AbstractPresenter {

	/**
	 * @var Transaction
	 */
	protected $model;

	/**
	 * @return string
	 */
	public function getEditUrl() {
		return route('finances.transaction.edit', $this->model->id);
	}

	/**
	 * @return string
	 */
	public function getParentEditUrl() {
		return route('finances.transaction.edit', $this->model->parent_transaction_id);
	}

}