<?php

namespace App\ValueObjects;

use App\Exceptions\ValueObjectException;
use App\Models\Transaction;
use App\Models\TransactionValueConstant;
use App\Models\TransactionValueRange;

// @todo ranged cost?
class EstimatedCost {

	/**
	 * @var float
	 */
	protected $estimateMin;

	/**
	 * @var float
	 */
	protected $estimateMax;

	/**
	 * @param float $estimateMin
	 * @param float $estimateMax
	 */
	public function __construct(
		float $estimateMin,
		float $estimateMax
	) {
		$this->estimateMin = $estimateMin;
		$this->estimateMax = $estimateMax;
	}

	/**
	 * @param mixed $value
	 * @return EstimatedCost
	 * @throws ValueObjectException
	 */
	public static function build($value) {
		if (is_object($value)) {
			if ($value instanceof Transaction) {
				return self::buildByTransaction($value);
			} elseif ($value instanceof TransactionValueConstant || $value instanceof TransactionValueRange) {
				return self::buildByTransactionValue($value);
			}
		}

		throw new ValueObjectException('Could not build %s from given value.', __CLASS__);
	}

	/**
	 * @return float
	 */
	public function getEstimateMin(): float {
		return $this->estimateMin;
	}

	/**
	 * @return float
	 */
	public function getEstimateMax(): float {
		return $this->estimateMax;
	}

	/**
	 * @return float
	 */
	public function getEstimate(): float {
		return ($this->estimateMin + $this->estimateMax) / 2;
	}

	/**
	 * @param Transaction $transaction
	 * @return EstimatedCost
	 */
	protected static function buildByTransaction(Transaction $transaction) {
		$cost = self::build($transaction->value);

		if ($transaction->isIncome()) {
			return $cost;
		}

		/**
		 * If transaction is expense, we need to manually negate the cost's estimate.
		 * That happens because the transaction's value is never negative.
		 */

		return new self(-$cost->getEstimateMax(), -$cost->getEstimateMin());
	}

	/**
	 * @param TransactionValueConstant|TransactionValueRange $transactionValue
	 * @return EstimatedCost
	 */
	protected static function buildByTransactionValue($transactionValue) {
		if ($transactionValue instanceof TransactionValueConstant) {
			return new self($transactionValue->value, $transactionValue->value);
		} else {
			return new self($transactionValue->value_from, $transactionValue->value_to);
		}
	}

}