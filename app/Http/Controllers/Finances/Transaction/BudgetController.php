<?php

namespace App\Http\Controllers\Finances\Transaction;

use App\Models\Budget;
use App\Models\Transaction;

class BudgetController
	extends Controller {

	/**
	 * @param Budget $budget
	 * @return mixed
	 */
	public function create(Budget $budget) {
		return $this->getCreateView('to-budget', $budget, Transaction::PARENT_TYPE_BUDGET);
	}

	/**
	 * @param Budget $budget
	 * @param Transaction $transaction
	 * @return mixed
	 */
	public function edit(Budget $budget, Transaction $transaction) {
		return $this->getEditView('budget'); // @todo
		// http://domownik.dev/finances/budgets/1/transactions/1008/edit
	}

}