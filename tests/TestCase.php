<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase
	extends BaseTestCase {

	use CreatesApplication;

	/**
	 * @return User
	 */
	protected function getSampleUser() {
		return new User([
			'id' => 666,
			'login' => 'sample-user',
			'full_name' => 'Sample User',
			'status' => User::STATUS_ACTIVE,
		]);
	}

}