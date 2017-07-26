<?php

namespace App\Http\Controllers\Finances;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Transaction\Crud\StoreRequest as TransactionStoreRequest;
use App\Models\Transaction;
use App\Services\Transaction\RequestProcessorContract as TransactionRequestProcessorContract;

class TransactionController
	extends BaseController {

	/**
	 * @var TransactionRequestProcessorContract
	 */
	protected $transactionRequestProcessor;

	/**
	 * @param TransactionRequestProcessorContract $transactionRequestProcessor
	 */
	public function __construct(
		TransactionRequestProcessorContract $transactionRequestProcessor
	) {
		$this->transactionRequestProcessor = $transactionRequestProcessor;
	}

	/**
	 * @param TransactionStoreRequest $request
	 * @return mixed
	 */
	public function store(TransactionStoreRequest $request) {
		$result = $this->transactionRequestProcessor->store($request);
		$transaction = $result->getTransaction();

		$this->flash('success', __('requests/transaction/crud.created'));

		return response()->json([
			'redirectUrl' => route('finances.transactions.edit', $transaction->id),
		]);
	}

	/**
	 * @param Transaction $transaction
	 * @return mixed
	 * @throws InvalidRequestException
	 */
	public function edit(Transaction $transaction) {
		switch ($transaction->parent_type) {
			case Transaction::PARENT_TYPE_BUDGET:
				return redirect()->route('finances.budgets.transactions.edit', [$transaction->parent_id, $transaction->id]);

			default:
				throw new InvalidRequestException('Unexpected transaction parent type [%s].', $transaction->parent_type);
		}
	}

}