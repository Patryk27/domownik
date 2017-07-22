<?php

namespace App\Services\User\RequestProcessor;

use App\Http\Requests\User\StoreRequest as UserStoreRequest;
use App\ValueObjects\Requests\User\StoreResult as UserStoreResult;

class Store
	extends Common {

	/**
	 * @param UserStoreRequest $request
	 * @return UserStoreResult
	 */
	public function process(UserStoreRequest $request): UserStoreResult {
		return $this->db->transaction(function() use ($request) {
			$this->log->info('Creating new user: %s', $request->get('login'));

			$user = $this->getUserFromRequest($request);
			$this->userRepository->persist($user);

			return new UserStoreResult($user);
		});
	}

}