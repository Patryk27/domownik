<?php

namespace App\Services\Install;

use Illuminate\Database\Connection as DatabaseConnection;

class Manager
	implements ManagerContract {

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @param DatabaseConnection $db
	 */
	public function __construct(
		DatabaseConnection $db
	) {
		$this->db = $db;
	}

	/**
	 * @return bool
	 */
	public function isApplicationInstalled(): bool {
		// @todo some cache would be nice here

		// no application key -> application certainly not installed
		if (is_null(env('APP_KEY'))) {
			return false;
		}

		// no 'migrations' table -> application certainly not installed
		$schemaBuilder = $this->db->getSchemaBuilder();

		if (!$schemaBuilder->hasTable('migrations')) {
			return false;
		}

		// as for now, the app's most likely been installed
		return true;
	}

}