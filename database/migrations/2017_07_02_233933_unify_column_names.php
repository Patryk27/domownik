<?php

use Illuminate\Database\Schema\Blueprint;

class UnifyColumnNames
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->logAlterTable('transaction_periodicity_monthlies');
		$this->schemaBuilder->table('transaction_periodicity_monthlies', function(Blueprint $table) {
			$table->renameColumn('day_number', 'day');
		});

		$this->logAlterTable('transaction_periodicity_weeklies');
		$this->schemaBuilder->table('transaction_periodicity_weeklies', function(Blueprint $table) {
			$table->renameColumn('weekday_number', 'weekday');
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->logAlterTable('transaction_periodicity_monthlies');
		$this->schemaBuilder->table('transaction_periodicity_monthlies', function(Blueprint $table) {
			$table->renameColumn('day', 'day_number');
		});

		$this->logAlterTable('transaction_periodicity_weeklies');
		$this->schemaBuilder->table('transaction_periodicity_weeklies', function(Blueprint $table) {
			$table->renameColumn('weekday', 'weekday_number');
		});
	}
}
