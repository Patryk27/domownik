<?php

namespace App\Services\Transaction\Request\Processor;

class Delete
	extends Base {

	/**
	 * @param int $id
	 * @return void
	 */
	public function process(int $id): void {
		$this->transactionPeriodicityRepository->deleteByTransactionId($id);
		$this->transactionScheduleRepository->deleteByTransactionId($id);
		$this->transactionRepository->delete($id);
	}

}