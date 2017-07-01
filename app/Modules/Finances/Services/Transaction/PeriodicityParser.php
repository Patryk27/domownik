<?php

namespace App\Modules\Finances\Services\Transaction;

use App\Exceptions\Exception;
use App\Exceptions\ValidateException;
use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers\DailyMatcher;
use App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers\MatcherContract;
use App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers\MonthlyMatcher;
use App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers\OneShotMatcher;
use App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers\WeeklyMatcher;
use App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers\YearlyMatcher;
use App\ServiceContracts\BasicSearchContract;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Collection;

class PeriodicityParser
	implements PeriodicityParserContract {

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
	public function reset(): BasicSearchContract {
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
				$matcher = new OneShotMatcher();
				break;

			case Transaction::PERIODICITY_TYPE_DAILY:
				$matcher = new DailyMatcher();
				break;

			case Transaction::PERIODICITY_TYPE_WEEKLY:
				$matcher = new WeeklyMatcher();
				break;

			case Transaction::PERIODICITY_TYPE_MONTHLY:
				$matcher = new MonthlyMatcher();
				break;

			case Transaction::PERIODICITY_TYPE_YEARLY:
				$matcher = new YearlyMatcher();
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
	public function setTransactionId(int $transactionId): PeriodicityParserContract {
		$this->transactionId = $transactionId;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function setDateRange(Carbon $dateFrom, Carbon $dateTo): PeriodicityParserContract {
		$this->dateFrom = $dateFrom;
		$this->dateTo = $dateTo;

		return $this;
	}

	/**
	 * @return $this
	 * @throws ValidateException
	 */
	protected function validate(): PeriodicityParser {
		if (empty($this->transactionId)) {
			throw new ValidateException('Transaction id has not been set.');
		}

		if (empty($this->dateFrom)) {
			throw new ValidateException('Beginning date has not been set.');
		}

		if (empty($this->dateTo)) {
			throw new ValidateException('Ending date has not been set.');
		}

		if ($this->dateFrom > $this->dateTo) {
			throw new ValidationException('Beginning date is further in future than ending date.');
		}

		return $this;
	}

}