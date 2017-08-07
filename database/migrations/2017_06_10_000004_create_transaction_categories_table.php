<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionCategoriesTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transaction_categories', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->unsignedInteger('parent_category_id')
				  ->nullable();
			$table->char('name', 128);
			$table->timestamps();
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transaction_categories');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
