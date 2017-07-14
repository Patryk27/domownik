<?php

namespace App\Presenters;

use App\Models\Transaction;

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