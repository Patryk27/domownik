<?php

namespace App\Modules\Finances\Services\Transaction;

use App\Modules\Finances\Http\Requests\Transaction\StoreRequest;

use App\Modules\Finances\Models\Transaction;

interface RequestManagerServiceContract {

	/**
	 * Stores given transaction.
	 * @param StoreRequest $createEditRequest
	 * @return RequestManagerServiceContract
	 */
	public function store(StoreRequest $request): RequestManagerServiceContract;

	/**
	 * Deletes given transaction.
	 * @param int $transactionId
	 * @return RequestManagerServiceContract
	 * @todo DeleteRequest $request
	 */
	public function delete(int $transactionId): RequestManagerServiceContract;

	/**
	 * Returns created/modified transaction model.
	 * @return Transaction
	 */
	public function getTransaction(): Transaction;

	/**
	 * Returns `true` if transaction is being created and `false` if it's updated.
	 * @return bool
	 */
	public function isNew(): bool;

}