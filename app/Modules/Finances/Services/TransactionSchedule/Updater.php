<?php

namespace App\Modules\Finances\Services\TransactionSchedule;

use App\Modules\Finances\Models\TransactionSchedule;
use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Modules\Finances\Services\Transaction\PeriodicityParserContract;
use App\Support\Facades\Date;
use App\Support\Facades\MyLog;
use Carbon\Carbon;
use Illuminate\Database\Connection;

class Updater
	implements UpdaterContract {

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
	 * @param Connection $databaseConnection
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param PeriodicityParserContract $transactionPeriodicityParserService
	 */
	public function __construct(
		Connection $databaseConnection,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		PeriodicityParserContract $transactionPeriodicityParserService
	) {
		$this->databaseConnection = $databaseConnection;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
		$this->transactionPeriodicityParserService = $transactionPeriodicityParserService;
	}

	/**
	 * @inheritDoc
	 */
	public function updateScheduleByTransactionId(int $transactionId): UpdaterContract {
		MyLog::info('Updating schedule for transaction with id=%d.', $transactionId);

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
					MyLog::info('-> NOT adding to schedule date \'%s\', because it\'s in the past.', $date->format('Y-m-d'));
					continue;
				}

				MyLog::info('-> adding date to schedule: %s.', $date->format('Y-m-d'));

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
			MyLog::info('Cleaning up...');

			// flush caches
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