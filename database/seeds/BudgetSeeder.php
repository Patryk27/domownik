<?php

use App\Models\Budget;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Seeder;

class BudgetSeeder
	extends Seeder {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db
	) {
		$this->log = $log;
		$this->db = $db;
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->log->info('Truncating \'budgets\' table...');

		$this->db
			->table('budgets')
			->delete();

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
