<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionSchedulesTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transaction_schedules', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('transaction_id');
			$table->dateTime('date');
			$table->timestamps();

			$table->index('date');

			$table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transaction_schedules');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
