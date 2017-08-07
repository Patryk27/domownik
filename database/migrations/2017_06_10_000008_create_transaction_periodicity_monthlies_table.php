<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionPeriodicityMonthliesTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transaction_periodicity_monthlies', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->tinyInteger('day');
			$table->timestamps();
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transaction_periodicity_monthlies');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
