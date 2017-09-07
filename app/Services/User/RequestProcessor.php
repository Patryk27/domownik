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
	protected $storeRequestProcessor;

	/**
	 * @var UserUpdateRequestProcessor
	 */
	protected $updateRequestProcessor;

	/**
	 * @var UserDeleteRequestProcessor
	 */
	protected $deleteRequestProcessor;

	/**
	 * @param UserStoreRequestProcessor $storeRequestProcessor
	 * @param UserUpdateRequestProcessor $updateRequestProcessor
	 * @param UserDeleteRequestProcessor $deleteRequestProcessor
	 */
	public function __construct(
		UserStoreRequestProcessor $storeRequestProcessor,
		UserUpdateRequestProcessor $updateRequestProcessor,
		UserDeleteRequestProcessor $deleteRequestProcessor
	) {
		$this->storeRequestProcessor = $storeRequestProcessor;
		$this->updateRequestProcessor = $updateRequestProcessor;
		$this->deleteRequestProcessor = $deleteRequestProcessor;
	}

	/**
	 * @inheritDoc
	 */
	public function store(UserStoreRequest $request): UserStoreResult {
		return $this->storeRequestProcessor->process($request);
	}

	/**
	 * @inheritDoc
	 */
	public function update(UserUpdateRequest $request, int $id): UserUpdateResult {
		return $this->updateRequestProcessor->process($request, $id);
	}

	/**
	 * @inheritDoc
	 */
	public function delete(int $id): void {
		$this->deleteRequestProcessor->process($id);
	}

}