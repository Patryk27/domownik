<?php

namespace App\Modules\Finances\Services\TransactionSchedule;

use App\Modules\Finances\Models\TransactionSchedule;
use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Modules\Finances\Services\Transaction\PeriodicityParserServiceContract;
use App\Support\Facades\MyLog;
use Carbon\Carbon;
use Illuminate\Database\Connection;

class UpdaterService
	implements UpdaterServiceContract {

	/**
	 * @var Connection
	 */
	protected $databaseConnection;

	/**
	 * @var TransactionScheduleRepositoryContract
	 */
	protected $transactionScheduleRepository;

	/**
	 * @var PeriodicityParserServiceContract
	 */
	protected $transactionPeriodicityParserService;

	/**
	 * UpdaterService constructor.
	 * @param Connection $databaseConnection
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param PeriodicityParserServiceContract $transactionPeriodicityParserService
	 */
	public function __construct(
		Connection $databaseConnection,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		PeriodicityParserServiceContract $transactionPeriodicityParserService
	) {
		$this->databaseConnection = $databaseConnection;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
		$this->transactionPeriodicityParserService = $transactionPeriodicityParserService;
	}

	/**
	 * @inheritDoc
	 */
	public function updateScheduleByTransactionId(int $transactionId): UpdaterServiceContract {
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
		$date = new Carbon('now');
		$date->setTime(0, 0, 0);

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