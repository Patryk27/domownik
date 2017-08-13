<?php

namespace App\Http\Controllers\Finances\Transactions;

use App\Models\Budget;
use App\Models\Transaction;

class BudgetsController
	extends Controller {

	/**
	 * @param Budget $budget
	 * @return mixed
	 */
	public function create(Budget $budget) {
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.budgets.transactions.create', $budget->id), __('breadcrumbs.transactions.create'));

		return $this->getCreateView('to-budget', $budget, Transaction::PARENT_TYPE_BUDGET);
	}

	/**
	 * @param Budget $budget
	 * @param Transaction $transaction
	 * @return mixed
	 */
	public function edit(Budget $budget, Transaction $transaction) {
		$this->breadcrumbManager
			->pushCustom($budget);

		return $this->getEditView($transaction, $budget, Transaction::PARENT_TYPE_BUDGET);
	}

}