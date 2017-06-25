<?php

namespace App\Services\Install;

use Illuminate\Database\Connection as DatabaseConnection;

class Manager {

	/**
	 * @var DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @param DatabaseConnection $databaseConnection
	 */
	public function __construct(
		DatabaseConnection $databaseConnection
	) {
		$this->databaseConnection = $databaseConnection;
	}

	/**
	 * @return bool
	 */
	public function isApplicationInstalled() {
		// @todo cache
		$schemaBuilder = $this->databaseConnection->getSchemaBuilder();
		return $schemaBuilder->hasTable('users');
	}

}