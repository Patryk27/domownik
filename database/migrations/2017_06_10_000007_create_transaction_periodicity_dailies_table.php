<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionPeriodicityDailiesTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transaction_periodicity_dailies', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transaction_periodicity_dailies');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
