<?php

use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('transactions', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('parent_transaction_id')->nullable();
			$table->nullableMorphs('parent');
			$table->unsignedInteger('category_id')->nullable();
			$table->char('type', 16);
			$table->char('name', 128);
			$table->text('description')->nullable();
			$table->nullableMorphs('value');
			$table->string('periodicity_type');
			$table->timestamps();

			$table->index('parent_id');
			$table->index('parent_type');
			$table->index('type');
			$table->index('name');

			$table->foreign('category_id')->references('id')->on('transaction_categories')->onDelete('set null');
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('transactions');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
