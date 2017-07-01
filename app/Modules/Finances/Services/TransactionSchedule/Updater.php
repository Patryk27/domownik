<?php

namespace App\Modules\Finances\Services\TransactionSchedule;

use App\Modules\Finances\Models\TransactionSchedule;
use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Modules\Finances\Services\Transaction\PeriodicityParserContract;
use App\Services\Logger\Contract as LoggerContract;
use App\Support\Facades\Date;
use Carbon\Carbon;
use Illuminate\Database\Connection;

class Updater
	implements UpdaterContract {

	/**
	 * @var LoggerContract
	 */
	protected $logger;

	/**
	 * @var Connection
	 */
	protected $databaseConnection;

	/**
	 * @var TransactionScheduleRepositoryContract
	 */
	protected $transactionScheduleRepository;

	/**
	 * @var PeriodicityParserContract
	 */
	protected $transactionPeriodicityParserService;

	/**
	 * @param LoggerContract $logger
	 * @param Connection $databaseConnection
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param PeriodicityParserContract $transactionPeriodicityParserService
	 */
	public function __construct(
		LoggerContract $logger,
		Connection $databaseConnection,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		PeriodicityParserContract $transactionPeriodicityParserService
	) {
		$this->logger = $logger;
		$this->databaseConnection = $databaseConnection;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
		$this->transactionPeriodicityParserService = $transactionPeriodicityParserService;
	}

	/**
	 * @inheritDoc
	 */
	public function updateScheduleByTransactionId(int $transactionId): UpdaterContract {
		$this->logger->info('Updating schedule for transaction with id=%d.', $transactionId);

		$today = Carbon::today();

		$this->databaseConnection->beginTransaction();

		try {
			$this->transactionScheduleRepository->deleteByTransactionId($transactionId);

			$this->transactionPeriodicityParserService
				->reset()
				->setTransactionId($transactionId)
				->setDateRange($this->getScheduleUpdateBeginningDate(), $this->getScheduleUpdateEndingDate());

			$dates = $this->transactionPeriodicityParserService->getRows();

			foreach ($dates as $date) {
				if ($date->lt($today)) {
					$this->logger->info('-> NOT adding to schedule date \'%s\', because it\'s in the past.', $date->format('Y-m-d'));
					continue;
				}

				$this->logger->info('-> adding date to schedule: %s.', $date->format('Y-m-d'));

				$transactionSchedule = new TransactionSchedule();
				$transactionSchedule->transaction_id = $transactionId;
				$transactionSchedule->date = $date;
				$transactionSchedule->save();
			}

			$this->databaseConnection->commit();
		} catch (\Throwable $ex) {
			$this->databaseConnection->rollBack();
			throw $ex;
		} finally {
			$this->logger->info('Cleaning up...');

			$this->transactionScheduleRepository
				->getFlushCache()
				->flush();
		}

		return $this;
	}

	/**
	 * @return Carbon
	 */
	protected function getScheduleUpdateBeginningDate() {
		$date = Date::stripTime(new Carbon('now'));

		return $date;
	}

	/**
	 * @return Carbon
	 */
	protected function getScheduleUpdateEndingDate() {
		$date = $this->getScheduleUpdateBeginningDate();
		$date->addYear(1);

		return $date;
	}

}