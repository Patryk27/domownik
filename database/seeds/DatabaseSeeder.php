<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder
	extends Seeder {

	/**
	 * @return void
	 */
	public function run() {
		$this->call(BudgetSeeder::class);
		$this->call(BudgetTransactionSeeder::class);
	}
}
