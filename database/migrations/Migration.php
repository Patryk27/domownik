<?php

use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Migrations\Migration as BaseMigration;
use Illuminate\Database\Schema\Builder as SchemaBuilder;

abstract class Migration
	extends BaseMigration {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var SchemaBuilder
	 */
	protected $schemaBuilder;

	/**
	 * Equal to 'true' if migration is begin run on a testing database.
	 * @var bool
	 */
	protected $testing;

	/**
	 * Migration constructor.
	 */
	public function __construct() {
		$this->log = app()->make(LoggerContract::class);
		$this->db = app()->make(DatabaseConnection::class);
		$this->schemaBuilder = $this->db->getSchemaBuilder();

		$this->testing = true; // @todo
	}

	/**
	 * @param string $tableName
	 * @return $this
	 */
	protected function logCreateTable(string $tableName): self {
		$this->log->info('Creating table: %s.', $tableName);
		return $this;
	}

	/**
	 * @param string $tableName
	 * @return $this
	 */
	protected function logAlterTable(string $tableName): self {
		$this->log->info('Altering table: %s.', $tableName);
		return $this;
	}

}