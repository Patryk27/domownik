<?php

namespace App\Services\Transaction\RequestProcessor;

use App\Http\Requests\Transaction\Crud\StoreRequest as TransactionStoreRequest;
use App\Models\Transaction;
use App\ValueObjects\Requests\Transaction\StoreResult as TransactionStoreResult;

class Store
	extends Base {

	/**
	 * @param TransactionStoreRequest $request
	 * @return TransactionStoreResult
	 */
	public function process(TransactionStoreRequest $request): TransactionStoreResult {
		return $this->db->transaction(function() use ($request) {
			$transaction = new Transaction();
			$transaction->parent_id = $request->get('parent_id');
			$transaction->parent_type = $request->get('parent_type');

			$this->parseCrudRequest($request, $transaction);
			$this->transactionRepository->persist($transaction);

			return new TransactionStoreResult($transaction);
		});
	}

}