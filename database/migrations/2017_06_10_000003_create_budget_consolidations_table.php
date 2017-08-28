<?php

use Illuminate\Database\Schema\Blueprint;

class CreateBudgetConsolidationsTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('budget_consolidations', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('base_budget_id');
			$table->unsignedInteger('subject_budget_id');

			$table->foreign('base_budget_id')
				  ->references('id')
				  ->on('budgets');

			$table->foreign('subject_budget_id')
				  ->references('id')
				  ->on('budgets');
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('budget_consolidations');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
