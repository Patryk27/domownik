<?php

use App\Models\Budget;
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
		$budget =
			Budget::where('name', 'First budget')
				->first();

		if (!empty($budget)) {
			$this->log->warning('Not creating \'First budget\' budget because one already exists.');
			return;
		}

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
