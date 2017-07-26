<?php

namespace App\Services\User\RequestProcessor;

use App\Http\Requests\User\UpdateRequest as UserUpdateRequest;
use App\ValueObjects\Requests\User\UpdateResult as UserUpdateResult;

class Update
	extends Base {

	/**
	 * @param UserUpdateRequest $request
	 * @param int $id
	 * @return UserUpdateResult
	 */
	public function process(UserUpdateRequest $request, int $id): UserUpdateResult {
		return $this->db->transaction(function() use ($request, $id) {
			$this->log->info('Updating user with id=%d: %s', $id, $request->get('login'));

			$user = $this->getUserFromRequest($request);
			$this->userRepository->persistUpdate($user, $id);

			return new UserUpdateResult($user);
		});
	}
	
}