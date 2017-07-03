<?php

namespace App\Services\Install;

use Illuminate\Database\Connection as DatabaseConnection;

class Manager {

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
		// @todo cache
		$schemaBuilder = $this->db->getSchemaBuilder();
		return $schemaBuilder->hasTable('users');
	}

}