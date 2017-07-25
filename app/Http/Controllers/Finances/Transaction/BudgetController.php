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
		return $this->getCreateEditView('to-budget', null, $budget, Transaction::PARENT_TYPE_BUDGET);
	}

}