<?php

use App\Models\User;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Seeder;

class UserSeeder
	extends Seeder {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db
	) {
		$this->log = $log;
		$this->db = $db;
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->log->info('Truncating \'users\' table...');

		$this->db
			->table('users')
			->delete();

		$this->log->info('Creating \'admin\' user...');

		$user = new User();
		$user->login = 'admin';
		$user->password = bcrypt('admin');
		$user->full_name = 'Admin Admin';
		$user->status = User::STATUS_ACTIVE;
		$user->save();

		$this->log->info('Flushing cache...');
		Cache::flush();
	}

}