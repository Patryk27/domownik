<?php

namespace App\Repositories\Eloquent;

use App\Models\TransactionSchedule;
use App\Repositories\Contracts\TransactionScheduleRepositoryContract;

class TransactionScheduleRepository
	extends AbstractCrudRepository
	implements TransactionScheduleRepositoryContract {

	/**
	 * @inheritDoc
	 */
	public function deleteByTransactionId(int $transactionId): TransactionScheduleRepositoryContract {
		TransactionSchedule
			::where('transaction_id', $transactionId)
			->delete();

		$this
			->getFlushCache()
			->flush();

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	protected function getModelName(): string {
		return TransactionSchedule::class;
	}

}