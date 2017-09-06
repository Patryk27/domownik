<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionPeriodicitiesTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transaction_periodicities', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('transaction_id');
			$table->morphs('transaction_periodicity', 'fk_transaction_periodicities_1');

			$table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transaction_periodicities');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
