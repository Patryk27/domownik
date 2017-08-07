<?php

use App\Models\TransactionCategory;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Seeder;

class TransactionCategorySeeder
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
		$this->log->info('Truncating \'transaction_categories\' table...');

		$this->db
			->table('transaction_categories')
			->delete();

		$this->log->info('Creating \'All transactions\' transaction category...');

		$transactionCategory = new TransactionCategory();
		$transactionCategory->name = 'All transactions';
		$transactionCategory->save();

		$this->log->info('Flushing cache...');
		Cache::flush();
	}

}