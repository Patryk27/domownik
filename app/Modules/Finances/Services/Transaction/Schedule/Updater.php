<?php

namespace App\Modules\Finances\Services\Transaction\Schedule;

use App\Modules\Finances\Models\TransactionSchedule;
use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Modules\Finances\Services\Transaction\PeriodicityParserContract;
use App\Services\Logger\Contract as LoggerContract;
use App\Support\Facades\Date;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;

class Updater
	implements UpdaterContract {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var TransactionScheduleRepositoryContract
	 */
	protected $transactionScheduleRepository;

	/**
	 * @var PeriodicityParserContract
	 */
	protected $transactionPeriodicityParserService;

	/**
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param PeriodicityParserContract $transactionPeriodicityParserService
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		PeriodicityParserContract $transactionPeriodicityParserService
	) {
		$this->log = $log;
		$this->db = $db;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
		$this->transactionPeriodicityParserService = $transactionPeriodicityParserService;
	}

	/**
	 * @inheritDoc
	 */
	public function updateTransactionSchedule(int $transactionId): UpdaterContract {
		$this->log->info('Updating schedule for transaction with id=%d.', $transactionId);

		$today = Carbon::today();

		$this->db->beginTransaction();

		try {
			$this->transactionScheduleRepository->deleteByTransactionId($transactionId);

			$this->transactionPeriodicityParserService
				->reset()
				->setTransactionId($transactionId)
				->setDateRange($this->getScheduleUpdateBeginningDate(), $this->getScheduleUpdateEndingDate());

			$dates = $this->transactionPeriodicityParserService->getRows();

			foreach ($dates as $date) {
				if ($date->lt($today)) {
					$this->log->info('-> NOT adding to schedule date \'%s\', because it\'s in the past.', $date->format('Y-m-d'));
					continue;
				}

				$this->log->info('-> adding date to schedule: %s.', $date->format('Y-m-d'));

				$transactionSchedule = new TransactionSchedule();
				$transactionSchedule->transaction_id = $transactionId;
				$transactionSchedule->date = $date;
				$transactionSchedule->save();
			}

			$this->db->commit();
		} catch (\Throwable $ex) {
			$this->db->rollBack();
			throw $ex;
		} finally {
			$this->log->info('Cleaning up...');

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