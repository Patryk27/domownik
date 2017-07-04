<?php

use App\Models\User;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Seeder;

class UserSeeder
	extends Seeder {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @param LoggerContract $log
	 */
	public function __construct(
		LoggerContract $log
	) {
		$this->log = $log;
	}

	/**
	 * @return void
	 */
	public function run() {
		$user =
			User::where('login', 'admin')
				->first();

		if (!empty($user)) {
			$this->log->warning('Not creating \'admin\' user because one already exists.');
			return;
		}

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