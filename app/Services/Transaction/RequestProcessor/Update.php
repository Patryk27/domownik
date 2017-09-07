<?php

namespace App\Services\Transaction\RequestProcessor;

use App\Http\Requests\Transaction\Crud\UpdateRequest as TransactionUpdateRequest;
use App\ValueObjects\Requests\Transaction\UpdateResult as TransactionUpdateResult;

class Update
	extends Base {

	/**
	 * @param TransactionUpdateRequest $request
	 * @param int $id
	 * @return TransactionUpdateResult
	 */
	public function process(TransactionUpdateRequest $request, int $id): TransactionUpdateResult {
		return $this->db->transaction(function () use ($request, $id) {
			$transaction = $this->transactionRepository->getOrFail($id);

			$this->updateTransactionFromRequest($transaction, $request);

			$this->transactionRepository->persistUpdate($transaction, $id);
			$this->transactionScheduleUpdater->updateTransactionSchedule($transaction->id);

			return new TransactionUpdateResult($transaction);
		});
	}

}