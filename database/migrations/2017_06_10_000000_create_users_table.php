<?php

use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->char('login', 64);
			$table->char('password', 128);
			$table->char('full_name', 128);
			$table->char('status', 32);
			$table->rememberToken();
			$table->timestamps();

			$table->index('login');
			$table->index('full_name');
			$table->index('status');
		});
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->schemaBuilder->disableForeignKeyConstraints();
		$this->schemaBuilder->dropIfExists('users');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}
}
