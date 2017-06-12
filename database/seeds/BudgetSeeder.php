<?php

use App\Modules\Finances\Models\Budget;
use App\Support\Classes\MyLog;
use Illuminate\Database\Seeder;

class BudgetSeeder
	extends Seeder {

	/**
	 * @var MyLog
	 */
	protected $myLog;

	/**
	 * BudgetSeeder constructor.
	 * @param MyLog $myLog
	 */
	public function __construct(
		MyLog $myLog
	) {
		$this->myLog = $myLog;
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->myLog->info('Creating first budget...');

		$budget = new Budget();
		$budget->type = Budget::TYPE_REGULAR;
		$budget->name = 'First budget';
		$budget->description = 'A description of the first budget.';
		$budget->status = Budget::STATUS_ACTIVE;
		$budget->save();

		$this->myLog->debug('Flushing cache...');
		Cache::flush();
	}

}
