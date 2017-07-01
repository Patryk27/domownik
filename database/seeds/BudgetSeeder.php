<?php

use App\Modules\Finances\Models\Budget;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Seeder;

class BudgetSeeder
	extends Seeder {

	/**
	 * @var LoggerContract
	 */
	protected $logger;

	/**
	 * @param LoggerContract $logger
	 */
	public function __construct(
		LoggerContract $logger
	) {
		$this->logger = $logger;
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->logger->info('Creating first budget...');

		$budget = new Budget();
		$budget->type = Budget::TYPE_REGULAR;
		$budget->name = 'First budget';
		$budget->description = 'A description of the first budget.';
		$budget->status = Budget::STATUS_ACTIVE;
		$budget->save();

		$this->logger->debug('Flushing cache...');
		Cache::flush();
	}

}
