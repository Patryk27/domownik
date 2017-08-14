<?php

namespace App\Services\Transaction\Periodicity;

use App\Exceptions\Exception;
use App\Exceptions\ValidationException;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
use App\Services\Transaction\Periodicity\Parser\Matchers\DailyMatcher;
use App\Services\Transaction\Periodicity\Parser\Matchers\MatcherContract;
use App\Services\Transaction\Periodicity\Parser\Matchers\MonthlyMatcher;
use App\Services\Transaction\Periodicity\Parser\Matchers\OneShotMatcher;
use App\Services\Transaction\Periodicity\Parser\Matchers\WeeklyMatcher;
use App\Services\Transaction\Periodicity\Parser\Matchers\YearlyMatcher;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Parser
	implements ParserContract {

	/**
	 * @var TransactionRepositoryContract
	 */
	protected $transactionRepository;

	/**
	 * @var TransactionPeriodicityRepositoryContract
	 */
	protected $transactionPeriodicityRepository;

	/**
	 * @var int
	 */
	protected $transactionId;

	/**
	 * @var Carbon
	 */
	protected $dateFrom;

	/**
	 * @var Carbon
	 */
	protected $dateTo;

	/**
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 */
	public function __construct(
		TransactionRepositoryContract $transactionRepository,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	) {
		$this->transactionRepository = $transactionRepository;
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function reset() {
		$this->transactionId = null;
		$this->dateFrom = null;
		$this->dateTo = null;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getRows(): Collection {
		$this->validate();

		$transaction = $this->transactionRepository->getOrFail($this->transactionId);

		/**
		 * @var MatcherContract $matcher
		 */

		switch ($transaction->periodicity_type) {
			case Transaction::PERIODICITY_TYPE_ONE_SHOT:
				$matcher = new OneShotMatcher($this->transactionPeriodicityRepository);
				break;

			case Transaction::PERIODICITY_TYPE_DAILY:
				$matcher = new DailyMatcher();
				break;

			case Transaction::PERIODICITY_TYPE_WEEKLY:
				$matcher = new WeeklyMatcher($this->transactionPeriodicityRepository);
				break;

			case Transaction::PERIODICITY_TYPE_MONTHLY:
				$matcher = new MonthlyMatcher($this->transactionPeriodicityRepository);
				break;

			case Transaction::PERIODICITY_TYPE_YEARLY:
				$matcher = new YearlyMatcher($this->transactionPeriodicityRepository);
				break;

			default:
				throw new Exception('Unexpected transaction periodicity type: %s.', $transaction->periodicity_type);
		}

		$matcher
			->loadTransaction($transaction)
			->filterRange($this->dateFrom, $this->dateTo);

		return $matcher->getMatchingDates();
	}

	/**
	 * @inheritDoc
	 */
	public function setTransactionId(int $transactionId) {
		$this->transactionId = $transactionId;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function setDateRange(Carbon $dateFrom, Carbon $dateTo) {
		$this->dateFrom = $dateFrom;
		$this->dateTo = $dateTo;

		return $this;
	}

	/**
	 * @return $this
	 * @throws ValidationException
	 */
	protected function validate() {
		if (empty($this->transactionId)) {
			throw new ValidationException('Transaction id has not been set.');
		}

		if (empty($this->dateFrom)) {
			throw new ValidationException('Beginning date has not been set.');
		}

		if (empty($this->dateTo)) {
			throw new ValidationException('Ending date has not been set.');
		}

		if ($this->dateFrom > $this->dateTo) {
			throw new ValidationException('Beginning date is further in future than ending date.');
		}

		return $this;
	}

}