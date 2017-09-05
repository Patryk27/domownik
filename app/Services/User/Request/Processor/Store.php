<?php

namespace App\Services\User\Request\Processor;

use App\Http\Requests\User\Crud\StoreRequest as UserStoreRequest;
use App\ValueObjects\Requests\User\StoreResult as UserStoreResult;

class Store
	extends Base {

	/**
	 * @param UserStoreRequest $request
	 * @return UserStoreResult
	 */
	public function process(UserStoreRequest $request): UserStoreResult {
		return $this->db->transaction(function () use ($request) {
			$this->log->info('Creating new user with login [%s].', $request->get('login'));

			$user = $this->getUserFromRequest($request);
			$this->userRepository->persist($user);

			return new UserStoreResult($user);
		});
	}

}