<?php

use Illuminate\Database\Schema\Blueprint;

class FixTransactionCategories
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->logAlterTable('transaction_categories');
		$this->schemaBuilder->table('transaction_categories', function(Blueprint $table) {
			$table->unsignedInteger('parent_category_id')
				  ->nullable()
				  ->change();
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->logAlterTable('transaction_categories');
		$this->schemaBuilder->table('transaction_categories', function(Blueprint $table) {
			$table->unsignedInteger('parent_category_id')
				  ->nullable(false)
				  ->change();
		});
	}
}
