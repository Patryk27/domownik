<?php

namespace App\ValueObjects\Requests\User;

use App\Models\User;

class StoreResult {

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @param User $user
	 */
	public function __construct(
		User $user
	) {
		$this->user = $user;
	}

	/**
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}

}