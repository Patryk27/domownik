<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;

/**
 * @var User $model
 */
class UserRepository
	extends AbstractCrudRepository
	implements UserRepositoryContract {

	/**
	 * @inheritdoc
	 */
	protected function getModelName(): string {
		return User::class;
	}

}