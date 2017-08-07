<?php

namespace App\Services\Transaction\Request;

use App\Http\Requests\Transaction\Crud\StoreRequest as TransactionStoreRequest;
use App\Http\Requests\Transaction\Crud\UpdateRequest as TransactionUpdateRequest;
use App\Services\Transaction\Request\Processor\Delete as TransactionDeleteRequestProcessor;
use App\Services\Transaction\Request\Processor\Store as TransactionStoreRequestProcessor;
use App\Services\Transaction\Request\Processor\Update as TransactionUpdateRequestProcessor;
use App\ValueObjects\Requests\Transaction\StoreResult as TransactionStoreResult;
use App\ValueObjects\Requests\Transaction\UpdateResult as TransactionUpdateResult;

class Processor
	implements ProcessorContract {

	/**
	 * @var TransactionStoreRequestProcessor
	 */
	protected $creator;

	/**
	 * @var TransactionUpdateRequestProcessor
	 */
	protected $updater;

	/**
	 * @var TransactionDeleteRequestProcessor
	 */
	protected $deleter;

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
		$this->creator = $creator;
		$this->updater = $updater;
		$this->deleter = $deleter;
	}

	/**
	 * @inheritDoc
	 */
	public function store(TransactionStoreRequest $request): TransactionStoreResult {
		return $this->creator->process($request);
	}

	/**
	 * @inheritDoc
	 */
	public function update(TransactionUpdateRequest $request, int $id): TransactionUpdateResult {
		return $this->updater->process($request, $id);
	}

	/**
	 * @inheritDoc
	 */
	public function delete(int $id): void {
		$this->deleter->process($id);
	}

}