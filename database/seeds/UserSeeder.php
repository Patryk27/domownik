<?php

use App\Models\User;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Seeder;

class UserSeeder
	extends Seeder {

	/**
	 * @var LoggerContract
	 */
	protected $logger;

	/**
	 * @param LoggerContract $logger
	 */
	public function __construct(
		LoggerContract $logger
	) {
		$this->logger = $logger;
	}

	/**
	 * @return void
	 */
	public function run() {
		$user =
			User::where('login', 'admin')
				->first();

		if (!empty($user)) {
			$this->logger->warning('Not creating \'admin\' user because one already exists.');
			return;
		}

		$this->logger->info('Creating \'admin\' user...');

		$user = new User();
		$user->login = 'admin';
		$user->password = bcrypt('admin');
		$user->full_name = 'Admin Admin';
		$user->is_active = 1;
		$user->save();

		$this->logger->info('Flushing cache...');
		Cache::flush();
	}

}