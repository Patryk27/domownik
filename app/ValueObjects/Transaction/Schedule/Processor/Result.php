<?php

namespace App\ValueObjects\Transaction\Schedule\Processor;

use App\ValueObjects\HasInitializationConstructor;

class Result {

	use HasInitializationConstructor;

	/**
	 * Number of processed scheduled transactions.
	 * @var int
	 */
	protected $processedTransactionCount;

	/**
	 * @return int
	 */
	public function getProcessedTransactionCount(): int {
		return $this->processedTransactionCount;
	}

}