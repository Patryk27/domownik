<?php

use App\Models\User;
use App\Support\Classes\MyLog;
use Illuminate\Database\Seeder;

class UserSeeder
	extends Seeder {

	/**
	 * @var MyLog
	 */
	protected $myLog;

	/**
	 * BudgetSeeder constructor.
	 * @param MyLog $myLog
	 */
	public function __construct(
		MyLog $myLog
	) {
		$this->myLog = $myLog;
	}

	/**
	 * @return void
	 */
	public function run() {
		$user =
			User::where('login', 'admin')
				->first();

		if (!empty($user)) {
			$this->myLog->warning('Not creating \'admin\' user, because one already exists.');
			return;
		}

		$this->myLog->info('Creating \'admin\' user...');

		$user = new User();
		$user->login = 'admin';
		$user->password = bcrypt('admin');
		$user->full_name = 'Admin Admin';
		$user->is_active = 1;
		$user->save();

		$this->myLog->info('Flushing cache...');
		Cache::flush();
	}

}