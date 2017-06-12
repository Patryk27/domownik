<?php

use App\Support\Classes\MyLog;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Migrations\Migration as BaseMigration;
use Illuminate\Database\Schema\Builder as SchemaBuilder;

abstract class Migration
	extends BaseMigration {

	/**
	 * @var DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @var SchemaBuilder
	 */
	protected $schemaBuilder;

	/**
	 * @var MyLog
	 */
	protected $myLog;

	/**
	 * Migration constructor.
	 */
	public function __construct() {
		$this->databaseConnection = app()->make(DatabaseConnection::class);
		$this->schemaBuilder = $this->databaseConnection->getSchemaBuilder();

		$this->myLog = app()->make(MyLog::class);
	}

	/**
	 * @param string $tableName
	 * @return $this
	 */
	protected function logCreateTable(string $tableName): self {
		$this->myLog->info('Creating table: %s.', $tableName);
		return $this;
	}

	/**
	 * @param string $tableName
	 * @return $this
	 */
	protected function logAlterTable(string $tableName): self {
		$this->myLog->info('Altering table: %s.', $tableName);
		return $this;
	}

}