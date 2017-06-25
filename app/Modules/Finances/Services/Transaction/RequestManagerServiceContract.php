<?php

namespace App\Modules\Finances\Services\Transaction;

use App\Modules\Finances\Http\Requests\Transaction\StoreRequest;

use App\Modules\Finances\Models\Transaction;
use App\ServiceContracts\RequestManagerContract as BaseRequestManagerContract;

interface RequestManagerServiceContract
	extends BaseRequestManagerContract {

	/**
	 * @param StoreRequest $request
	 * @return string
	 */
	public function store($request): string;

	/**
	 * @param int $transactionId
	 * @return RequestManagerServiceContract
	 */
	public function delete(int $transactionId): RequestManagerServiceContract;

	/**
	 * @return Transaction
	 */
	public function getModel(): Transaction;

}