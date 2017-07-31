<?php

namespace App\Services\User;

use App\Http\Requests\User\Crud\StoreRequest as UserStoreRequest;
use App\Http\Requests\User\Crud\UpdateRequest as UserUpdateRequest;
use App\Services\User\RequestProcessor\Delete as UserDeleteRequestProcessor;
use App\Services\User\RequestProcessor\Store as UserStoreRequestProcessor;
use App\Services\User\RequestProcessor\Update as UserUpdateRequestProcessor;
use App\ValueObjects\Requests\User\StoreResult as UserStoreResult;
use App\ValueObjects\Requests\User\UpdateResult as UserUpdateResult;

class RequestProcessor
	implements RequestProcessorContract {

	/**
	 * @var UserStoreRequestProcessor
	 */
	protected $userCreator;

	/**
	 * @var UserUpdateRequestProcessor
	 */
	protected $userUpdater;

	/**
	 * @var UserDeleteRequestProcessor
	 */
	protected $userDeleter;

	/**
	 * @param UserStoreRequestProcessor $userCreator
	 * @param UserUpdateRequestProcessor $userUpdater
	 * @param UserDeleteRequestProcessor $userDeleter
	 */
	public function __construct(
		UserUpdateRequestProcessor $userUpdater,
		UserStoreRequestProcessor $userCreator,
		UserDeleteRequestProcessor $userDeleter
	) {
		$this->userCreator = $userCreator;
		$this->userUpdater = $userUpdater;
		$this->userDeleter = $userDeleter;
	}

	/**
	 * @inheritDoc
	 */
	public function store(UserStoreRequest $request): UserStoreResult {
		return $this->userCreator->process($request);
	}

	/**
	 * @inheritDoc
	 */
	public function update(UserUpdateRequest $request, int $id): UserUpdateResult {
		return $this->userUpdater->process($request, $id);
	}

	/**
	 * @inheritDoc
	 */
	public function delete(int $id): void {
		$this->userDeleter->process($id);
	}

}