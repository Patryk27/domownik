<?php

namespace App\ValueObjects\Requests\Transaction;

use App\Models\Transaction;

class UpdateResult {

	/**
	 * @var Transaction
	 */
	protected $transaction;

	/**
	 * @param Transaction $transaction
	 */
	public function __construct(
		Transaction $transaction
	) {
		$this->transaction = $transaction;
	}

	/**
	 * @return Transaction
	 */
	public function getTransaction(): Transaction {
		return $this->transaction;
	}

}