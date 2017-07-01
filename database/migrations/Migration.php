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
	protected $logger;

	/**
	 * @var DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @var SchemaBuilder
	 */
	protected $schemaBuilder;

	/**
	 * Migration constructor.
	 */
	public function __construct() {
		$this->logger = app()->make(LoggerContract::class);

		$this->databaseConnection = app()->make(DatabaseConnection::class);
		$this->schemaBuilder = $this->databaseConnection->getSchemaBuilder();
	}

	/**
	 * @param string $tableName
	 * @return $this
	 */
	protected function logCreateTable(string $tableName): self {
		$this->logger->info('Creating table: %s.', $tableName);
		return $this;
	}

	/**
	 * @param string $tableName
	 * @return $this
	 */
	protected function logAlterTable(string $tableName): self {
		$this->logger->info('Altering table: %s.', $tableName);
		return $this;
	}

}