<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionPeriodicityWeekliesTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transaction_periodicity_weeklies', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->tinyInteger('weekday');
			$table->timestamps();
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transaction_periodicity_weeklies');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
