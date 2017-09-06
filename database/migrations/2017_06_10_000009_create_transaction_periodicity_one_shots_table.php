<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionPeriodicityOneShotsTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transaction_periodicity_one_shots', function (Blueprint $table) {
			$table->increments('id');
			$table->dateTime('date');
			$table->timestamps();
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transaction_periodicity_one_shots');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
