<?php

use App\Modules\Finances\Models\Budget;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Seeder;

class BudgetSeeder
	extends Seeder {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @param LoggerContract $log
	 */
	public function __construct(
		LoggerContract $log
	) {
		$this->log = $log;
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->log->info('Creating first budget...');

		$budget = new Budget();
		$budget->type = Budget::TYPE_REGULAR;
		$budget->name = 'First budget';
		$budget->description = 'A description of the first budget.';
		$budget->status = Budget::STATUS_ACTIVE;
		$budget->save();

		$this->log->debug('Flushing cache...');
		Cache::flush();
	}

}
