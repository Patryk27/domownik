<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionValueConstantsTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transaction_value_constants', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->decimal('value');
			$table->timestamps();
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transaction_value_constants');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
