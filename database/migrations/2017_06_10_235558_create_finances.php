<?php

use Illuminate\Database\Schema\Blueprint;

class CreateFinances
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->logCreateTable('budgets');
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

		$this->logCreateTable('budget_consolidations');
		$this->schemaBuilder->create('budget_consolidations', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

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

		$this->logCreateTable('transaction_categories');
		$this->schemaBuilder->create('transaction_categories', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->unsignedInteger('parent_category_id');
			$table->char('name', 128);
			$table->timestamps();
		});

		$this->logCreateTable('transactions');
		$this->schemaBuilder->create('transactions', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->unsignedInteger('parent_transaction_id')
				  ->nullable();
			$table->nullableMorphs('parent');
			$table->unsignedInteger('category_id')
				  ->nullable();
			$table->char('type', 16);
			$table->char('name', 128);
			$table->text('description')
				  ->nullable();
			$table->nullableMorphs('value');
			$table->string('periodicity_type');
			$table->timestamps();

			$table->index('parent_id');
			$table->index('parent_type');
			$table->index('type');
			$table->index('name');

			if (!$this->testing) {
				$table->foreign('category_id')
					  ->references('id')
					  ->on('transaction_categories');
			}
		});

		$this->logCreateTable('transaction_periodicities');
		$this->schemaBuilder->create('transaction_periodicities', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->unsignedInteger('transaction_id');
			$table->morphs('transaction_periodicity', 'fk_transaction_periodicities_1');

			if (!$this->testing) {
				$table->foreign('transaction_id')
					  ->references('id')
					  ->on('transactions');
			}
		});

		$this->logCreateTable('transaction_periodicity_dailies');
		$this->schemaBuilder->create('transaction_periodicity_dailies', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->timestamps();
		});

		$this->logCreateTable('transaction_periodicity_monthlies');
		$this->schemaBuilder->create('transaction_periodicity_monthlies', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->tinyInteger('day_number');
			$table->timestamps();
		});

		$this->logCreateTable('transaction_periodicity_one_shots');
		$this->schemaBuilder->create('transaction_periodicity_one_shots', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->dateTime('date');
			$table->timestamps();
		});

		$this->logCreateTable('transaction_periodicity_weeklies');
		$this->schemaBuilder->create('transaction_periodicity_weeklies', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->tinyInteger('weekday_number');
			$table->timestamps();
		});

		$this->logCreateTable('transaction_periodicity_yearlies');
		$this->schemaBuilder->create('transaction_periodicity_yearlies', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->tinyInteger('month');
			$table->tinyInteger('day');
			$table->timestamps();
		});

		$this->logCreateTable('transaction_value_constants');
		$this->schemaBuilder->create('transaction_value_constants', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->decimal('value');
			$table->timestamps();
		});

		$this->logCreateTable('transaction_value_ranges');
		$this->schemaBuilder->create('transaction_value_ranges', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->decimal('value_from');
			$table->decimal('value_to');
			$table->timestamps();
		});

		$this->logCreateTable('transaction_schedules');
		$this->schemaBuilder->create('transaction_schedules', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->unsignedInteger('transaction_id');
			$table->dateTime('date');
			$table->timestamps();

			$table->index('date');

			if (!$this->testing) {
				$table->foreign('transaction_id')
					  ->references('id')
					  ->on('transactions');
			}
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();

		$this->schemaBuilder->dropIfExists('budgets');
		$this->schemaBuilder->dropIfExists('budget_consolidations');
		$this->schemaBuilder->dropIfExists('transaction_categories');
		$this->schemaBuilder->dropIfExists('transactions');
		$this->schemaBuilder->dropIfExists('transaction_periodicities');
		$this->schemaBuilder->dropIfExists('transaction_periodicity_dailies');
		$this->schemaBuilder->dropIfExists('transaction_periodicity_monthlies');
		$this->schemaBuilder->dropIfExists('transaction_periodicity_one_shots');
		$this->schemaBuilder->dropIfExists('transaction_periodicity_weeklies');
		$this->schemaBuilder->dropIfExists('transaction_periodicity_yearlies');
		$this->schemaBuilder->dropIfExists('transaction_value_constants');
		$this->schemaBuilder->dropIfExists('transaction_value_ranges');
		$this->schemaBuilder->dropIfExists('transaction_schedules');

		$this->schemaBuilder->enableForeignKeyConstraints();
	}

}
