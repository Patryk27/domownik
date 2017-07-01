<?php

namespace Tests\Unit;

use App\Console\Kernel;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase
	extends BaseTestCase {

	/**
	 * @return Application
	 */
	public function createApplication(): Application {
		$app = require __DIR__ . '/../../bootstrap/app.php';

		$app->make(Kernel::class)
			->bootstrap();

		return $app;
	}

	/**
	 * @return User
	 */
	protected function getSampleUser(): User {
		return new User([
			'id' => 666,
			'login' => 'sample-user',
			'full_name' => 'Sample User',
			'status' => User::STATUS_ACTIVE,
		]);
	}

}