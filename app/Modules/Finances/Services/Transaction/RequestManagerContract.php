<?php

namespace App\Modules\Finances\Services\Transaction;

use App\Modules\Finances\Http\Requests\Transaction\StoreRequest as TransactionStoreRequest;
use App\Modules\Finances\Models\Transaction;
use App\ServiceContracts\RequestManagerContract as BaseRequestManagerContract;

interface RequestManagerContract
	extends BaseRequestManagerContract {

	/**
	 * @param TransactionStoreRequest $request
	 * @return string
	 */
	public function store(TransactionStoreRequest $request): string;

	/**
	 * @param int $transactionId
	 * @return RequestManagerContract
	 */
	public function delete(int $transactionId): RequestManagerContract;

	/**
	 * @return Transaction
	 */
	public function getModel(): Transaction;

}