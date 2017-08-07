<?php

use Illuminate\Database\Schema\Blueprint;

class CreateBudgetsTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('budgets', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->char('type', 32); // Laravel has some weird issues with enums and thus we use varchar
			$table->char('name', 64);
			$table->text('description')
				  ->nullable();
			$table->char('status', 32);
			$table->timestamps();

			$table->index('type');
			$table->index('name');
			$table->index('status');

			$table->unique('name');
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('budgets');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
