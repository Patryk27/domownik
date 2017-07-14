<?php

namespace App\Services\User;

use App\Models\User;
use App\Http\Requests\User\StoreRequest as UserStoreRequest;
use App\ServiceContracts\RequestManagerContract as BaseRequestManagerContract;

interface RequestManagerContract
	extends BaseRequestManagerContract {

	/**
	 * @param UserStoreRequest $request
	 * @return string
	 */
	public function store(UserStoreRequest $request): string;

	/**
	 * @param int $userId
	 * @return RequestManagerContract
	 */
	public function delete(int $userId): RequestManagerContract;

	/**
	 * @return User
	 */
	public function getModel(): User;

}