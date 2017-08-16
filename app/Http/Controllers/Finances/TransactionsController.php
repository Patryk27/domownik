<?php

namespace App\Http\Controllers\Finances;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Transaction\Crud\StoreRequest as TransactionStoreRequest;
use App\Http\Requests\Transaction\Crud\UpdateRequest as TransactionUpdateRequest;
use App\Models\Transaction;
use App\Services\Transaction\Request\ProcessorContract as TransactionRequestProcessorContract;
use Illuminate\Http\Request;

class TransactionsController
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

		$this->putFlash('success', __('requests/transaction/crud.messages.stored'));

		return response()->json([
			'redirectUrl' => route('finances.transactions.edit', $transaction->id),
		]);
	}

	/**
	 * @param Transaction $transaction
	 * @return mixed
	 */
	public function show(Transaction $transaction) {
		return redirect()->route('finances.transactions.edit', $transaction->id);
	}

	/**
	 * @param Request $request
	 * @param Transaction $transaction
	 * @return mixed
	 * @throws InvalidRequestException
	 */
	public function edit(Request $request, Transaction $transaction) {
		switch ($transaction->parent_type) {
			case Transaction::PARENT_TYPE_BUDGET:
				$request
					->session()
					->reflash();

				return redirect()->route('finances.budgets.transactions.edit', [$transaction->parent_id, $transaction->id]);

			default:
				throw new InvalidRequestException('Unexpected transaction parent type [%s].', $transaction->parent_type);
		}
	}

	/**
	 * @param TransactionUpdateRequest $request
	 * @param int $id
	 * @return mixed
	 */
	public function update(TransactionUpdateRequest $request, int $id) {
		$result = $this->transactionRequestProcessor->update($request, $id);
		$transaction = $result->getTransaction();

		$this->putFlash('success', __('requests/transaction/crud.messages.updated'));

		return response()->json([
			'redirectUrl' => route('finances.transactions.edit', $transaction->id),
		]);
	}

}