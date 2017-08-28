<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionPeriodicityYearliesTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transaction_periodicity_yearlies', function(Blueprint $table) {
			$table->increments('id');
			$table->tinyInteger('month');
			$table->tinyInteger('day');
			$table->timestamps();
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transaction_periodicity_yearlies');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
