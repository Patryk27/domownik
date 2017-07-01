<?php

namespace Tests\Integration;

use App\Console\Kernel;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Contracts\Console\Kernel as KernelConsoleContract;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Connection as DatabaseConnection;

abstract class TestCase
	extends BaseTestCase {

	/**
	 * @var LoggerContract
	 */
	protected $logger;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var KernelConsoleContract
	 */
	protected $console;

	/**
	 * @return Application
	 */
	public function createApplication(): Application {
		putenv('DB_CONNECTION=testing');

		$app = require __DIR__ . '/../../bootstrap/app.php';
		$app->make(Kernel::class)
			->bootstrap();

		return $app;
	}

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->logger = $this->app->make(LoggerContract::class);
		$this->db = $this->app->make(DatabaseConnection::class);

		$this->dropTables();

		$this->console = $this->app->make(KernelConsoleContract::class);
		$this->console->call('migrate');
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @return $this
	 */
	protected function dropTables(): self {
		$this->logger->info('Dropping existing tables from the testing database: %s.', $this->db->getDatabaseName());

		$schemaBuilder = $this->db->getSchemaBuilder();

		foreach ($this->db->select('SHOW TABLES') as $tableName) {
			$tableName = (array)$tableName;
			$tableName = current($tableName);

			$schemaBuilder->drop($tableName);
		}

		return $this;
	}

}