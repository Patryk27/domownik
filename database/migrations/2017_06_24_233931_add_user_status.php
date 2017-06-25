<?php

use Illuminate\Database\Schema\Blueprint;

class AddUserStatus
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->logAlterTable('users');
		$this->schemaBuilder->table('users', function(Blueprint $table) {
			$table->char('status', 32)
				  ->after('is_active');

			$table->index('status');

			$table->dropIndex('users_is_active_index');
			$table->dropColumn('is_active');
		});

		$this->databaseConnection->update('
			UPDATE
				`users` u
				
			SET
				u.`status` = "active"
		');
	}

	/**
	 * @return void
	 */
	public function down() {
		$this->logAlterTable('users');
		$this->schemaBuilder->table('users', function(Blueprint $table) {
			$table->boolean('is_active')
				  ->after('status');

			$table->index('is_active');

			$table->dropIndex('users_status_index');
			$table->dropColumn('status');
		});

		$this->databaseConnection->update('
			UPDATE
				`users` u
				
			SET
				u.`is_active` = 1
		');
	}
}
