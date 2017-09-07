<?php

namespace App\Services\Transaction\RequestProcessor;

class Delete
	extends Base {

	/**
	 * @param int $id
	 * @return void
	 */
	public function process(int $id): void {
		$this->transactionRepository->delete($id);
	}

}