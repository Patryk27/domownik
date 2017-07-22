<?php

namespace App\Services\User;

use App\Http\Requests\User\StoreRequest as UserStoreRequest;
use App\Http\Requests\User\UpdateRequest as UserUpdateRequest;
use App\ValueObjects\Requests\User\StoreResult as UserStoreResult;
use App\ValueObjects\Requests\User\UpdateResult as UserUpdateResult;

interface RequestProcessorContract {

	/**
	 * @param UserStoreRequest $request
	 * @return UserStoreResult
	 */
	public function store(UserStoreRequest $request): UserStoreResult;

	/**
	 * @param UserUpdateRequest $request
	 * @param int $id
	 * @return UserUpdateResult
	 */
	public function update(UserUpdateRequest $request, int $id): UserUpdateResult;

	/**
	 * @param int $id
	 * @return void
	 */
	public function delete(int $id): void;

}