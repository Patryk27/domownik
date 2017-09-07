<?php

namespace App\Services\Transaction;

use App\Http\Requests\Transaction\Crud\StoreRequest as TransactionStoreRequest;
use App\Http\Requests\Transaction\Crud\UpdateRequest as TransactionUpdateRequest;
use App\ValueObjects\Requests\Transaction\StoreResult as TransactionStoreResult;
use App\ValueObjects\Requests\Transaction\UpdateResult as TransactionUpdateResult;

interface RequestProcessorContract {

	/**
	 * @param TransactionStoreRequest $request
	 * @return TransactionStoreResult
	 */
	public function store(TransactionStoreRequest $request): TransactionStoreResult;

	/**
	 * @param TransactionUpdateRequest $request
	 * @param int $id
	 * @return TransactionUpdateResult
	 */
	public function update(TransactionUpdateRequest $request, int $id): TransactionUpdateResult;

	/**
	 * @param int $id
	 * @return void
	 */
	public function delete(int $id): void;

}