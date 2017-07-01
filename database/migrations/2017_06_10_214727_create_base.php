<?php

use Illuminate\Database\Schema\Blueprint;

class CreateBase
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->logCreateTable('modules');
		$this->schemaBuilder->create('modules', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->char('name', 32);

			$table->unique('name');
		});

		$this->logCreateTable('module_settings');
		$this->schemaBuilder->create('module_settings', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->unsignedInteger('module_id');
			$table->char('key', 128);
			$table->string('value');
			$table->timestamps();

			$table->index('module_id');
			$table->index(['module_id', 'key']);

			$table->foreign('module_id')
				  ->references('id')
				  ->on('modules');
		});

		$this->logCreateTable('users');
		$this->schemaBuilder->create('users', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->char('login', 64);
			$table->char('password', 128);
			$table->char('full_name', 128);
			$table->rememberToken();
			$table->boolean('is_active');
			$table->timestamps();

			$table->index('login');
			$table->index('full_name');
			$table->index('is_active');
		});

		$this->logCreateTable('settings');
		$this->schemaBuilder->create('settings', function(Blueprint $table) {
			if ($this->testing) {
				$table->engine = 'MyISAM';
			}

			$table->increments('id');
			$table->unsignedInteger('user_id')
				  ->nullable();
			$table->char('key', 128);
			$table->text('value');
			$table->timestamps();

			$table->index('user_id');
			$table->index(['user_id', 'key']);

			$table->foreign('user_id')
				  ->references('id')
				  ->on('users');
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();

		$this->schemaBuilder->dropIfExists('modules');
		$this->schemaBuilder->dropIfExists('module_settings');
		$this->schemaBuilder->dropIfExists('users');
		$this->schemaBuilder->dropIfExists('settings');

		$this->schemaBuilder->enableForeignKeyConstraints();
	}
}
