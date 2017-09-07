<?php

namespace App\Services\Transaction;

use App\Http\Requests\Transaction\Crud\StoreRequest as TransactionStoreRequest;
use App\Http\Requests\Transaction\Crud\UpdateRequest as TransactionUpdateRequest;
use App\Services\Transaction\RequestProcessor\Delete as TransactionDeleteRequestProcessor;
use App\Services\Transaction\RequestProcessor\Store as TransactionStoreRequestProcessor;
use App\Services\Transaction\RequestProcessor\Update as TransactionUpdateRequestProcessor;
use App\ValueObjects\Requests\Transaction\StoreResult as TransactionStoreResult;
use App\ValueObjects\Requests\Transaction\UpdateResult as TransactionUpdateResult;

class RequestProcessor
	implements RequestProcessorContract {

	/**
	 * @var TransactionStoreRequestProcessor
	 */
	protected $storeRequestProcessor;

	/**
	 * @var TransactionUpdateRequestProcessor
	 */
	protected $updateRequestProcessor;

	/**
	 * @var TransactionDeleteRequestProcessor
	 */
	protected $deleteRequestProcessor;

	/**
	 * @param TransactionStoreRequestProcessor $creator
	 * @param TransactionUpdateRequestProcessor $updater
	 * @param TransactionDeleteRequestProcessor $deleter
	 */
	public function __construct(
		TransactionStoreRequestProcessor $creator,
		TransactionUpdateRequestProcessor $updater,
		TransactionDeleteRequestProcessor $deleter
	) {
		$this->storeRequestProcessor = $creator;
		$this->updateRequestProcessor = $updater;
		$this->deleteRequestProcessor = $deleter;
	}

	/**
	 * @inheritDoc
	 */
	public function store(TransactionStoreRequest $request): TransactionStoreResult {
		return $this->storeRequestProcessor->process($request);
	}

	/**
	 * @inheritDoc
	 */
	public function update(TransactionUpdateRequest $request, int $id): TransactionUpdateResult {
		return $this->updateRequestProcessor->process($request, $id);
	}

	/**
	 * @inheritDoc
	 */
	public function delete(int $id): void {
		$this->deleteRequestProcessor->process($id);
	}

}