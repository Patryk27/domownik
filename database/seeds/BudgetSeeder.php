<?php

use App\Modules\Finances\Models\Budget;
use Illuminate\Database\Seeder;

class BudgetSeeder
	extends Seeder {

	/**
	 * @return void
	 */
	public function run() {
		MyLog::debug('Creating first budget...');

		$budget = new Budget();
		$budget->type = Budget::TYPE_REGULAR;
		$budget->name = 'First budget';
		$budget->description = 'A description of the first budget.';
		$budget->status = Budget::STATUS_ACTIVE;
		$budget->save();
	}

}
