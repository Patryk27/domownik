<?php

namespace App\Presenters;

use App\Models\Transaction;

/**
 * @property Transaction $model
 */
class TransactionPresenter
	extends AbstractPresenter {

	/**
	 * @return string
	 */
	public function getEditUrl(): string {
		return route('finances.transactions.edit', $this->model->id);
	}

	/**
	 * @return string
	 */
	public function getParentEditUrl(): string {
		return route('finances.transactions.edit', $this->model->parent_transaction_id);
	}

}