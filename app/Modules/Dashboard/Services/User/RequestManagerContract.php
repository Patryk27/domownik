<?php

namespace App\Modules\Dashboard\Services\User;

use App\Models\User;
use App\Modules\Dashboard\Http\Requests\User\StoreRequest as UserStoreRequest;
use App\ServiceContracts\RequestManagerContract as BaseRequestManagerContract;

interface RequestManagerContract
	extends BaseRequestManagerContract {

	/**
	 * @param UserStoreRequest $request
	 * @return string
	 */
	public function store($request): string;

	/**
	 * @param int $userId
	 * @return RequestManagerContract
	 */
	public function delete(int $userId): RequestManagerContract;

	/**
	 * @return User|null
	 */
	public function getModel();

}