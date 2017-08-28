<?php

use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable
	extends Migration {

	/**
	 * @return void
	 */
	public function up() {
		$this->schemaBuilder->create('settings', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->nullable();
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
		$this->schemaBuilder->dropIfExists('settings');
		$this->schemaBuilder->enableForeignKeyConstraints();
	}
}
