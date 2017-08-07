<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionValueRangesTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transaction_value_ranges', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->decimal('value_from');
			$table->decimal('value_to');
			$table->timestamps();
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transaction_value_ranges');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
